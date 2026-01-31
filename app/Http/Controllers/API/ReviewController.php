<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reviews;

class ReviewController extends Controller
{
    // CREATE or UPDATE Review (USER ONLY)
    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'point' => 'required|numeric|between:1,5',
            'content' => 'required',
            'product_id' => 'required|exists:products,id'
        ],[
            'required' => ':attribute is required',
            'numeric' => ':attribute must be a number',
            'between' => ':attribute must be between :min and :max',
            'exists' => ':attribute not found'
        ]);

        $review = Reviews::updateOrCreate(
            [
                'user_id' => $user->id,
                'product_id' => $request->input('product_id')
            ],
            [
                'point' => $request->input('point'),
                'content' => $request->input('content')
            ]
        );

        return response()->json([
            "message" => "Review submitted successfully",
            "data" => $review
        ], 201);
    }

    // GET USER REVIEWS (AUTH USER)
    public function userReviews(Request $request)
    {
        $user = $request->user();

        $data = Reviews::with('product')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            "message" => "User review list",
            "data" => $data
        ], 200);
    }

    // GET ALL REVIEWS (ADMIN ONLY)
    public function index()
    {
        $reviews = Reviews::with(['product:id,name,image,price,stock', 'owner:id,name,email'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            "message" => "List Reviews",
            "data" => $reviews
        ], 200);
    }

    public function update(Request $request, $id)
    {
    $user = $request->user();

    // Validate input
    $validated = $request->validate([
        'point' => 'required|numeric|between:1,5',
        'content' => 'required'
    ]);

    $review = Reviews::where('id', $id)->where('user_id', $user->id)->first();

    if (! $review) {
        return response()->json([
            "message" => "Review not found or not owned by you"
        ], 404);
    }

    $review->update($validated);

    return response()->json([
        "message" => "Review updated successfully",
        "data" => $review
    ], 200);
    }
}
