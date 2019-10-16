<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    
    protected   $table = 'settings';
    protected   $primaryKey = 'id';
    public      $timestamps = false;

    protected $fillable = [
        'cron_period_time',
        'dropbox_access_token',
        'ohi_max_links',
        'ohi_api_key',
        'ohi_app_key',
        'ohi_links_per_day',
    ];

    protected $hidden = [
        'id'
    ];

}
