<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailVerifiy extends Model
{
    
    protected $table    = 'email_verifies';
    protected $fillable = [
        'email',
        'token'
    ];

    protected $hidden   = [
        'id',
    ];

}
