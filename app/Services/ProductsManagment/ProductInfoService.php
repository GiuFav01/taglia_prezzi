<?php

namespace App\Services\ProductsManagment;

use App\Models\Product;
use App\Utils\ResponseHandler;
use App\Utils\Utilities;
use Illuminate\Support\Facades\Log;

/**
 * Class ProductInfoService
 * Handles mapping and saving product information to the database.
 */
class ProductInfoService
{
    /**
     * Inserts product information into the `products` table.
     *
     * @param array $productData Formatted product data for insertion.
     * @param string $apiId Associated API ID.
     *
     * @return array
     */
    public function saveProduct(array $productData, string $apiId): array
    {
        try {
            $formattedData = $this->mapApiDataToDatabase($productData, $apiId);
            $product = Product::create($formattedData);

            return ResponseHandler::success('Prodotto inserito con successo', ["id" => $product->id]);
        } catch (\Exception $e) {
            return ResponseHandler::error('Errore durante l\'inserimento del prodotto', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }

    /**
     * Maps API data to the database format for the `products` table.
     *
     * @param array $apiData Single product data from the API.
     * @param string $apiId Associated API ID.
     * @return array Mapped data for database insertion.
     */
    public function mapApiDataToDatabase(array $apiData, string $apiId): array
    {
        return [
            'id_api' => $apiId,
            'product_type' => $apiData['productType'] ?? null,
            'asin' => $apiData['asin'] ?? null,
            'domain_id' => $apiData['domainId'] ?? null,
            'title' => $apiData['title'] ?? null,
            'tracking_since' => isset($apiData['trackingSince']) ? Utilities::convertKeepaTime($apiData['trackingSince'], true) : null,
            'listed_since' => $apiData['listedSince'],
            'last_update' => isset($apiData['lastUpdate']) ? Utilities::convertKeepaTime($apiData['lastUpdate'], true) : null,
            'last_rating_update' => isset($apiData['lastRatingUpdate']) ? Utilities::convertKeepaTime($apiData['lastRatingUpdate'], true) : null,
            'last_price_change' => isset($apiData['lastPriceChange']) ? Utilities::convertKeepaTime($apiData['lastPriceChange'], true) : null,
            'new_price_is_map' => $apiData['newPriceIsMap'] ?? false,
            'last_ebay_update' => isset($apiData['lastEbayUpdate']) ? Utilities::convertKeepaTime($apiData['lastEbayUpdate'], true) : null,
            'last_stock_update' => isset($apiData['lastStockUpdate']) ? Utilities::convertKeepaTime($apiData['lastStockUpdate'], true) : null,
            'images_csv' => $apiData['imagesCsv'] ?? '',
            'root_category' => $apiData['rootCategory'] ?? null,
            'brand' => $apiData['brand'] ?? '',
            'type' => $apiData['type'] ?? '',
            'manufacturer' => $apiData['manufacturer'] ?? '',
            'product_group' => $apiData['productGroup'] ?? '',
            'product_type_name' => $apiData['productTypeName'] ?? '',
            'parent_asin' => $apiData['parentAsin'] ?? '',
            'availability_amazon' => $apiData['availabilityAmazon'] ?? 0,
            'sales_rank_reference' => $apiData['salesRankReference'] ?? null,
            'last_sold_update' => isset($apiData['lastSoldUpdate']) ? Utilities::convertKeepaTime($apiData['lastSoldUpdate'], true) : null,
            'monthly_sold' => $apiData['monthlySold'] ?? 0,
            'referral_fee_percentage' => $apiData['referralFeePercentage'] ?? 0.0,
            'referral_fee_percent' => $apiData['referralFeePercent'] ?? 0.0,
        ];
    }
}
