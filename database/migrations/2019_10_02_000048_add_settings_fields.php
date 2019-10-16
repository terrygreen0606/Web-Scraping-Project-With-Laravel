<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSettingsFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('settings', function (Blueprint $table) {
            $table->string('cron_period_time')->nullable();
            $table->text('dropbox_access_token')->nullable();
            $table->string('ohi_app_key')->nullable();
            $table->string('ohi_api_key')->nullable();
            $table->string('ohi_max_links')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                                    'cron_period_time',
                                    'dropbox_access_token',
                                    'ohi_app_key', 
                                    'ohi_api_key', 
                                    'ohi_max_links',
                                ]);
        });

    }
}
