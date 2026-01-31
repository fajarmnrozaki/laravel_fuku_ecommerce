<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transactions;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'amount' => 'required|numeric',
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Products::find($request->product_id);

        if ($request->amount > $product->stock) {
            return response()->json([
                "message" => "Not enough stock"
            ], 400);
        }

        $total = $product->price * $request->amount;

        $transaction = Transactions::create([
            'amount' => $request->amount,
            'total' => $total,
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);

        return response()->json([
            "message" => "Transaction created successfully",
            "data" => $transaction
        ], 201);
    }

    public function userTransactions(Request $request)
    {
        $user = $request->user();

        $data = Transactions::with('product')
            ->where('user_id', $user->id)
            ->get();

        return response()->json([
            "message" => "User transactions list",
            "data" => $data
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $transaction = Transactions::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$transaction) {
            return response()->json(["message" => "Transaction not found"], 404);
        }

        $transaction->delete();

        return response()->json([
            "message" => "Transaction deleted successfully"
        ], 200);
    }

    // ADMIN
    public function index()
    {
        $data = Transactions::with(['product', 'owner'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            "message" => "List of transactions",
            "data" => $data
        ], 200);
    }

    // public function update(Request $request, $id)
    // {
    //     $transaction = Transactions::find($id);

    //     if (!$transaction) {
    //         return response()->json(["message" => "Transaction not found"], 404);
    //     }

    //     $request->validate([
    //         'status' => 'required|in:pending,success,cancel',
    //     ]);

    //     $transaction->status = $request->status;
    //     $transaction->save();

    //     return response()->json([
    //         "message" => "Transaction updated successfully",
    //         "data" => $transaction
    //     ], 200);
    // }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,success,cancel',
        ]);

        return DB::transaction(function () use ($request, $id) {

            $transaction = Transactions::with('product')->find($id);

            if (!$transaction) {
                return response()->json([
                    "message" => "Transaction not found"
                ], 404);
            }

            // Deduct stock ONLY when moving to success
            if (
                $request->status === 'success' &&
                $transaction->status !== 'success'
            ) {
                $product = $transaction->product;

                if ($product->stock < $transaction->amount) {
                    return response()->json([
                        "message" => "Insufficient stock"
                    ], 400);
                }

                $product->stock -= $transaction->amount;
                $product->save();
            }

            // Optional safety: restore stock if success → cancel
            if (
                $request->status === 'cancel' &&
                $transaction->status === 'success'
            ) {
                $product = $transaction->product;
                $product->stock += $transaction->amount;
                $product->save();
            }

            $transaction->status = $request->status;
            $transaction->save();

            return response()->json([
                "message" => "Transaction updated successfully",
                "data" => $transaction
            ], 200);
        });
    }
}
