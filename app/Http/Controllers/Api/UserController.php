<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Exibe os dados do perfil do usuário autenticado.
     */
    public function show(Request $request)
    {
        // Retorna os dados do usuário logado em formato JSON
        return response()->json([
            'status' => true,
            'user' => $request->user() // ou auth()->user()
        ], 200);
    }

    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();

        $user->update($request->validated());

        return response()->json([
            'message' => 'Perfil atualizado com sucesso',
            'user' => $user->fresh(),
        ]);
    }


    public function updatePassword(UpdatePasswordRequest $request)
    {
        $request->user()->update([
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'message' => 'Senha alterada com sucesso'
        ]);
    }

    /**
     * Exclui a conta do usuário autenticado.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required']
        ]);

        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Senha incorreta.'
            ], 401);
        }

        $user->delete();

        return response()->json([
            'message' => 'Conta excluída com sucesso.'
        ]);
    }
}
