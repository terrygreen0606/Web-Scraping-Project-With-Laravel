<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tier2 extends Model
{
    protected   $table      = 'tier2s';
    protected   $primaryKey = 'id';

    protected $fillable = [
        'client_id',
        'provider_id',
        'tier1_link_id',
        'anchor_text',
        'tier2_link'
    ];

    public function client(){
        return $this->belongsTo('App\Client');
    }

    public function provider(){
        return $this->belongsTo('App\Provider');
    }

}
