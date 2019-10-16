<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{

    protected $table        = 'images';
    protected $primaryKey   = 'id';

    protected $fillable = [
        'category_id',      // category id
        'description',
        'file',             // image address on storage
        'title',
        'thumbnail',
        'uuid',
        'user_id',          // user id
    ];

    protected $hidden = [
        'id',
        'user_id'
    ];

    public function User() {
        return $this->belongsTo('App\User', 'user');
    }

    public function Category() {
        return $this->belongsTo('App\Category', 'category_id');
    }

    // TODO: Fetch Image Exifs
    public function Exifs() {
        return $this->hasMany('App\ImageExif', 'image', 'id');
    }

}