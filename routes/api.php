<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConstructionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('throttle:8,1')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/login', [AuthController::class, 'login']);
});


    Route::get('/verify-email/{id}/{hash}', function (
        Request $request,
        $id,
        $hash
    ) {

        $user = User::find($id);

        if (!$user) {
            return view('controllers.auth.verify-success');
        }

        if (! hash_equals(
            sha1($user->getEmailForVerification()),
            $hash
        )) {
            return response()->json([
                'message' => 'Link inválido'
            ], 403);
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return response()->json([
            'message' => 'Email verificado com sucesso'
        ]);

    })
    ->middleware('signed')
    ->name('verification.verify');

    Route::post(
        '/email/resend-verification',
        [AuthController::class, 'resendVerification']
    );

// Privadas
Route::middleware('auth:sanctum')->group(function () {

    Route::middleware('throttle:60,1')->group(function () {

        Route::get('constructions/search', [ConstructionController::class, 'search']);

        Route::get(
            '/constructions/pdf',
            [ConstructionController::class, 'generateAllPdf']
        );

        Route::get(
            '/constructions/{construction}/pdf',
            [ConstructionController::class, 'generatePdf']
        );
        
        Route::get('/constructions', [ConstructionController::class, 'index']);

        Route::get('/constructions/{construction}', [ConstructionController::class, 'show']);

        Route::get('/profile', [UserController::class, 'show']);

        Route::put('/profile', [UserController::class, 'update']);

        Route::put('/profile/password', [UserController::class, 'updatePassword']);
    
        // Rota para deletar o perfil
        Route::delete('/profile', [UserController::class, 'destroy']);

    });


    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('constructions', ConstructionController::class)
        ->except(['index', 'show']);


});

Route::prefix('auth')->group(function () {
    // 1️⃣ Solicitar código OTP
    Route::post('forgot-password', [PasswordResetController::class, 'forgotPassword'])
        ->middleware('throttle:5,1')   // 5 requisições por minuto por IP
        ->name('auth.forgot-password');
    // 2️⃣ Redefinir senha usando OTP
    Route::post('reset-password', [PasswordResetController::class, 'resetPassword'])
        ->middleware('throttle:5,1')
        ->name('auth.reset-password');
});
