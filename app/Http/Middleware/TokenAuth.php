<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized - Token not provided'
            ], 401);
        }

        if ($token !== 'secret-token-123') {
            return response()->json([
                'message' => 'Unauthorized - Invalid token'
            ], 401);
        }

        return $next($request);
    }
}
