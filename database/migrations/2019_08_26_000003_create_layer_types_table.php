<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateLayerTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('layer_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
        });

        DB::table('layer_types')->insert([
            [
                'title' => 'Image'
            ],
            [
                'title' => 'Text'
            ],
            [
                'title' => 'Shape'
            ]
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('layer_types');
    }
    
}
