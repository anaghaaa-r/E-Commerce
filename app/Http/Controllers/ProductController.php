<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function viewAdmin(Request $request)
    {
        $products = Product::orderBy('id', 'desc')->get();

        return view('product', [
            'products' => $products
        ]);
    }

    public function viewUser(Request $request)
    {
        $products = Product::orderBy('id', 'desc')->get();

        return view('product-list', compact('products'));
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'tax_percentage' => 'nullable|integer|min:0|max:100',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Check data before submission.',
                'error' => $validator->errors()
            ], 422);
        }

        try {
            $product = new Product();

            $product->title = $request->title;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->tax_percentage = $request->tax_percentage;

            $filename = $request->file('image')->hashName();
            $imagePath = 'uploads/' . $filename;
            $request->file('image')->storeAs('public/' . $imagePath);

            $product->image = $imagePath;
            $product->save();

            return response()->json([
                'status' => true,
                'message' => 'Product Added.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error.',
                'error' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // update
    public function edit(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $cartItem = Cart::where('product_id', $id)->first();

            $validator = Validator::make($request->all(), [
                'title' => 'nullable|string',
                'description' => 'nullable|string',
                'price' => 'nullable|numeric',
                'quantity' => 'nullable|integer',
                'tax_percentage' => 'nullable|integer|min:0|max:100',
                'image' => 'nullable|image|mimes:jpg,png,jpeg,gif'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Inspect data for errors.',
                    'error' => 'Error: ' . $validator->errors()
                ], 422);
            }

            $productDetails = [];

            $updatableFields = [
                'title',
                'description',
                'price',
                'quantity',
                'tax_percentage'
            ];

            foreach ($updatableFields as $field) {
                if ($request->filled($field)) {
                    $productDetails[$field] = $request->input($field);
                }
            }

            if ($request->hasFile('image')) {
                $filename = $request->file('image')->hashName();
                $imagePath = 'uploads/' . $filename;
                $request->file('image')->storeAs('public/' . $imagePath);
                $productDetails['image'] = $imagePath;
            }

            if($request->filled('tax_percentage'))
            {
                if($cartItem)
                {
                    $subtotal = $cartItem->subtotal;
                    $taxPercentage = $productDetails['tax_percentage'];
                    $taxAmount = ($subtotal * $taxPercentage) / 100;
                    $totalAmount = $subtotal + $taxAmount;
                    $cartItem->tax_amount = $taxAmount;
                    $cartItem->total_amount = $totalAmount;
                    $cartItem->save();
                }
            }

            $product->update($productDetails);

            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully.'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.',
                'error' => 'Error: ' . $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error.',
                'error' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // delete
    public function delete($id)
    {
        try {
            $product = Product::findOrFail($id);

            $product->delete();

            return response()->json([
                'status' => true,
                'message' => 'Product Deleted.'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error.',
                'error' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
