<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LayerType extends Model
{
    
    protected   $table      = 'layer_types';
    protected   $primaryKey = 'id';
    public      $timestamps = false;

    protected $fillable = [
        'id',
        'title',
    ];

}
