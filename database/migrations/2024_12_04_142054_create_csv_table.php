<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCsvTable extends Migration
{
    public function up()
    {
        Schema::create('csv', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key
            $table->uuid('product_id'); // Foreign key for `products`
            $table->json('amazon_price_history')->nullable(); // JSON field for Amazon price history
            $table->json('marketplace_new_price_history')->nullable(); // JSON field for marketplace new price history
            $table->json('list_price_history')->nullable(); // JSON field for list price history
            $table->json('rating_history')->nullable(); // JSON field for rating history
            $table->json('rating_count_history')->nullable(); // JSON field for rating count history
            $table->timestamps(); // Default Laravel timestamps

            // Foreign key constraint
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('csv');
    }
}
