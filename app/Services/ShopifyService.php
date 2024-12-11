<?php

namespace App\Services;

use Shopify\Context;
use Shopify\Clients\Graphql;
use Illuminate\Support\Facades\Log;
use Shopify\Auth\FileSessionStorage;

use function Pest\Laravel\json;

/**
 * Service to interact with Shopify API.
 */
class ShopifyService extends DataShopifyFormatService
{
    private Graphql $client;

    public function __construct()
    {
        $scopes = env('SHOPIFY_APP_SCOPES');
        if (empty($scopes)) {
            throw new \Exception("Missing Shopify API scopes. Please set SHOPIFY_APP_SCOPES in your .env file.");
        }

        Context::initialize(
            apiKey: env('SHOPIFY_API_KEY'),
            apiSecretKey: env('SHOPIFY_API_SECRET'),
            scopes: explode(',', $scopes),
            hostName: env('SHOPIFY_APP_HOST_NAME'),
            sessionStorage: new FileSessionStorage('/tmp/php_sessions'),
            apiVersion: '2024-10',
            isEmbeddedApp: true,
            isPrivateApp: false
        );

        $this->client = new Graphql(env('SHOPIFY_APP_HOST_NAME'), env('SHOPIFY_ACCESS_TOKEN'));
    }

    /**
     * Cerca un prodotto su Shopify in base al titolo.
     *
     * @param string $title
     * @return array|null
     */
    public function findProductByTitle(string $title): ?array
    {
        $query = <<<QUERY
        query findProduct(\$query: String!) {
            products(first: 1, query: \$query) {
                edges {
                    node {
                        id
                        title
                        variants(first: 1) {
                            edges {
                                node {
                                    id
                                    price
                                }
                            }
                        }
                    }
                }
            }
        }
        QUERY;

        $variables = ["query" => "title:'" . addslashes($title) . "'"];

        try {
            $response = $this->client->query(['query' => $query, 'variables' => $variables]);
            $products = $response->getDecodedBody()['data']['products']['edges'] ?? [];
            return count($products) > 0 ? $products[0]['node'] : null;
        } catch (\Exception $e) {
            Log::error("Errore durante la ricerca del prodotto: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete a product from Shopify.
     *
     * @param string $productId
     *
     * @return bool
     */
    public function deleteProduct($productId): bool{
        try{
            $query = <<<QUERY
            mutation {
                productDelete(input: {id: "$productId"}) {
                deletedProductId
                    userErrors {
                        field
                        message
                    }
                }
            }
            QUERY;

            $response = $this->client->query(['query' => $query]);
            Log::info($response->getDecodedBody());
            return isset($response->getDecodedBody()['data']['productDelete']['deletedProductId']);
        }
        catch(\Exception $e){
            Log::error("Errore durante la cancellazione del prodotto: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create or Update a product in Shopify.
     *
     * @param string $title
     * @param array<string,string> $images
     * @param string|null $description
     * @param float $price
     * @param float $savingBasis
     * @param string $brand
     * @param array $tags
     * @param array $metafields
     * @param array $media
     * @return array Shopify API response.
     */
    public function createOrUpdateProduct(
        string $title,
        float $price,
        float $savingBasis,
        string $brand,
        array $images,
        ?string $description = null,
        array $tags = [],
        array $metafields = [],
    ): array {
        $existingProduct = $this->findProductByTitle($title);

        if ($existingProduct) {
            $productId = $existingProduct['id'];
            $deleteProduct = $this->deleteProduct($productId);
            if(!$deleteProduct){
                return [
                    'success' => false,
                    'error' => 'Errore durante la cancellazione del prodotto esistente',
                ];
            }
        }

        $createProductResponse = $this->createProduct(
            $title,
            $images,
            $brand,
            $metafields,
            $description,
            $tags
        );

        if (isset($createProductResponse['data']['productCreate']['product']['id'])) {
            $productId = $createProductResponse['data']['productCreate']['product']['id'];
            $variantsId = $createProductResponse['data']['productCreate']['product']['variants']['nodes'][0]['id'];

            $addVariantResponse = $this->addProductVariant(
                $productId,
                $variantsId,
                $price,
                $savingBasis
            );

            return [
                'product' => $createProductResponse,
                'variant' => $addVariantResponse,
            ];
        }


        return $createProductResponse;
    }

    /**
     * Create a product in Shopify with metafields and media.
     *
     * @param string $title The title of the product.
     * @param array<string,string> $images Array of image URLs to associate with the product.
     * @param string $brand The brand of the product.
     * @param array $metafields Array of metafields to add to the product.
     * @param array $media Array of media objects (e.g., images) to associate with the product.
     *
     * @return array Shopify API response.
     */
    public function createProduct(
        string $title,
        array $images,
        string $brand,
        array $metafields = [],
        ?string $description = null,
        array $tags = []
    ): array {
        $query = <<<QUERY
        mutation CreateProductWithNewMedia(\$input: ProductInput!, \$media: [CreateMediaInput!]) {
            productCreate(input: \$input, media: \$media) {
                product {
                    id
                    title
                    descriptionHtml
                    tags
                    variants(first: 1) {
                        nodes {
                            id
                        }
                    }
                    media(first: 10) {
                        nodes {
                            alt
                            mediaContentType
                            preview {
                                status
                            }
                        }
                    }

                }
                userErrors {
                    message
                    field
                }
            }
        }
        QUERY;

        $mediaImages = [];
        foreach($images as $image) {
            $mediaImages[] = [
                "mediaContentType" => "IMAGE",
                "originalSource" => $image,
                "alt" => "Product image "
            ];
        }

        $variables = [
            "input" => [
                "title" => $title,
                "descriptionHtml" => $description,
                "metafields" => $metafields,
                "tags" => implode(',', $tags),
                "templateSuffix" => "prodotto-amazon",
                "vendor" => $brand,
            ],
            "media" => $mediaImages
        ];

        try {
            $response = $this->client->query(["query" => $query, "variables" => $variables]);
            $decodedResponse = $response->getDecodedBody();

            return $decodedResponse;
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Errore durante la creazione del prodotto su Shopify: ' . $e->getMessage(),
            ];
        }
    }

    public function addProductVariant(
        string $productId,
        string $variantsId,
        float $price,
        ?float $compareAtPrice = null
    ): array {
        $query = <<<QUERY
        mutation productVariantsBulkUpdate(\$productId: ID!, \$variants: [ProductVariantsBulkInput!]!) {
            productVariantsBulkUpdate(productId: \$productId, variants: \$variants) {
                 product {
                    id
                }
                productVariants {
                    id
                    metafields(first: 2) {
                        edges {
                            node {
                                namespace
                                key
                                value
                            }
                        }
                    }
                }
                userErrors {
                    field
                    message
                }
            }
        }
        QUERY;

        // Definire la variante con le opzioni
        $variants = [
            [
                "id" => $variantsId,
                "price" => number_format($price, 2, '.', ''),
            ]
        ];

        if ($compareAtPrice !== null) {
            $variants[0]["compareAtPrice"] = number_format($compareAtPrice, 2, '.', '');
        }

        $variables = [
            "productId" => $productId,
            "variants" => $variants
        ];

        try {
            $response = $this->client->query(["query" => $query, "variables" => $variables]);
            $decodedResponse = $response->getDecodedBody();

            return $decodedResponse;
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Errore durante la creazione della variante del prodotto su Shopify: ' . $e->getMessage(),
            ];
        }
    }
}
