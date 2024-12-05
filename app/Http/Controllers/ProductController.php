<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    /**
     * List all products.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $products = Product::with('api')->get();
            if(session('success')) {
                return Inertia::render('Product', [
                    'products' => $products,
                    'flash' => ['success' => session('success')],
                ]);
            }
            elseif(session('error')) {
                return Inertia::render('Product', [
                    'products' => $products,
                    'flash' =>  ['error' => session('error')],
                ]);
            }
            else{
                return Inertia::render('Product', [
                    'products' => $products,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch products: '. $e->getMessage()], 500);
        }
    }

    /**
     * Show a single product.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $product = Product::with('api')->findOrFail($id);
            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to product API: '.$e->getMessage()], 500);
        }
    }
}
