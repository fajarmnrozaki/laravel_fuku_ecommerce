<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(['auth:sanctum','isadmin:admin'], except: ['index','show']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Categories = Categories::get();

        return response()->json([
            'message' => 'Categories retrieved successfully',
            'data' => $Categories
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validation function
        $dataValidate = $request->validate([
            'name' => 'required|max:255|unique:categories,id',
            'description' => 'required',
        ],
        [
            'name.required' => 'Category name is required.',
            'name.max' => 'Category name must not exceed 255 characters.',
            'description.required' => 'Description is required.',
            'name.unique' => 'Category name must be unique and not same.',
        ]);

        // Insert category data into database
        $Categories = Categories::create($dataValidate);

        // respond function
        return response()->json([
            'message' => 'Category data is valid and has been stored successfully',
            'data' => $Categories
        ],201);  
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Categories = Categories::find($id);

        if(!$Categories){
            return response()->json([
                'message' => 'Category not found'
            ],404);
        }

        return response()->json([
            'message' => 'Detail of category retrieved successfully',
            'data' => $Categories
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Categories = Categories::find($id);
        if(!$Categories){
            return response()->json([
                'message' => 'Category not found'
            ],404);
        }
        
        $dataValidate = $request->validate([
            'name' => 'required|max:255|unique:categories,id,'.$id,
            'description' => 'required',
        ],
        [
            'name.required' => 'Category name is required.',
            'name.max' => 'Category name must not exceed 255 characters.',
            'description.required' => 'Description is required.',
            'name.unique' => 'Category name must be unique and not same.',
        ]);
        
        $Categories->name = $request->input('name');
        $Categories->description = $request->input('description');
        $Categories->save();
        return response()->json([
            'message' => 'Category data is valid and has been updated successfully',
            'data' => $Categories
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Categories = Categories::find($id);
        if(!$Categories){
            return response()->json([
                'message' => 'Category not found'
            ],404);
        }

        $Categories->delete();
        return response()->json([
            'message' => 'Category has been deleted successfully'
        ],200);
    }
}
