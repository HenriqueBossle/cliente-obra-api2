<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_change_waits_for_confirmation_and_sends_link_to_pending_email(): void
    {
        Http::fake();

        $user = User::factory()->create();
        $newEmail = 'new-address@example.com';

        Sanctum::actingAs($user);

        $response = $this->putJson('/api/profile', [
            'name' => 'Updated Name',
            'email' => $newEmail,
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('user.email', $user->email)
            ->assertJsonPath('user.pending_email', $newEmail);

        $this->assertSame($user->email, $user->fresh()->email);
        $this->assertSame($newEmail, $user->fresh()->pending_email);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());

        Http::assertSent(function (Request $request) use ($newEmail, $user) {
            return $request->url() === 'https://api.brevo.com/v3/smtp/email'
                && $request['to'][0]['email'] === $newEmail
                && str_contains(
                    $request['htmlContent'],
                    '/api/verify-pending-email/'.$user->id.'/'.sha1($newEmail)
                );
        });
    }

    public function test_signed_pending_email_link_changes_and_verifies_email(): void
    {
        $user = User::factory()->create([
            'pending_email' => 'new-address@example.com',
        ]);
        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.pending-email',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->pending_email),
            ]
        );

        $this->getJson($verificationUrl)
            ->assertOk()
            ->assertJson([
                'message' => 'E-mail alterado e verificado com sucesso.',
            ]);

        $user = $user->fresh();
        $this->assertSame('new-address@example.com', $user->email);
        $this->assertNull($user->pending_email);
        $this->assertTrue($user->hasVerifiedEmail());
        Event::assertDispatched(Verified::class);
    }

    public function test_pending_email_is_not_changed_with_invalid_hash(): void
    {
        $user = User::factory()->create([
            'pending_email' => 'new-address@example.com',
        ]);
        $verificationUrl = URL::temporarySignedRoute(
            'verification.pending-email',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1('another-address@example.com'),
            ]
        );

        $this->getJson($verificationUrl)->assertForbidden();

        $user = $user->fresh();
        $this->assertNotSame('new-address@example.com', $user->email);
        $this->assertSame('new-address@example.com', $user->pending_email);
    }

    public function test_email_change_cannot_reuse_another_users_pending_email(): void
    {
        $emailOwner = User::factory()->create([
            'pending_email' => 'reserved-address@example.com',
        ]);
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->putJson('/api/profile', [
            'name' => $user->name,
            'email' => $emailOwner->pending_email,
        ])->assertUnprocessable();

        $this->assertNull($user->fresh()->pending_email);
    }

    public function test_pending_email_cannot_be_used_for_password_recovery(): void
    {
        Http::fake();

        $user = User::factory()->create([
            'pending_email' => 'pending-address@example.com',
        ]);

        $this->postJson('/api/auth/forgot-password', [
            'email' => $user->pending_email,
        ])->assertOk();

        Http::assertNothingSent();
    }

    public function test_pending_email_cannot_be_used_to_register_another_user(): void
    {
        $user = User::factory()->create([
            'pending_email' => 'pending-address@example.com',
        ]);

        $this->postJson('/api/register', [
            'name' => 'New User',
            'email' => $user->pending_email,
            'password' => 'secure-password',
        ])->assertUnprocessable();

        $this->assertDatabaseMissing('users', [
            'email' => $user->pending_email,
        ]);
    }
}
