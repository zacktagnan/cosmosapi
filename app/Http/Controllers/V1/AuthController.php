<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;

#[Group('Authentication')]
class AuthController extends Controller
{
    #[BodyParam(name: 'email', example: 'scribe@example.com')]
    #[BodyParam(name: 'password', example: 'password')]
    /**
     * Login
     *
     * Iniciar sesión
     *
     * @unauthenticated
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Invalid credentials',
                'timestamp' => now()->toISOString()
            ], 401);
        }

        // Revoke all existing tokens
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at->toISOString(),
                'updated_at' => $user->updated_at->toISOString()
            ],
        ]);
    }

    #[Authenticated]
    public function logout(): JsonResponse
    {
        /** @var PersonalAccessToken|null $token */
        $token = Auth::user()?->currentAccessToken();
        $token?->delete();

        return response()->json(null, 204);
    }
}
