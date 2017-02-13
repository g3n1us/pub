<?php

namespace G3n1us\Pub\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/*
use SocialAccount;
use UserGroup;
*/

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function social_account(){
        return $this->hasMany(SocialAccount::class);
    }    
    
    public function getAvatarAttribute(){
	    return $this->social_account->first() ? $this->social_account->first()->avatar : null;
    }
    
    public function groups(){
	    return $this->hasMany(UserGroup::class);
    }
    
}
