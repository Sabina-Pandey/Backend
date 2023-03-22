<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request, int $productId)
    {
        $user = Auth::user();

        $request->validate([
            "address" => "required",
            "number" => "required",
            "quantity" => "required"
        ]);

        $order = Order::create([
            "user_id" => $user->id,
            "product_id" => $productId,
            "address" => $request->address,
            "number" => $request->number,
            "quantity" => $request->quantity
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'order created successfully',
            'order' => $order,
        ]);
    }
}
