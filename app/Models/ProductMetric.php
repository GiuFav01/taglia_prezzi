<?php

namespace App\Models;

use Illuminate\Support\Str;

class ProductMetric extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'product_id',
        'metric_timestamp',
        'tokens_left',
        'refill_in',
        'refill_rate',
        'token_flow_reduction',
        'tokens_consumed',
        'processing_time_ms',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
        'metric_timestamp' => 'datetime',
        'tokens_left' => 'integer',
        'refill_in' => 'integer',
        'refill_rate' => 'integer',
        'token_flow_reduction' => 'float',
        'tokens_consumed' => 'integer',
        'processing_time_ms' => 'integer',
    ];

    /**
     * Get the product associated with the metric.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
