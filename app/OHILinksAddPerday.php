<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OHILinksAddPerday extends Model
{
    
    protected   $table      = 'ohi_links_added_per_day';
    protected   $primaryKey = 'id';

    protected $fillable = [
        'date',
        'total',
    ];

}