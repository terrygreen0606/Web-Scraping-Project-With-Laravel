<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('avatar')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('family');
            $table->string('name');
            $table->string('password');
            $table->unsignedBigInteger('rule_id')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->string('uuid');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('rule_id')->references('id')->on('rules')->onDelete('set null');
            $table->foreign('status_id')->references('id')->on('user_statuses')->onDelete('set null');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['rule_id']);
            $table->dropForeign(['status_id']);
        });

        Schema::dropIfExists('users');
    }
}
