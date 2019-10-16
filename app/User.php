<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table        = 'users';
    protected $primaryKey   = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'avatar',
        'email',
        'family',
        'left_sidebar_status',
        'name',
        'password',
        'rule_id',         // Rule id
        'status_id',       // UserStatus id
        'uuid',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'password',
        'remember_token',
        'rule_id',
        'status_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function Status() {
        return $this->belongsTo('App\UserStatus', 'status_id');
    }

    public function Rule() {
        return $this->belongsTo('App\Rule', 'rule_id');
    }

    public function Categories() {
        return $this->hasMany('App\Category', 'user_id', 'id');
    }

}