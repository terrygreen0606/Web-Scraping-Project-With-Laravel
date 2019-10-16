<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    
    protected   $table = 'password_resets';
    public      $timestamps = false;

    protected $fillable = [
        'email',
        'token',
        'created_at'
    ];

}
