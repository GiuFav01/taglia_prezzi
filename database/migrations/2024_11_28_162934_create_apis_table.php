<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApisTable extends Migration
{
    public function up()
    {
        Schema::create('apis', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary(); // Unique ID
            $table->text('url');
            $table->string('description');
            $table->dateTime('last_execution')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('apis');
    }
}
