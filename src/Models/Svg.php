<?php

namespace G3n1us\Pub\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Svg extends BaseModel
{
    use SoftDeletes;
    
    protected $casts = [
        'imgdata' => 'collection',
    ];	
	
	protected $fillable = ['path'];
    
}
