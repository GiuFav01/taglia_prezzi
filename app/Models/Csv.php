<?php

namespace App\Models;

class Csv extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'product_id',
        'amazon_price_history',
        'marketplace_new_price_history',
        'list_price_history',
        'rating_history',
        'rating_count_history',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
        'amazon_price_history' => 'array', // JSON field cast as array
        'marketplace_new_price_history' => 'array', // JSON field cast as array
        'list_price_history' => 'array', // JSON field cast as array
        'rating_history' => 'array', // JSON field cast as array
        'rating_count_history' => 'array', // JSON field cast as array
    ];

    /**
     * Get the product associated with the CSV record.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
