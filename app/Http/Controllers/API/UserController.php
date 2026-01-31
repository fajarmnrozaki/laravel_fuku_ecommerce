<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();

        return response()->json([
            "message" => "List of users",
            "data" => $users
        ], 200);
    }

    public function show($id)
    {
        $user = User::with(['role','reviews','transactions'])->find($id);

        if (!$user) {
            return response()->json(["message" => "User not found"], 404);
        }

        return response()->json([
            "message" => "User details",
            "data" => $user
        ], 200);
    }

    public function update(Request $request, $id){
    // Validate input
    $request->validate([
        'role_id' => 'required|exists:roles,id'
    ]);

    // Find user
    $user = User::find($id);
    if (!$user) {
        return response()->json(["message" => "User not found"], 404);
    }

    // Update role
    $user->role_id = $request->role_id;
    $user->save();

    return response()->json([
        "message" => "Successfully updated user role",
        "data" => $user
    ], 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(["message" => "User not found"], 404);
        }

        $user->delete();

        return response()->json([
            "message" => "User deleted successfully"
        ], 200);
    }
}
