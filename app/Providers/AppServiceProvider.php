<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        $this->app['mail.manager']->extend('brevo', function (array $config) {
            return (new \Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory)->create(
                new \Symfony\Component\Mailer\Transport\Dsn(
                    'brevo+api',
                    'default',
                    $config['key'] ?? ''
                )
            );
        });
    }
}
