<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth; // Add this import

class TokenAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-Auth-Token');
        
        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        Auth::setUser($user); // Changed from auth()->setUser()
        
        return $next($request);
    }
}