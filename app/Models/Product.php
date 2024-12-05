<?php

namespace App\Models;

class Product extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'id_api',
        'product_type',
        'asin',
        'domain_id',
        'title',
        'tracking_since',
        'listed_since',
        'last_update',
        'last_rating_update',
        'last_price_change',
        'new_price_is_map',
        'last_ebay_update',
        'last_stock_update',
        'images_csv',
        'root_category',
        'brand',
        'type',
        'manufacturer',
        'product_group',
        'product_type_name',
        'parent_asin',
        'availability_amazon',
        'sales_rank_reference',
        'last_sold_update',
        'monthly_sold',
        'referral_fee_percentage',
        'referral_fee_percent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'id_api' => 'string',
        'tracking_since' => 'datetime',
        'last_update' => 'datetime',
        'last_rating_update' => 'datetime',
        'last_price_change' => 'datetime',
        'last_ebay_update' => 'datetime',
        'last_stock_update' => 'datetime',
        'last_sold_update' => 'datetime',
        'new_price_is_map' => 'boolean',
        'monthly_sold' => 'integer',
        'referral_fee_percentage' => 'float',
        'referral_fee_percent' => 'float',
    ];

    /**
     * Get the API this product belongs to.
     */
    public function api()
    {
        return $this->belongsTo(Api::class, 'id_api');
    }

    /**
     * Define relationships for other tables like promotions, metrics, etc.
     */

    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'product_id');
    }

    public function metrics()
    {
        return $this->hasMany(ProductMetric::class, 'product_id');
    }

    public function salesRanks()
    {
        return $this->hasMany(SalesRank::class, 'product_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'api_tag_relation', 'api_id', 'tag_id');
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class, 'product_id');
    }

    public function salesRankReferenceHistories()
    {
        return $this->hasMany(SalesRankReferenceHistory::class, 'product_id');
    }

    public function monthlySoldHistories()
    {
        return $this->hasMany(MonthlySoldHistory::class, 'product_id');
    }

    public function buyboxEligibleOfferCounts()
    {
        return $this->hasMany(BuyboxEligibleOfferCount::class, 'product_id');
    }

    public function parentAsinHistories()
    {
        return $this->hasMany(ParentAsinHistory::class, 'product_id');
    }

    public function couponHistories()
    {
        return $this->hasMany(CouponHistory::class, 'product_id');
    }

    public function categoryTrees()
    {
        return $this->hasMany(CategoryTree::class, 'product_id');
    }

    public function eanLists()
    {
        return $this->hasMany(EanList::class, 'product_id');
    }

    public function csvRecords()
    {
        return $this->hasOne(Csv::class, 'product_id');
    }
}
