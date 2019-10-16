<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcessedImage extends Model
{
    
    protected   $table      = 'processed_images';
    protected   $primaryKey = 'id';

    protected $fillable = [
        'category_id',      // Category id
        'image_id',         // Image id
        'file',             // Image address on the storage
        'user_id',          // User id
        'template_id',      // Template id
        'uuid',
    ];

    public function Category() {
        return $this->belongsTo('App\Category', 'category_id');
    }

    public function User() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function Image() {
        return $this->belongsTo('App\Image', 'image_id');
    }

    public function Template() {
        return $this->belongsTo('App\Template', 'template_id');
    }

}
