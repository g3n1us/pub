<?php

namespace G3n1us\Pub\Models;

use Illuminate\Database\Eloquent\Model;

class Edition extends BaseModel
{
	protected $dates = ['pub_date'];
	
    protected $casts = [
        'metadata' => 'object',
    ];	
    
}
