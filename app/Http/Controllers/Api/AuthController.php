<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validação dos dados de entrada
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required', // Útil para identificar de onde vem o acesso
        ]);

        // 2. Busca o usuário pelo e-mail
        $user = User::where('email', $request->email)->first();

        // 3. Verifica se o usuário existe e se a senha está correta
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        // 4. Gera o token do Sanctum
        $token = $user->createToken($request->device_name)->plainTextToken;

        // 5. Retorna o token e os dados básicos do usuário
        return response()->json([
            'token' => $token,
            'user' => $user
        ], 200);
    }

    public function logout(Request $request)
    {
        // Remove o token atual que está sendo usado na requisição
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso'], 200);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password)
        ]);

        $token = $user->createToken('react-app')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }
}