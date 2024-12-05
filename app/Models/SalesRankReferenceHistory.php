<?php

namespace App\Models;

class SalesRankReferenceHistory extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'product_id',
        'history_timestamp',
        'reference_category_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
        'history_timestamp' => 'datetime',
        'reference_category_id' => 'integer',
    ];

    /**
     * Get the product associated with the sales rank reference history.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
