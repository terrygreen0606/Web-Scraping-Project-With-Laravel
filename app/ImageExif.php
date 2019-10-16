<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageExif extends Model
{
    
    protected $table        = 'image_exifs';
    protected $primaryKey   = 'id';

    protected $fillable = [
        'exif_id',         // Exif id
        'image_id',        // Image id
        'value',
    ];

}
