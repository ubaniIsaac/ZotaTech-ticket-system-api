<?php

namespace App\Http\Controllers\api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\{SignUpRequest, LoginRequest};
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    // Methods for authentication functionality

    public function login(LoginRequest $request): JsonResponse
    {
        // Logic for handling user login
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->generateUserRole();

        return response()->json([
            'message' => 'User logged in successfully',
            'user' => $user,
            'token' => $token
        ], Response::HTTP_OK);
    }

    public function register(SignUpRequest $request): JsonResponse
    {
        // Logic for handling user registration
        $user = User::create($request->validated());

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], Response::HTTP_CREATED);
    }
}
