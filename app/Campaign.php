<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    
    protected   $table      = 'campaigns';
    protected   $primaryKey = 'id';

    protected $fillable = [
        'uuid',
        'user_id',
        'title',
        'batch_id',
        'ohi_batch_state',
    ];


    public function Links () {

        return $this->hasMany('App\CampaignLink', 'campaign_id');

    }


    public function BatchState () {

        return $this->belongsTo('App\OhiBatchState', 'ohi_batch_state');

    }

}
