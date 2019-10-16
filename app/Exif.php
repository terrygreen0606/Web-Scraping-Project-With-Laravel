<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exif extends Model
{
    
    protected $table        = 'exifs';
    protected $primaryKey   = 'id';

    protected $fillable = [
        'description',
        'title',
        'uuid',
    ];


    // TODO: Fetch Exif Images
    public function Images() {

        $imageExifs = $this->hasMany('App\ImageExif', 'exif', 'id');


        // foreach ($imageExifs as $key => $value) {
            
        // }

        // return $images;

    }

}