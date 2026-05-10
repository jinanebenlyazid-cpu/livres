<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            return response()->json(['message' => 'Identifiants invalides.'], 422);
        }

        $plainToken = Str::random(60);
        $request->user()->update(['api_token' => hash('sha256', $plainToken)]);

        return response()->json([
            'message' => 'Connexion reussie.',
            'token' => $plainToken,
            'user' => $request->user(),
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $plainToken = Str::random(60);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'membre',
            'api_token' => hash('sha256', $plainToken),
        ]);

        return response()->json([
            'message' => 'Compte cree avec succes.',
            'token' => $plainToken,
            'user' => $user,
        ], 201);
    }
}
