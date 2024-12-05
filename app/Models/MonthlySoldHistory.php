<?php

namespace App\Models;

class MonthlySoldHistory extends BaseModel
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
        'monthly_sold_count',
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
        'monthly_sold_count' => 'integer',
    ];

    /**
     * Get the product associated with the monthly sold history.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
