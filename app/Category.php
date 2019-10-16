<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    
    protected   $table      = 'categories';
    protected   $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'title',
        'uuid'
    ];

    protected $hidden = [
        'id',
        'user_id'
    ];

    public function Images() {
        return $this->hasMany('App\Image', 'category', 'id');
    }

    public function User() {
        return $this->belongsTo('App\User', 'user_id');
    }

}
