<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    
    protected $table        = 'templates';
    protected $primaryKey   = 'id';

    protected $fillable = [
        'description',
        'id',
        'title',
        'user_id',      // User id
        'uuid'
    ];

    public function Layers() {
        return $this->hasMany('App\Layer', 'template_id', 'id');
    }

    public function User() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function ProcessedImages() {
        return $this->hasMany('App\ProcessedImage', 'template_id', 'id');
    }

}