<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tier1 extends Model
{
    protected   $table      = 'tier1s';
    protected   $primaryKey = 'id';

    protected $fillable = [
        'client_id',
        'provider_id',
        'tier1_link',
        'emUrl',
        'anchor_text',
        'target_url'
    ];

    /**
     * Get the comments for the 
     */
    public function client(){
        return $this->belongsTo('App\Client');
    }

    public function provider(){
        return $this->belongsTo('App\Provider');
    }

    /**
     * End the comments for the client, provider
     */
}
