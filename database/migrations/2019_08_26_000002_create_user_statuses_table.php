<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Hamidjavadi\guid;

class CreateUserStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('user_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title');
            $table->string('uuid')->unique();
            $table->integer('login')->default(1);
        });

        DB::table('user_statuses')->insert([
            [
                'id'    => 1,
                'title' => 'Active',
                'uuid'  => guid::generate(),
                'login' => 1
            ],
            [
                'id'    => 2,
                'title' => 'Suspend',
                'uuid'  => guid::generate(),
                'login' => 0
            ],
            [
                'id'    => 3,
                'title' => 'Closed',
                'uuid'  => guid::generate(),
                'login' => 0
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
        Schema::dropIfExists('user_statuses');
    }
}
