<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignLink extends Model
{
    
    protected   $table      = 'campaign_links';
    protected   $primaryKey = 'id';

    protected $fillable = [
        'added_to_ohi_at',
        'campaign_id',
        'url',
    ];


    public function Campaign() {

        return $this->belongsTo('App\Campaign', 'campaign_id');

    }

}
