<?php

use App\Http\Controllers\API\CategoryController as APICategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\ProductController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController as APIProductController;
use App\Http\Controllers\API\TransactionController as APITransactionController;
use App\Http\Controllers\API\ReviewController as APIReviewController;
use App\Http\Controllers\API\UserController as APIUserController;


//  API Routes for Product -> Post Method
// route::post('/v1/products', [ProductController::class, 'store']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// route::prefix('v1')->group(function () {
//     Route::apiResource('categories', APICategoryController::class);
// });

// Route::prefix('v1')->group(function () {
//     // Route::apiResource('categories', ProductController::class);
//     // Route::apiResource('categories', ProductController::class)->middleware('auth:sanctum','isadmin');
//     // Route::apiResource('categories', APICategoryController::class)->middleware('auth:sanctum','isadmin');
//     // Route::apiResource('categories', APICategoryController::class);
//     // Route::apiResource('products', APIProductController::class);
    
//     // AUTH — Public
//     Route::post('/auth/register', [AuthController::class,'register']);
//     Route::post('/auth/login', [AuthController::class,'login']);

//     // AUTH — Protected
//     Route::middleware('auth:sanctum')->group(function () {
//         Route::get('/auth/user', [AuthController::class, 'user']);
//         Route::post('/auth/logout', [AuthController::class, 'logout']);
//     });

//     // CATEGORY & PRODUCT
//     Route::apiResource('categories', APICategoryController::class);
//     Route::apiResource('products', APIProductController::class);

//     // REVIEWS — User Only
//     Route::middleware('auth:sanctum')->prefix('reviews')->group(function () {
//         Route::post('/', [APIReviewController::class, 'store']);
//         Route::get('/user', [APIReviewController::class, 'userReviews']);
//     });

//     // USERS — Admin Only
//     Route::middleware(['auth:sanctum','isadmin:admin'])->prefix('users')->group(function () {
//         Route::get('/', [APIUserController::class, 'index']);
//         Route::get('/{id}', [APIUserController::class, 'show']);
//         Route::delete('/{id}', [APIUserController::class, 'destroy']);
//     });

//     // TRANSACTIONS — USER
//     Route::prefix('transaction')->middleware('auth:sanctum')->group(function () {
//         Route::post('/', [APITransactionController::class, 'store']);
//         Route::get('/user', [APITransactionController::class, 'userTransactions']);
//         Route::delete('/{id}', [APITransactionController::class, 'destroy']);
//     });

//     // TRANSACTIONS — ADMIN
//     Route::prefix('transaction')->middleware(['auth:sanctum','isadmin:admin'])->group(function () {
//         Route::get('/', [APITransactionController::class, 'index']);
//         Route::put('/{id}', [APITransactionController::class, 'update']);
//     });
// });

Route::prefix('v1')->group(function () {

//     // ---------------- AUTH ----------------
//     Route::post('/auth/register', [AuthController::class,'register']);
//     Route::post('/auth/login', [AuthController::class,'login']);

//     Route::middleware('auth:sanctum')->group(function () {
//         Route::get('/auth/user', [AuthController::class, 'user']);
//         Route::post('/auth/logout', [AuthController::class, 'logout']);
//     });


//     // ---------------- PUBLIC CRUD ----------------
//     Route::apiResource('categories', APICategoryController::class)
//         ->middleware(['auth:sanctum', 'isadmin:admin']);

//     Route::apiResource('products', APIProductController::class)
//         ->middleware(['auth:sanctum', 'isadmin:admin']);



//     // ---------------- REVIEWS (AUTH USER) ----------------
//     Route::middleware('auth:sanctum')->prefix('reviews')->group(function () {
//         Route::post('/', [APIReviewController::class, 'store']);
//         Route::get('/user', [APIReviewController::class, 'userReviews']);
//     });


//     // ---------------- USERS (ADMIN) ----------------
//     Route::middleware(['auth:sanctum', 'isadmin:admin'])
//         ->prefix('users')
//         ->group(function () {
//             Route::get('/', [APIUserController::class, 'index']);
//             Route::get('/{id}', [APIUserController::class, 'show']);
//             Route::put('/{id}', [APIUserController::class, 'update']);
//             Route::delete('/{id}', [APIUserController::class, 'destroy']);
//         });


//     // ---------------- TRANSACTIONS ----------------

//     // USER
//     Route::middleware('auth:sanctum')->prefix('transaction')->group(function () {
//         Route::post('/', [APITransactionController::class, 'store']);
//         Route::get('/user', [APITransactionController::class, 'userTransactions']);
//         Route::delete('/{id}', [APITransactionController::class, 'destroy']);
//     });

//     // ADMIN
//     Route::middleware(['auth:sanctum', 'isadmin:admin'])
//         ->prefix('transaction')
//         ->group(function () {
//             Route::get('/', [APITransactionController::class, 'index']);
//             Route::put('/{id}', [APITransactionController::class, 'update']);
//         });

// });

    // PUBLIC
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // AUTH REQUIRED
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/user', [AuthController::class, 'user']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        Route::post('/auth/generate-otp-code', [AuthController::class, 'generateOtp']);
        Route::post('/auth/verification-email', [AuthController::class, 'verify']);
    });


    /*
    |--------------------------------------------------------------------------
    | CATEGORY ROUTES
    |--------------------------------------------------------------------------
    */
    // PUBLIC
    Route::get('/categories', [APICategoryController::class, 'index']);
    Route::get('/categories/{id}', [APICategoryController::class, 'show']);

    // ADMIN ONLY
    Route::middleware(['auth:sanctum', 'isadmin:admin'])->group(function () {
        Route::post('/categories', [APICategoryController::class, 'store']);
        Route::put('/categories/{id}', [APICategoryController::class, 'update']);
        Route::delete('/categories/{id}', [APICategoryController::class, 'destroy']);
    });


    /*
    |--------------------------------------------------------------------------
    | PRODUCT ROUTES
    |--------------------------------------------------------------------------
    */
    // PUBLIC
    Route::get('/products', [APIProductController::class, 'index']);
    Route::get('/products/{id}', [APIProductController::class, 'show']);

    // ADMIN ONLY
    Route::middleware(['auth:sanctum', 'isadmin:admin'])->group(function () {
        Route::post('/products', [APIProductController::class, 'store']);
        Route::put('/products/{id}', [APIProductController::class, 'update']);
        Route::delete('/products/{id}', [APIProductController::class, 'destroy']);
    });


    /*
    |--------------------------------------------------------------------------
    | REVIEWS (USER ONLY)
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth:sanctum')->prefix('reviews')->group(function () {
        // Route::post('/', [APIReviewController::class, 'store']);
        Route::post('/', [APIReviewController::class, 'store'])->middleware('isverified');
        Route::put('/{id}', [APIReviewController::class, 'update']);
        Route::get('/user', [APIReviewController::class, 'userReviews']);
    });
    // ---------------- REVIEWS (ADMIN) ----------------
    Route::middleware(['auth:sanctum', 'isadmin:admin'])->get('/reviews', [APIReviewController::class, 'index']);

    /*
    |--------------------------------------------------------------------------
    | USERS (ADMIN ONLY)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth:sanctum', 'isadmin:admin'])->prefix('users')->group(function () {
        Route::get('/', [APIUserController::class, 'index']);
        Route::get('/{id}', [APIUserController::class, 'show']);
        Route::put('/{id}', [APIUserController::class, 'update']); // Update role
        Route::delete('/{id}', [APIUserController::class, 'destroy']);
    });

    // ---------------- REVIEWS (ADMIN) ----------------
    Route::middleware(['auth:sanctum', 'isadmin:admin'])->get('/reviews', [APIReviewController::class, 'index']);


    /*
    |--------------------------------------------------------------------------
    | TRANSACTIONS
    |--------------------------------------------------------------------------
    */
    // USER
    Route::middleware('auth:sanctum')->prefix('transaction')->group(function () {
        Route::post('/', [APITransactionController::class, 'store']); // Create purchase
        Route::get('/user', [APITransactionController::class, 'userTransactions']); // View own history
        Route::delete('/{id}', [APITransactionController::class, 'destroy']); // Cancel (only user's)
    });

    // ADMIN
    Route::middleware(['auth:sanctum', 'isadmin:admin'])->prefix('transaction')->group(function () {
        Route::get('/', [APITransactionController::class, 'index']); // Get all
        Route::put('/{id}', [APITransactionController::class, 'update']); // Update status
    });
});