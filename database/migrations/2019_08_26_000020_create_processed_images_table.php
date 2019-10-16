<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessedImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('processed_images', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('image_id');
            $table->text('file');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('template_id');
            $table->string('uuid');
            $table->timestamps();

        });

        Schema::table('processed_images', function (Blueprint $table) {

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('processed_images', function (Blueprint $table) {

            $table->dropForeign(['category_id']);
            $table->dropForeign(['image_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['template_id']);

        });

        Schema::dropIfExists('processed_images');

    }
}
