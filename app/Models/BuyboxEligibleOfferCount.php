<?php

namespace App\Models;

class BuyboxEligibleOfferCount extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'product_id',
        'new_fba_offers',
        'new_fbm_offers',
        'used_fba_offers',
        'used_fbm_offers',
        'collectible_fba_offers',
        'collectible_fbm_offers',
        'refurbished_fba_offers',
        'refurbished_fbm_offers',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
        'new_fba_offers' => 'integer',
        'new_fbm_offers' => 'integer',
        'used_fba_offers' => 'integer',
        'used_fbm_offers' => 'integer',
        'collectible_fba_offers' => 'integer',
        'collectible_fbm_offers' => 'integer',
        'refurbished_fba_offers' => 'integer',
        'refurbished_fbm_offers' => 'integer',
    ];

    /**
     * Get the product associated with the buybox eligible offer counts.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
