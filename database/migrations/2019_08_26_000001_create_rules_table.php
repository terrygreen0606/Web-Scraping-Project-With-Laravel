<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Hamidjavadi\guid;

class CreateRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        /**
         * 
         * Add fields to rules table
         * 
         */
        Schema::create('rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->unique();
            $table->string('uuid');
            $table->integer('admin')->unsigned();
        });


        /**
         * 
         * Add default records 
         * 
         */
        DB::table('rules')->insert([
            [
                'admin'     => 0,
                'id'        => 1,
                'title'     => 'Client',
                'uuid'      =>  guid::generate()
            ],
            [
                'admin'     => 1,
                'id'        => 2,
                'title'     => 'Administrator',
                'uuid'      =>  guid::generate()
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
        Schema::dropIfExists('rules');
    }
}
