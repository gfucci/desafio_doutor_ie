<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $fields = $request->validated();

        $user = User::create([
           'name' => $fields['name'],
           'email' => $fields['email'],
           'password' => bcrypt($fields['password'])
        ]);

        $response = [
            'user' => $user
        ];

        return response($response, 201);
    }

    public function token(LoginRequest $request)
    {
        $fields = $request->validated();
        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
               'message' => 'E-mail ou login inválido'
            ], 401);
        }

        $token = $user->createToken('usuario_logado')->plainTextToken;
        $response = [
            'message' => 'login efetuado!',
            'user' => $user,
            'token' => $token
        ];

        return response($response, 200);
    }
}
