<?php

namespace App\Models;

class EanList extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'product_id',
        'ean_code',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
        'ean_code' => 'string',
    ];

    /**
     * Get the product associated with the EAN list.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
