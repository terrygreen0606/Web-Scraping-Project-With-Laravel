<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model
{
    
    protected   $table = 'user_statuses';
    protected   $primaryKey = 'id';
    public      $timestamps = false;

    protected $fillable = [
        'id',
        'title',
        'uuid',
        'login',     // Specify user able to login to his panel
    ];

    public function Users() {
        return $this->hasMany('App\User', 'status_id', 'id');
    }

}
