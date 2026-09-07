<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtAuthenticate
{
    /**
     * Handle an incoming request.
     * Reads the JWT token from the secure HTTP-only cookie,
     * validates it, and sets the authenticated user for the request.
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->cookie('jwt_token');

        if ($token) {
            try {
                $user = JWTAuth::setToken($token)->authenticate();

                if ($user && $user->is_active) {
                    // Inject the user into Laravel's default auth guard
                    // This satisfies both the standard 'auth' middleware and Livewire
                    \Illuminate\Support\Facades\Auth::setUser($user);
                }
            } catch (\Exception $e) {
                // Token invalid or expired. Just ignore and let the standard auth
                // middleware handle the rejection / redirection.
            }
        }

        return $next($request);
    }
}
