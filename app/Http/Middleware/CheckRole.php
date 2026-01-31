<?php

// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Http\Request;
// use Symfony\Component\HttpFoundation\Response;
// Use App\Models\Roles;

// class CheckRole
// {
//     /**
//      * Handle an incoming request.
//      */
//     public function handle(Request $request, Closure $next, $role): Response
//     {
//         $user = $request->user();

//         $roleAdmin = Roles::where('name', 'admin')->first();


//         // Pastikan user terautentikasi
//         if (! $user) {
//             return response()->json(['message' => 'Unauthorized'], 401);
//         }

//         // // Cek role user
//         // if ($user->role !== $role) {
//         //     return response()->json(['message' => 'Forbidden Access denied'], 403);
//         // }

//         if($user->role_id !== $roleAdmin->id){
//             return response()->json(['message' => 'Forbidden Access denied'], 403);
//         }

//         // Lanjutkan ke request berikutnya
//         return $next($request);
//     }
// }


// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Http\Request;
// use App\Models\Roles;

// class CheckRole
// {
//     public function handle(Request $request, Closure $next, $roleName)
//     {
//         $user = $request->user();

//         if (! $user) {
//             return response()->json(['message' => 'Unauthorized'], 401);
//         }

//         // User role name
//         $userRole = $user->role->name;

//         // If user role DOES NOT MATCH the required role → BLOCK
//         if ($userRole !== $roleName) {
//             return response()->json(['message' => 'Access denied — Admins only'], 403);
//         }

//         return $next($request);
//     }
// }

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    // /**
    //  * Handle an incoming request.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \Closure  $next
    //  * @param  string  $roleName  → expected role (e.g., 'admin')
    //  * @return \Symfony\Component\HttpFoundation\Response
    //  */
    public function handle(Request $request, Closure $next, $roleName): Response
    {
        // Ensure the user is authenticated
        $user = $request->user();
        if (! $user) {
            return response()->json([
                'message' => 'Unauthorized — Please login first'
            ], 401); // 401 = Unauthenticated
        }

        // Ensure the user has a role relationship
        if (! $user->role) {
            return response()->json([
                'message' => 'Forbidden — This account does not have a role'
            ], 403);
        }

        // Check if user role is the one required (e.g., admin)
        if ($user->role->name !== $roleName) {
            return response()->json([
                'message' => 'Forbidden — You do not have permission to access this resource'
            ], 403); // 403 = Forbidden
        }

        // Allow the request to continue
        return $next($request);
    }
}