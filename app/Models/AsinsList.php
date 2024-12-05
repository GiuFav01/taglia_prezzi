<?php

namespace App\Models;

use App\Utils\ResponseHandler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class AsinsList
 *
 * Represents the `asins_list` table in the database, which stores the ASINs associated with specific APIs.
 *
 * @package App\Models
 */
class AsinsList extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'asins_list';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'asin',
        'id_api',
    ];

    /**
     * Get the API associated with the ASIN.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function api(){
        return $this->belongsTo(Api::class, 'id_api');
    }

   /**
     * Create a new ASIN record associated with a specific API.
     *
     * @param string $asin The ASIN value to be stored.
     * @param string $apiId The ID of the associated API.
     * @return ResponseHandler
     */
    public static function createAsin(string $asin, string $apiId) {
        try {
            $asinRecord = self::create([
                'asin' => $asin,
                'id_api' => $apiId,
            ]);

            return ResponseHandler::success('ASIN creato con successo', $asinRecord, true);
        } catch (\Exception $e) {
            return ResponseHandler::error(
                "Errore durante l'inserimento dell'ASIN: {$asin}",
                $e->getMessage(),
                500,
                true
            );
        }
    }
}
