<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Layer extends Model
{
    
    protected $table        = 'layers';
    protected $primaryKey   = 'id';

    protected $fillable = [
        'angle',
        'background_color',
        'bold',
        'color',
        'description',
        'file',             // Address of uploaded image in the storage
        'font_id',          // Font id
        'font_size',
        'height',
        'italic',
        'left',
        'opacity',
        'order',
        'template_id',      // Template id
        'text_v_align',
        'text_h_align',
        'title',
        'top',
        'layer_type_id',    // LayerType id => text, image, shape
        'underline',
        'uuid',
        'width',
    ];

    public function Type() {
        return $this->belongsTo('App\LayerType', 'type');
    }

    public function Template() {
        return $this->belongsTo('App\Template', 'template');
    }

}
