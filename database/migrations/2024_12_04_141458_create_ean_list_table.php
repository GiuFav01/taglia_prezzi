<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEanListTable extends Migration
{
    public function up()
    {
        Schema::create('ean_list', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key
            $table->uuid('product_id'); // Foreign key for `products`
            $table->string('ean_code'); // EAN code
            $table->timestamps(); // Default Laravel timestamps

            // Foreign key constraint
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ean_list');
    }
}
