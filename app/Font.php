<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Font extends Model
{
    
    protected $table        = 'fonts';
    protected $primaryKey   = 'id';

    protected $fillable = [
        'enabled',
        'file',
        'title',
        'uuid',
    ];

}
