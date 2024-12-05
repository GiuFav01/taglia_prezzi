<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductMetricsTable extends Migration
{
    public function up()
    {
        Schema::create('product_metrics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_id');
            $table->timestamp('metric_timestamp');
            $table->integer('tokens_left')->nullable();
            $table->integer('refill_in')->nullable();
            $table->integer('refill_rate')->nullable();
            $table->float('token_flow_reduction')->nullable();
            $table->integer('tokens_consumed')->nullable();
            $table->integer('processing_time_ms')->nullable();
            $table->timestamps(); // Default Laravel timestamps

            // Foreign key constraint
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_metrics');
    }
}
