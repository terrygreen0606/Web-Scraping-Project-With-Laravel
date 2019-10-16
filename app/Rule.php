<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{

    protected   $table = 'rules';
    protected   $primaryKey = 'id';
    public      $timestamps = false;

    /**
     * 
     * for now, there is tow rules
     * 
     * Administrator
     * Client
     * 
     */
    protected $fillable = [
        'id',
        'admin',
        'title',
        'uuid'
    ];

    protected $hidden = [
        'id'
    ];

    public function Users() {
        return $this->hasMany('App\User', 'rule', 'id');
    }

}
