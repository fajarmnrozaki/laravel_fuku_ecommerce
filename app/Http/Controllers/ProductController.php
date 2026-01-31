<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    // function to store product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'image' => 'required|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ],
        [
            'name.required' => 'Product name is required.',
            'name.max' => 'Product name must not exceed 255 characters.',
            'description.required' => 'Description is required.',
            'image.required' => 'Image is required.',
            'image.mimes' => 'Image must be a file of type: jpg, jpeg, png.',
            'image.max' => 'Image size must not exceed 2 MB.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a number.',
            'stock.required' => 'Stock is required.',
            'stock.numeric' => 'Stock must be a number.',
        ]);

        // If validation passes, return success response
        return response()->json(['message' => 'Product data is valid and has been stored successfully']);
    }
}
