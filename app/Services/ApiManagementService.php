<?php

namespace App\Services;

use App\Models\Api;
use App\Models\AsinsList;
use App\Utils\ResponseHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\ProductsManagment\ProductDataService;

class ApiManagementService
{
    /**
     * Execute the given API and process the results.
     *
     * @param Api $api
     * @return ResponseHandler
     */
    public function executeApi(Api $api)
    {
        try {
            $url = str_replace('YOUR_API_KEY', env('KEEPA_API_KEY', ''), $api->url);
            $url = str_replace('YOUR_DOMAIN', '8', $url);
            $response = $this->sendGetRequest($url);

            if ($response['error']) {
                Log::error("Errore durante l'esecuzione dell'API: {$response['error']}");
                return ResponseHandler::error('Errore durante l\'esecuzione dell\'API');
            }

            $api->update(['last_execution' => now()]);

            $asinList = $response['data']['asinList'] ?? [];
            if (empty($asinList)) {
                return ResponseHandler::error('Nessun ASIN trovato nella risposta dell\'API', null, 400);
            }

            return $this->insertAsinList($asinList, $api);

            // return $this->getProdutcData($asinList, $api);
        } catch (\Exception $e) {
            return ResponseHandler::error('Errore interno durante l\'esecuzione dell\'API', null, 500);
        }
    }

    /**
    * Insert the ASIN list into the database.
    *
    * @param array $asinList
    * @param Api $api
    * @return ResponseHandler
    */
    private function insertAsinList($asinList, $api)
    {
        try {
            $deletedCount = AsinsList::where('id_api', $api->id)->delete();

            if ($deletedCount === 0) {
                return ResponseHandler::error('Nessun dato da eliminare o errore nella cancellazione.', null, 400);
            }

            $errors = [];
            $successCount = 0;

            foreach ($asinList as $asin) {
                $result = AsinsList::createAsin($asin, $api->id);

                if (!$result['success']) {
                    $errors[] = $result['message'];
                } else {
                    $successCount++;
                }
            }

            if (!empty($errors)) {
                return ResponseHandler::error(
                    'Alcuni ASIN non sono stati inseriti correttamente.',
                    ['success_count' => $successCount, 'errors' => $errors],
                    500
                );
            }

            return ResponseHandler::success('ASIN inseriti con successo', ['success_count' => $successCount], true);
        } catch (\Exception $e) {
            return ResponseHandler::error(
                'Errore durante l\'inserimento degli ASIN',
                $e->getMessage(),
                500
            );
        }
    }

    /**
     * Get product data for the given ASIN list.
     *
     * @param array<string> $asinList
     * @param Api $api
     *
     * @return ResponseHandler
     */
    public function getProdutcData($asinList, $api){
        $productResponse = $this->fetchProductDetails($asinList);
        if (!$productResponse['success']) {
            return ResponseHandler::error('Errore durante il recupero dei dettagli del prodotto', null, 500);
        }

        $productDataService = new ProductDataService($productResponse['data'], $api->id);
        return $productDataService->elaborateData();
    }

    /**
     * Send a GET request to the specified URL.
     *
     * @param string $url
     * @return array
     */
    protected function sendGetRequest(string $url): array
    {
        try {
            $response = Http::timeout(30)->get($url);
            return ['data' => $response->json(), 'error' => null];
        } catch (\Exception $e) {
            Log::error("Errore durante la richiesta GET: {$e->getMessage()}");
            return ['data' => null, 'error' => $e->getMessage()];
        }
    }

    /**
     * Fetch product details for the given ASIN list.
     *
     * @param array $asinList
     * @return array
     */
    private function fetchProductDetails(array $asinList): array
    {
        try {
            $asins = implode(',', $asinList);
            $productUrl = "https://api.keepa.com/product?key=" . env('KEEPA_API_KEY', '') . "&domain=8&asin={$asins}";

            $response = $this->sendGetRequest($productUrl);

            if ($response['error']) {
                Log::error("Errore durante la chiamata ai dettagli del prodotto: {$response['error']}");
                return ['success' => false, 'data' => null];
            }

            return ['success' => true, 'data' => $response['data']];
        } catch (\Exception $e) {
            Log::error("Errore interno durante il recupero dei dettagli del prodotto: {$e->getMessage()}");
            return ['success' => false, 'data' => null];
        }
    }
}
