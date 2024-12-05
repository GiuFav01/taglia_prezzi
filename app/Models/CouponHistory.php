<?php

namespace App\Models;

class CouponHistory extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'product_id',
        'keepa_time',
        'one_time_coupon',
        'subscribe_and_save_coupon',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
        'keepa_time' => 'datetime',
        'one_time_coupon' => 'integer',
        'subscribe_and_save_coupon' => 'integer',
    ];

    /**
     * Get the product associated with the coupon history.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
