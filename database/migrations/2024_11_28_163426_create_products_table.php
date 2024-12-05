<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key
            $table->uuid('id_api'); // Foreign key for `apis`

            // Foreign key constraint
            $table->foreign('id_api')->references('id')->on('apis')->onDelete('cascade');

            // Additional fields
            $table->integer('product_type');
            $table->string('asin');
            $table->integer('domain_id');
            $table->string('title');
            $table->timestamp('tracking_since')->nullable();
            $table->integer('listed_since')->nullable();
            $table->timestamp('last_update')->nullable();
            $table->timestamp('last_rating_update')->nullable();
            $table->timestamp('last_price_change')->nullable();
            $table->boolean('new_price_is_map')->nullable();
            $table->timestamp('last_ebay_update')->nullable();
            $table->timestamp('last_stock_update')->nullable();
            $table->string('images_csv')->nullable();
            $table->bigInteger('root_category')->nullable();
            $table->string('brand')->nullable();
            $table->string('type')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('product_group')->nullable();
            $table->string('product_type_name')->nullable();
            $table->string('parent_asin')->nullable();
            $table->integer('availability_amazon')->nullable();
            $table->bigInteger('sales_rank_reference')->nullable();
            $table->timestamp('last_sold_update')->nullable();
            $table->integer('monthly_sold')->nullable();
            $table->float('referral_fee_percentage')->nullable();
            $table->float('referral_fee_percent')->nullable();

            $table->timestamps(); // Default timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
