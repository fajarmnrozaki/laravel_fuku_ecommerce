<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(['auth:sanctum', 'isadmin:admin'], except: ['index','show']),
        ];
    }

    // PUBLIC - GET ALL
    public function index()
    {
        // $products = Products::get();
        $products = Products::with('category')->withCount('reviews')->get();

        return response()->json([
            "message" => "List Products",
            "data" => $products
        ], 200);
    }

    // PUBLIC - GET DETAIL
    public function show(string $id)
    {
        // $product = Products::find($id);
        $product = Products::with('category','reviews.owner')->withCount('reviews')->find($id);

        if(!$product){
            return response()->json([
                "message" => "Product not found",
            ], 404);
        }

        return response()->json([
            "message" => "Detail Product",
            "data" => $product
        ], 200);
    }

    // ADMIN ONLY - CREATE
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|max:255',
            'description' => 'required',
            'image'       => 'required|mimes:png,jpg,jpeg|max:2048',
            'price'       => 'required|numeric',
            'stock'       => 'required|numeric'
        ],
        [
           'name.required' => "Product name is required",
           'description.required' => "Product description is required",
           'image.required' => "Product image is required and file must be png, jpg, or jpeg",
           'price.required' => "Product price is required",
           'stock.required' => "Product stock is required", 
        ]);

        // New model
        $product = new Products;

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;

        // UPLOAD IMAGE TO CLOUDINARY
        if($request->hasFile('image')){
            $fileImage = $request->file('image');

            $path = Storage::disk('cloudinary')->put('product', $fileImage);
            $url  = Storage::disk('cloudinary')->url($path);

            $product->image = $url;
            $product->image_id = $path;
        }

        $product->user_id = auth()->id();
        $product->category_id = $request->category_id;

        $product->save();

        return response()->json([
            "message" => "Product created successfully",
            "data" => $product
        ], 201);
    }

    // ADMIN ONLY - UPDATE
    public function update(Request $request, string $id)
    {
        $product = Products::find($id);

        if(!$product){
            return response()->json([
                "message" => "Product not found",
            ], 404);
        }

        $validated = $request->validate([
            'name'        => 'required|max:255',
            'description' => 'required',
            'image'       => 'nullable|mimes:png,jpg,jpeg|max:2048',
            'price'       => 'required|numeric',
            'stock'       => 'required|numeric'
        ],
        [
              'name.required' => "Prduct name is required",
              'description.required' => "Product description is required",
              'image.mimes' => "Product image must be png, jpg, or jpeg",
              'price.required' => "Product price is required",
              'stock.required' => "Product stock is required",
        ]);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;

        // Replace image if needed
        if($request->hasFile('image')){

            if($product->image_id){
                Storage::disk('cloudinary')->delete($product->image_id);
            }

            $fileImage = $request->file('image');

            $path = Storage::disk('cloudinary')->put('product', $fileImage);
            $url  = Storage::disk('cloudinary')->url($path);

            $product->image = $url;
            $product->image_id = $path;
        }

        $product->save();

        return response()->json([
            "message" => "Successfully updated Product",
            "data" => $product
        ], 200);
    }

    // ADMIN ONLY - DELETE
    public function destroy(string $id)
    {
        $product = Products::find($id);

        if(!$product){
            return response()->json([
                "message" => "Product not found",
            ], 404);
        }

        if($product->image_id){
            Storage::disk('cloudinary')->delete($product->image_id);
        }

        $product->delete();

        return response()->json([
            "message" => "Product deleted successfully"
        ], 200);
    }
}
