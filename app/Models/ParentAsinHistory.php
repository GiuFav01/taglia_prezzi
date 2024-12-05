<?php

namespace App\Models;

class ParentAsinHistory extends BaseModel
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
        'parent_asin',
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
        'parent_asin' => 'string',
    ];

    /**
     * Get the product associated with the parent ASIN history.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
