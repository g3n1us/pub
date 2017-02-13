<?php

namespace G3n1us\Pub\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends BaseModel
{
    protected $casts = [
        'fields' => 'collection',
    ];
}
