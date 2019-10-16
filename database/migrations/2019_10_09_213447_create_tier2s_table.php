<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTier2sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tier2s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('client_id');
            $table->string('provider_id');
            $table->string('tier1_link_id');
            $table->string('anchor_text');
            $table->string('tier2_link');
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
        Schema::dropIfExists('tier2s');
    }
}
