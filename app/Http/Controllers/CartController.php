<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // view cart details
    public function view()
    {
        $cartItems = Cart::with('product')->orderBy('id', 'desc')
                    ->get();

        $payableAmount = Cart::sum('total_amount');

        $taxPercentageGroup = $cartItems->groupBy('product.tax_percentage');

        $taxDetails = [];
        foreach ($taxPercentageGroup as $taxPercentage => $cart)
        {
            $taxAmount = $cart->sum('tax_amount');
            $taxDetails[$taxPercentage] = $taxAmount;
        }

        return view('cart', [
            'cartItems' => $cartItems,
            'payableAmount' => $payableAmount,
            'taxDetails' => $taxDetails
        ]);
    }

    // add to cart
    public function addToCart(Request $request, $productId)
    {
        try {
            $product = Product::findOrFail($productId);

            $cartItem = Cart::where('product_id', $productId)->first();
            $count = $request->count;
            $taxPercentage = $product->tax_percentage;
            $subtotal = $product->price * $count;
            $taxAmount = ($subtotal * $taxPercentage) / 100;
            $totalAmount = $subtotal + $taxAmount;

            if($count <= 0 || $count > $product->quantity)
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid quantity. Check again.'
                ], 400);
            }
            else if ($cartItem)
            {
                $cartItem->count = $count;
                $cartItem->subtotal = $subtotal;
                $cartItem->tax_amount = $taxAmount;
                $cartItem->total_amount = $totalAmount;
                $cartItem->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Updated cart.'
                ], 200);
            }
            else
            {
                Cart::create([
                    'product_id' => $product->id,
                    'count' => $count,
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $totalAmount
                ], 200);

                return response()->json([
                    'status' => true,
                    'message' => 'Added to Cart.',
                ], 200);
            }
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error.',
                'error' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // update cart
    public function cartUpdate(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $cartItem = Cart::where('product_id', $product->id)->first();

            if ($cartItem)
            {
                $count = $request->input('count');

                if ($count < 0)
                {
                    return response()->json([
                        'status' => false,
                        'message' => 'Please input a valid quantity.',
                        'error' => 'Error: Quantity is negative'
                    ], 400);
                }
                else if ($count > $product->quantity)
                {
                    return response()->json([
                        'status' => false,
                        'message' => 'Cannot add to cart. Exceeded available quantity.',
                        'error' => 'Error: Provide value within available quantity'
                    ], 400);
                }
                else
                {
                    $subtotal = $product->price * $count;
                    $taxAmount = ($subtotal * $product->tax_percentage) / 100;
                    $totalAmount = $subtotal + $taxAmount;
                    $cartItem->count = $count;
                    $cartItem->subtotal = $subtotal;
                    $cartItem->tax_amount = $taxAmount;
                    $cartItem->total_amount = $totalAmount;
                    $cartItem->save();

                    return response()->json([
                        'status' => true,
                        'message' => 'Cart updated.'
                    ], 200);
                }
            }
            else
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Add item to cart to change quantity.'
                ], 500);
            }
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error.',
                'error' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function removeFromCart($id)
    {
        try
        {
            $cart = Cart::findOrFail($id);

            $cart->delete();

            return response()->json([
                'status' => true,
                'message' => 'Product removed from cart.'
            ], 200);
        }
        catch (ModelNotFoundException $e)
        {
            return response()->json([
                'status' => 'false',
                'message' => 'Product not found.',
                'error' => 'Error: ' . $e->getMessage()
            ], 404);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error',
                'error' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
