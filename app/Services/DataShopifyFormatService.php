<?php

namespace App\Services;

/**
 * Service for formatting product data for Shopify.
 */
class DataShopifyFormatService
{
    /**
     * Format Amazon product data as Shopify metafields.
     *
     * @param array $productData Product data from Amazon API.
     * @return array Metafields formatted for Shopify.
     */
    public function formatMetafieldsForShopify(array $productData): array
    {
        $metafields = [];

        if (!empty($productData['Title'])) {
            $metafields[] = [
                "namespace" => "product_info",
                "key" => "title",
                "type" => "single_line_text_field",
                "value" => $productData['Title'],
            ];
        }

        if (!empty($productData['Price'])) {
            $metafields[] = [
                "namespace" => "product_info",
                "key" => "price",
                "type" => "single_line_text_field",
                "value" => $productData['Price'],
            ];
        }

        if (!empty($productData['Features'])) {
            $metafields[] = [
                "namespace" => "product_info",
                "key" => "features",
                "type" => "multi_line_text_field",
                "value" => implode("\n", $productData['Features']),
            ];
        }

        if (!empty($productData['Color'])) {
            $metafields[] = [
                "namespace" => "product_info",
                "key" => "color",
                "type" => "single_line_text_field",
                "value" => $productData['Color'],
            ];
        }

        if (!empty($productData['DetailPageURL'])) {
            $metafields[] = [
                "namespace" => "product_info",
                "key" => "amazon_url",
                "type" => "single_line_text_field",
                "value" => $productData['DetailPageURL'],
            ];
        }

        // Metafields necessari per far apparire il pulsante
        $metafields[] = [
            "namespace" => "global",
            "key" => "spreadr-tag",
            "type" => "single_line_text_field",
            "value" => "spreadr-affiliate", // Valore richiesto dal codice Liquid
        ];

        if (!empty($productData['Region'])) {
            $metafields[] = [
                "namespace" => "global",
                "key" => "spreadr-region",
                "type" => "single_line_text_field",
                "value" => "it", // Esempio: "it" per Italia
            ];
        }

        if (!empty($productData['DetailPageURL'])) {
            $metafields[] = [
                "namespace" => "global",
                "key" => "spreadr-url",
                "type" => "single_line_text_field",
                "value" => $productData['DetailPageURL'], // URL affiliato
            ];
        }


        return $metafields;
    }


    /**
     * Generate a description for the product based on Amazon data.
     *
     * @param array $productData Product data from Amazon API.
     * @return string The formatted description.
     */
    public function generateDescription(array $productData): string
    {
        $description = "";

        if (!empty($productData['Features'])) {
            $description .= "<h2>Caratteristiche principali:</h2><ul>";
            foreach ($productData['Features'] as $feature) {
                $description .= "<li>" . htmlspecialchars($feature) . "</li>";
            }
            $description .= "</ul>";
        }

        if (!empty($productData['DetailPageURL'])) {
            $description .= "<p><strong>Acquista su Amazon:</strong> <a href=\"" . htmlspecialchars($productData['DetailPageURL']) . "\">Vai alla pagina del prodotto</a></p>";
        }

        return $description;
    }
}
