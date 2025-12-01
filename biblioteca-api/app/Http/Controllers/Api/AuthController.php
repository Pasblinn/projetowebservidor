<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)
                    ->where('ativo', true)
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Credenciais inválidas', 401);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->successResponse([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'nome' => $user->nome,
                'email' => $user->email,
                'tipo' => $user->tipo,
            ],
            'token' => $token,
        ], 'Login realizado com sucesso');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logout realizado com sucesso');
    }

    public function me(Request $request)
    {
        return $this->successResponse([
            'id' => $request->user()->id,
            'username' => $request->user()->username,
            'nome' => $request->user()->nome,
            'email' => $request->user()->email,
            'tipo' => $request->user()->tipo,
            'ativo' => $request->user()->ativo,
        ]);
    }
}
