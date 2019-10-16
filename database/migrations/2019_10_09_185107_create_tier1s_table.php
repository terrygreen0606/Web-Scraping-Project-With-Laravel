<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTier1sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tier1s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('client_id');
            $table->string('provider_id');
            $table->string('tier1_link');
            $table->string('emUrl');
            $table->string('anchor_text');
            $table->string('target_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tier1s');
    }
}
