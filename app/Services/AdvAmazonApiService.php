<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Utils\ResponseHandler;
use Illuminate\Support\Facades\Log;
use Amazon\ProductAdvertisingAPI\v1\ApiException;
use Amazon\ProductAdvertisingAPI\v1\Configuration;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\PartnerType;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\api\DefaultApi;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\GetItemsRequest;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\GetItemsResource;

class AdvAmazonApiService
{
    private string $accessKey;
    private string $secretKey;
    private string $partnerTag;
    private string $host;
    private string $region;

    /**
     * Constructor to initialize the Amazon API service.
     *
     * @param string $accessKey AWS access key.
     * @param string $secretKey AWS secret key.
     * @param string $partnerTag Partner tag associated with the API.
     * @param string $host Host URL for the API.
     * @param string $region Region for the API.
     */
    public function __construct(string $accessKey, string $secretKey, string $partnerTag, string $host, string $region)
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
        $this->partnerTag = $partnerTag;
        $this->host = $host;
        $this->region = $region;
    }

    /**
     * Fetch detailed information about a list of items.
     *
     * @param array $itemIds List of item IDs (ASINs) to fetch data for.
     * @param int $exceed Number of retry attempts made in case of throttling.
     * @return array Response data containing item details or errors.
     */
    public function getItems(array $itemIds, int $exceed = 0): array
    {
        $config = new Configuration();
        $config->setAccessKey($this->accessKey);
        $config->setSecretKey($this->secretKey);
        $config->setHost($this->host);
        $config->setRegion($this->region);

        $apiInstance = new DefaultApi(new Client(), $config);

        $resources = $this->getAllResources();

        $getItemsRequest = new GetItemsRequest();
        $getItemsRequest->setItemIds($itemIds);
        $getItemsRequest->setPartnerTag($this->partnerTag);
        $getItemsRequest->setPartnerType(PartnerType::ASSOCIATES);
        $getItemsRequest->setResources($resources);

        $invalidProperties = $getItemsRequest->listInvalidProperties();
        if (count($invalidProperties) > 0) {
            return ResponseHandler::error('Request contains invalid properties', $invalidProperties, 400);
        }

        try {
            $getItemsResponse = $apiInstance->getItems($getItemsRequest);

            if ($getItemsResponse->getItemsResult()?->getItems() !== null) {
                $responseList = $this->parseResponse($getItemsResponse->getItemsResult()->getItems());
                return ResponseHandler::success('Items retrieved successfully', $responseList);
            }

            if ($getItemsResponse->getErrors() !== null) {
                $errors = $getItemsResponse->getErrors();
                return ResponseHandler::error('API returned errors', $errors, 500);
            }

            return ResponseHandler::info('No items found in the response');
        } catch (ApiException $exception) {
            if ($exceed >= 3) {
                return ResponseHandler::error('API Exception occurred', $exception->getMessage(), $exception->getCode());
            }

            return ResponseHandler::error(
                'API Exception occurred',
                [
                    'http_status' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                    'response' => $exception->getResponseBody(),
                ],
                $exception->getCode()
            );
        } catch (\Exception $exception) {
            return ResponseHandler::error('Unexpected error occurred', $exception->getMessage(), 500);
        }
    }

    private function getAllResources(): array
    {
        return [
            GetItemsResource::ITEM_INFOTITLE,
            GetItemsResource::ITEM_INFOFEATURES,
            GetItemsResource::ITEM_INFOCONTENT_INFO,
            GetItemsResource::ITEM_INFOMANUFACTURE_INFO,
            GetItemsResource::ITEM_INFOTECHNICAL_INFO,
            GetItemsResource::ITEM_INFOBY_LINE_INFO,
            GetItemsResource::ITEM_INFOPRODUCT_INFO,
            GetItemsResource::ITEM_INFOCONTENT_RATING,
            GetItemsResource::ITEM_INFOTRADE_IN_INFO,
            GetItemsResource::ITEM_INFOEXTERNAL_IDS,
            GetItemsResource::IMAGESPRIMARYSMALL,
            GetItemsResource::IMAGESPRIMARYMEDIUM,
            GetItemsResource::IMAGESPRIMARYLARGE,
            GetItemsResource::IMAGESVARIANTSSMALL,
            GetItemsResource::IMAGESVARIANTSMEDIUM,
            GetItemsResource::IMAGESVARIANTSLARGE,
            GetItemsResource::CUSTOMER_REVIEWSCOUNT,
            GetItemsResource::CUSTOMER_REVIEWSSTAR_RATING,
            GetItemsResource::BROWSE_NODE_INFOBROWSE_NODES,
            GetItemsResource::BROWSE_NODE_INFOBROWSE_NODESANCESTOR,
            GetItemsResource::BROWSE_NODE_INFOWEBSITE_SALES_RANK,
            GetItemsResource::OFFERSLISTINGSPRICE,
            GetItemsResource::OFFERSLISTINGSSAVING_BASIS,
            GetItemsResource::OFFERSLISTINGSAVAILABILITYMESSAGE,
            GetItemsResource::OFFERSLISTINGSIS_BUY_BOX_WINNER,
            GetItemsResource::OFFERSLISTINGSDELIVERY_INFOIS_PRIME_ELIGIBLE,
            GetItemsResource::OFFERSLISTINGSDELIVERY_INFOSHIPPING_CHARGES,
            GetItemsResource::OFFERSLISTINGSCONDITION,
            GetItemsResource::OFFERSSUMMARIESHIGHEST_PRICE,
            GetItemsResource::OFFERSSUMMARIESLOWEST_PRICE,
            GetItemsResource::OFFERSSUMMARIESOFFER_COUNT,
            GetItemsResource::PARENT_ASIN,
        ];
    }

    /**
     * Parse the API response to extract item details.
     *
     * @param array $items List of items from the API response.
     * @return array Parsed response data.
     */
    private function parseResponse(array $items): array
    {
        $responseList = [];

        foreach ($items as $item) {
            // Variabili temporanee per evitare ripetizioni
            $itemInfo = $item->getItemInfo();
            $byLineInfo = $itemInfo?->getByLineInfo();
            $productInfo = $itemInfo?->getProductInfo();
            $itemDimensions = $productInfo?->getItemDimensions();
            $offers = $item->getOffers();
            $customerReviews = $item->getCustomerReviews();
            $images = [];

            $images[] = $item->getImages()?->getPrimary()?->getLarge()?->getURL();
            $variants = $item->getImages()?->getVariants() ?? [];
            foreach ($variants  as $variant) {
                $images[] = $variant->getLarge()->getURL();
            }


            $responseList[] = [
                'ASIN' => $item->getASIN(),
                'Title' => $itemInfo?->getTitle()?->getDisplayValue(),
                'Brand' => $byLineInfo?->getBrand()?->getDisplayValue(),
                'Manufacturer' => $byLineInfo?->getManufacturer()?->getDisplayValue(),
                'Features' => $itemInfo?->getFeatures()?->getDisplayValues() ?? [],
                'Color' => $productInfo?->getColor()?->getDisplayValue(),
                'Dimensions' => [
                    'Height' => $itemDimensions?->getHeight()?->getDisplayValue(),
                    'Length' => $itemDimensions?->getLength()?->getDisplayValue(),
                    'Width' => $itemDimensions?->getWidth()?->getDisplayValue(),
                    'Weight' => $itemDimensions?->getWeight()?->getDisplayValue(),
                ],
                'Price' => $offers?->getListings()[0]?->getPrice()?->getDisplayAmount(),
                'SavingBasis' => $offers?->getListings()[0]?->getSavingBasis()?->getDisplayAmount(),
                'CustomerReviews' => [
                    'Count' => $customerReviews?->getCount(),
                    'StarRating' => $customerReviews?->getStarRating(),
                ],
                'Tags' => $this->extractTags($item),
                'DetailPageURL' => $item->getDetailPageURL(),
                'Images' => $images,
            ];
        }
        return $responseList;
    }

    /**
     * Extract tags from the item.
     *
     * @param object $item The item object from the API response.
     * @return array List of tags.
     */
    private function extractTags($item): array
    {
        $tags = [];
        $browseNodeInfo = $item->getBrowseNodeInfo()?->getBrowseNodes();

        if ($browseNodeInfo) {
            foreach ($browseNodeInfo as $node) {
                $tags[] = $node->getDisplayName();
            }
        }

        return $tags;
    }
}
