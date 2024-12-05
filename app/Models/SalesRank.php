<?php

namespace App\Models;

class SalesRank extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'product_id',
        'category_id',
        'rank_timestamp',
        'sales_rank',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
        'category_id' => 'integer',
        'rank_timestamp' => 'datetime',
        'sales_rank' => 'integer',
    ];

    /**
     * Get the product associated with the sales rank.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
