<?php

namespace G3n1us\Pub\Models;

use Illuminate\Database\Eloquent\Model;


class SocialAccount extends BaseModel
{
    protected $fillable = ['user_id', 'provider_user_id', 'provider', 'metadata', 'email', 'name', 'nickname', 'avatar'];
    
    protected $casts = [
        'metadata' => 'collection',
    ];    

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
