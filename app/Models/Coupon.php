<?php

namespace App\Models;

class Coupon extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'product_id',
        'coupon_details',
        'updated_by_offers',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
        'coupon_details' => 'array', // JSON field cast as array
        'updated_by_offers' => 'boolean',
    ];

    /**
     * Get the product associated with the coupon.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
