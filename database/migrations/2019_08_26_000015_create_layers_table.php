<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('layers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('angle')->default(0.0);
            $table->string('background_color')->nullable();
            $table->integer('bold')->default(0);
            $table->string('color')->nullable();
            $table->text('description')->nullable();
            $table->text('file')->nullable();
            $table->unsignedBigInteger('font_id')->nullable();
            $table->integer('font_size')->nullable();
            $table->integer('height')->nullable();
            $table->integer('italic')->default(0);
            $table->integer('left')->default(0);
            $table->float('opacity')->default(1.0);
            $table->integer('order')->default(0);
            $table->unsignedBigInteger('template_id');
            $table->string('text_v_align')->nullable();
            $table->string('text_h_align')->nullable();
            $table->text('title');
            $table->integer('top')->default(0);
            $table->unsignedBigInteger('layer_type_id')->nullable();
            $table->integer('underline')->default(0);
            $table->string('uuid');
            $table->integer('width')->nullable();
            $table->timestamps();
        });

        Schema::table('layers', function (Blueprint $table) {
            $table->foreign('font_id')->references('id')->on('fonts')->onDelete('set null');
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
            $table->foreign('layer_type_id')->references('id')->on('layer_types')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('layers', function (Blueprint $table) {

            $table->dropForeign(['font_id']);
            $table->dropForeign(['template_id']);
            $table->dropForeign(['layer_type_id']);

        });

        Schema::dropIfExists('layers');
    }
}
