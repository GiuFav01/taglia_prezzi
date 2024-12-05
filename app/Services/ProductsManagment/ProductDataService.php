<?php

namespace App\Services\ProductsManagment;

use Illuminate\Support\Facades\Log;
use App\Services\ProductsManagment\ProductInfoService;
use App\Utils\ResponseHandler;

/**
 * Class ProductDataService
 * Service for handling product data from API responses and distributing it to other services.
 */
class ProductDataService
{
    /**
     * JSON data from the API.
     *
     * @var array
     */
    private $jsonData;

    /**
     * API ID associated with the products.
     *
     * @var string
     */
    private $apiId;

    /**
     * ProductDataService constructor.
     *
     * @param array $jsonData JSON data from the API.
     * @param string $apiId API ID for the products.
     */
    public function __construct(array $jsonData, string $apiId)
    {
        $this->jsonData = $jsonData;
        $this->apiId = $apiId;
    }

    /**
     * Processes the product data and distributes it to the appropriate services.
     *
     * @return ResponseHandler
     */
    public function elaborateData()
    {
        try {
            if (!isset($this->jsonData['products']) || !is_array($this->jsonData['products'])) {
                return ResponseHandler::error("Invalid or missing 'products' key in JSON data.", [], 400);
            }

            $productInfoService = new ProductInfoService();
            $errors = [];
            foreach ($this->jsonData['products'] as $product) {
                $product = $productInfoService->saveProduct($product, $this->apiId);
                if (!$product['success']) {
                    $errors[] = $product['data'] ?? [];
                }
                $productId = $product['data']['id'] ?? null;
                ResponseHandler::info("Product saved with ID: {$productId}");
            }

            if(!empty($errors)) {
                return ResponseHandler::error('Errore durante l\'elaborazione dei dati del prodotto', ['errors' => $errors], 500);
            }

            return ResponseHandler::success('Elaborazione completata con successo');
        } catch (\Exception $e) {
            return ResponseHandler::error("Errore durante l'elaborazione dei dati del prodotto", [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }
}
