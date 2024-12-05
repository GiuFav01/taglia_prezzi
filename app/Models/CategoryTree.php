<?php

namespace App\Models;

class CategoryTree extends BaseModel
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
        'category_name',
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
        'category_name' => 'string',
    ];

    /**
     * Get the product associated with the category tree.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
