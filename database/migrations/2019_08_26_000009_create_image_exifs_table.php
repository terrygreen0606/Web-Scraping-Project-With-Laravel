<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImageExifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('image_exifs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('exif_id');
            $table->unsignedBigInteger('image_id');
            $table->timestamps();
        });

        Schema::table('image_exifs', function (Blueprint $table) {
            $table->foreign('exif_id')->references('id')->on('exifs')->onDelete('cascade');
            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('image_exifs', function (Blueprint $table) {
            $table->dropForeign(['exif_id']);
            $table->dropForeign(['image_id']);
        });

        Schema::dropIfExists('image_exifs');

    }
}
