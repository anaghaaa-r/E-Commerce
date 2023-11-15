<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrdersController extends Controller
{
    public function view()
    {
        $orders = Order::orderBy('id', 'desc')->get();

        $orderNumberGroup = $orders->groupBy('order_no');

        $totalAmount = [];
        foreach ($orderNumberGroup as $orderNumber => $order)
        {
            $amount = $order->sum('total_amount');
            $totalAmount[$orderNumber] = $amount;
        }

        return view('orders', [
            'orders' => $orderNumberGroup,
            'totalAmounts' => $totalAmount
        ]);
    }

    public function generateOrderNumber()
    {
        $currentTime = date('dmY');
        $randomNumber = mt_rand(300, 1500);

        $orderNumber = 'RD' . $currentTime . $randomNumber;

        return $orderNumber;
    }

    public  function checkOut()
    {
        try {
            $cartItems = Cart::with('product')->get();

            $generatedOrderNumber = $this->generateOrderNumber();

            foreach ($cartItems as $cartItem)
            {
                $product = Product::findOrFail($cartItem->product_id);

                $orderDetails = [
                    'order_no' => $generatedOrderNumber,
                    'product' => $cartItem->product->title,
                    'image' => $cartItem->product->image,
                    'description' => $cartItem->product->description,
                    'quantity' => $cartItem->count,
                    'tax_percentage' => $cartItem->product->tax_percentage,
                    'tax_amount' => $cartItem->tax_amount,
                    'total_amount' => $cartItem->total_amount
                ];
                Order::create($orderDetails);
                $productQuantity = $cartItem->product->quantity;

                $newQuantity = $productQuantity - $orderDetails['quantity'];

                $product->quantity = $newQuantity;
                $product->save();



                $cartItem->delete();
            }
            return redirect()->route('orders');
        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => 'Could not place order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
