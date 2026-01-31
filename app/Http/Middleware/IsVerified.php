<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->email_verified_at !== null) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Email belum verifikasi'
        ], 401);
    }
}
