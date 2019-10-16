<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected   $table      = 'providers';
    protected   $primaryKey = 'id';

    protected $fillable = [
        'uuid',
        'title'
    ];

    protected $hidden = [
        'id'
    ];

     /**
     * Get the comments for the 
     */
    public function tier1(){
        return $this->hasMany('App\Tier1');
    }
    
    public function tier2(){
        return $this->hasMany('App\Tier2');
    }
}
