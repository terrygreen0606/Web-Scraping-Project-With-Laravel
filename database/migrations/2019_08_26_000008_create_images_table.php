<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->text('description')->nullable();
            $table->text('title')->nullable();
            $table->text('file');
            $table->text('thumbnail');
            $table->string('uuid');
            $table->unsignedBigInteger('user_id');

            $table->timestamps();
        });

        Schema::table('images', function (Blueprint $table) {

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('images', function (Blueprint $table) {

            $table->dropForeign(['category_id']);
            $table->dropForeign(['user_id']);

        });

        Schema::dropIfExists('images');
    }
}
