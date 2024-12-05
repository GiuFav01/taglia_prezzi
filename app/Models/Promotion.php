<?php

namespace App\Models;

class Promotion extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'product_id',
        'promotion_type',
        'discounted_price',
        'discount_percent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
        'discounted_price' => 'integer',
        'discount_percent' => 'integer',
    ];

    /**
     * Get the product associated with the promotion.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
