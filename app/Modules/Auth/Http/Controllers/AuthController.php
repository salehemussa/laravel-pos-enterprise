<?php

namespace App\Modules\Auth\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Modules\Auth\Actions\RegisterUserAction;
use App\Modules\Auth\Actions\LoginUserAction;
use App\Modules\Auth\Http\Requests\RegisterRequest;
use App\Modules\Auth\Http\Requests\LoginRequest;
use App\Modules\Auth\DTOs\RegisterData;
use App\Modules\Auth\DTOs\LoginData;
use App\Modules\Auth\Http\Resources\AuthUserResource;

class AuthController extends Controller{
    public function register(RegisterRequest $request, RegisterUserAction $registerUser): JsonResponse {
        $user = $registerUser->execute(RegisterData::fromRequest($request));

        // Optional: auto-login immediately after registration
        $token = auth('api')->login($user);

        return response()->json([
            'message' => 'Registration successful.',
            'token' => $token,
            'token_type' => 'bearer', 
            'expires_in' => auth('api')->factory()->getTTL() * 60, // seconds
            'user' => new AuthUserResource($user),
        ], 201);
    }

    public function login(LoginRequest $request, LoginUserAction $loginUser): JsonResponse {
        $result = $loginUser->execute(LoginData::fromRequest($request));

        return response()->json([
            'message' => 'Login successful.',
            'token' => $result['token'],
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => new AuthUserResource($result['user']),
        ]);
    }

    public function me(): JsonResponse
    {
        // Returns the authenticated user
        return response()->json([
            'user' => new AuthUserResource(auth('api')->user()),
        ]);
    }

    public function refresh(): JsonResponse
    {
        // Refresh token (rotation)
        $token = auth('api')->refresh();

        return response()->json([
            'message' => 'Token refreshed.',
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    public function logout(): JsonResponse
    {
        // Invalidates the current token
        auth('api')->logout();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }
}
