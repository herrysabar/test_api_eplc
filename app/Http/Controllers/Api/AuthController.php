<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Handle login request.
     * Returns token if credentials are valid.
     */
    public function login(Request $request): JsonResponse
    {
        $email = $request->input('email');
        $password = $request->input('password');

        // Hardcoded credentials as per requirement
        if ($email === 'admin@test.com' && $password === 'password') {
            return response()->json([
                'token' => 'secret-token-123'
            ], 200);
        }

        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }
}
