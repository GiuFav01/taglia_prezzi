<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\AsinsList;
use App\Services\ShopifyService;
use Illuminate\Http\JsonResponse;
use App\Services\AdvAmazonApiService;
use App\Services\ApiManagementService;

class AsinsListController extends Controller
{
    /**
     * List all ASINs with their associated APIs.
     *
     * @return Response
     */
    public function index(): Response
    {
        try {
            $asins = AsinsList::with('api')->get();
            return Inertia::render('AsinsList', [
                'asins' => $asins,
                'flash' => [
                    'success' => session('success') ? session('success') : null,
                    'error' => session('error') ? session('error') : null
                ],
            ]);
        } catch (\Exception $e) {
            return Inertia::render('AsinsList', [
                'asins' => [],
                'flash' => ['error' => 'Errore nel recupero degli ASIN: ' . $e->getMessage()],
            ]);
        }
    }

    /**
     * Show details of a specific ASIN by ID.
     *
     * @param string $asinId
     * @return JsonResponse
     */
    public function show(string $asinId): JsonResponse
    {
        try {
            $asin = AsinsList::with('api')->findOrFail($asinId);
            return response()->json($asin);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Errore nel recupero dell\'ASIN: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Show details of a specific ASIN by ID.
     *
     * @param AsinsList $asin
     * @return JsonResponse
     */
    public function execute(AsinsList $asin): JsonResponse
    {
        try {
            $asinId = $asin->id;
            $service = new ApiManagementService();
            $asin = AsinsList::with('api')->findOrFail($asinId);
            $response = $service->getProdutcData([$asin->asin], $asin->api);
            if (!$response['success']) {
                return response()->json(['error' => 'Errore durante il recupero dei dettagli del prodotto: ' . $response['error']], 500);
            }
            return response()->json($response['message']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Errore nel recupero dell\'ASIN: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Sync a specific ASIN with Shopify.
     *
     * @param string $asin
     * @return JsonResponse
     */
    public function sync(string $asin): JsonResponse
    {
        $amazonService = new AdvAmazonApiService(
            env('ADV_AMAZON_ACCESS_KEY'),
            env('ADV_AMAZON_SECRET_KEY'),
            env('ADV_AMAZON_PARTNER_TAG'),
            'webservices.amazon.it',
            'eu-west-1'
        );

        $shopifyService = new ShopifyService();

        try {
            $amazonResponse = $amazonService->getItems([$asin]);

            if ($amazonResponse['success']) {
                $productData = $amazonResponse['data'][0];

                // Usa le funzioni di DataShopifyFormatService per formattare i dati
                $title = $productData['Title'] ?? 'Prodotto Sconosciuto';
                $description = $shopifyService->generateDescription($productData);
                $metafields = $shopifyService->formatMetafieldsForShopify($productData);
                $tags = $productData['Tags'] ?? [];
                $images = $productData['Images'] ?? [];

                // Crea o aggiorna il prodotto su Shopify
                $shopifyResponse = $shopifyService->createOrUpdateProduct(
                    $title,
                    $images,
                    $description,
                    $tags,
                    $metafields,
                );

                return response()->json([
                    'success' => true,
                    'amazon_data' => $productData,
                    'shopify_response' => $shopifyResponse,
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => 'Errore nel recupero dei dati da Amazon',
                'amazon_response' => $amazonResponse,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Errore durante la sincronizzazione con Shopify: ' . $e->getMessage(),
            ], 500);
        }
    }
}
