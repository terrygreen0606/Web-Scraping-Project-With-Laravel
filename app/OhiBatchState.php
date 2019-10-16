<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OhiBatchState extends Model
{
    
    protected   $table      = 'ohi_batch_states';
    protected   $primaryKey = 'id';
    public      $timestamps = false;

    protected $fillable = [
        'title'
    ];


    public function Campaings () {

        return $this->hasMany('App/Campaign', 'ohi_batch_state');

    }

}
