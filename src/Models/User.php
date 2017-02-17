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
	    return $this->social_account->first() ? $this->social_account->first()->avatar : 'https://www.gravatar.com/avatar/'.$this->id.'?d=retro&f=y';
    }
    
    public function getAvatarLargeAttribute(){
	    return $this->social_account->first() ? $this->social_account->first()->metadata->get('avatar_original') : 'https://www.gravatar.com/avatar/'.$this->id.'?d=retro&f=y&s=400';
    }
    
    public function groups(){
	    return $this->hasMany(UserGroup::class);
    }
    
}
