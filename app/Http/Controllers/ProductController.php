<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
class ProductController extends Controller
{
    /**
     * List all products.
     *
     * @return Response
     */
    public function index(): Response
    {
        try {
            $products = Product::with('api')->get();
            return Inertia::render('Products', [
                'product' => $products,
                'flash' => [
                    'success' => session('success') ? session('success') : null,
                    'error' => session('error') ? session('error') : null
                ],
            ]);
        } catch (\Exception $e) {
            return Inertia::render('Product', [
                'product' => [],
                'flash' => ['error' => 'Error fetching Product: ' . $e->getMessage()],
            ]);
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
