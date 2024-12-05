<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyboxEligibleOfferCountsTable extends Migration
{
    public function up()
    {
        Schema::create('buybox_eligible_offer_counts', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key
            $table->uuid('product_id'); // Foreign key for `products`
            $table->integer('new_fba_offers')->default(0);
            $table->integer('new_fbm_offers')->default(0);
            $table->integer('used_fba_offers')->default(0);
            $table->integer('used_fbm_offers')->default(0);
            $table->integer('collectible_fba_offers')->default(0);
            $table->integer('collectible_fbm_offers')->default(0);
            $table->integer('refurbished_fba_offers')->default(0);
            $table->integer('refurbished_fbm_offers')->default(0);
            $table->timestamps(); // Default Laravel timestamps

            // Foreign key constraint
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('buybox_eligible_offer_counts');
    }
}
