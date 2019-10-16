<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateOhiBatchStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ohi_batch_states', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
        });

        
        // Begin add default records
        DB::table('ohi_batch_states')
                ->insert([
                    [
                        'id'    => 1,
                        'title' => 'Not Processed'
                    ],
                    [
                        'id'    => 2,
                        'title' => 'In Progress'
                    ],
                    [
                        'id'    => 3,
                        'title' => 'Completed'
                    ]
                ]);
        // End add default records

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ohi_batch_states');
    }
}
