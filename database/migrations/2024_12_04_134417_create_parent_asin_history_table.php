<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParentAsinHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('parent_asin_history', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key
            $table->uuid('product_id'); // Foreign key for `products`
            $table->timestamp('history_timestamp');
            $table->string('parent_asin');
            $table->timestamps(); // Default Laravel timestamps

            // Foreign key constraint
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('parent_asin_history');
    }
}
