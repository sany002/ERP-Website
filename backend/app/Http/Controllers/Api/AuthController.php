<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login(
            $request->string('email'),
            $request->string('password'),
            $request
        );

        return response()->json($result);
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function refresh(): JsonResponse
    {
        return response()->json($this->authService->refresh());
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json(
            $request->user()->load(['company', 'branch', 'roles.permissions'])
        );
    }
}
