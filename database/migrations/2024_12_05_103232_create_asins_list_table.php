<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsinsListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asins_list', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key
            $table->string('asin')->notNullable(); // ASIN string
            $table->uuid('id_api'); // Foreign key for `apis`
            $table->timestamps(); // Standard timestamps

            // Foreign key constraint
            $table->foreign('id_api')->references('id')->on('apis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asins_list');
    }
}
