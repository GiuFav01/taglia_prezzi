<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiTagTable extends Migration
{
    public function up()
    {
        Schema::create('api_tag', function (Blueprint $table) {
            $table->uuid('id_api'); // Foreign key for `apis`
            $table->uuid('id_tag'); // Foreign key for `tags`

            $table->foreign('id_api')->references('id')->on('apis')->onDelete('cascade');
            $table->foreign('id_tag')->references('id')->on('tags')->onDelete('cascade');

            $table->primary(['id_api', 'id_tag']); // Composite primary key
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_tag');
    }
}
