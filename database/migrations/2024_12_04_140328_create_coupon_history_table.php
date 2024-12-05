<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('coupon_history', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key
            $table->uuid('product_id'); // Foreign key for `products`
            $table->timestamp('keepa_time'); // Timestamp of the history record
            $table->integer('one_time_coupon')->default(0); // One-time coupon value
            $table->integer('subscribe_and_save_coupon')->default(0); // Subscribe and save coupon value
            $table->timestamps(); // Default Laravel timestamps

            // Foreign key constraint
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupon_history');
    }
}
