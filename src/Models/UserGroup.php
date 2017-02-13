<?php
namespace G3n1us\Pub\Models;

use Illuminate\Database\Eloquent\Model;


class UserGroup extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group', 'user_id', 'id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    
    public function user(){
        return $this->hasOne(User::class);
    }    
    
    public function getNameAttribute(){
	    return config("groups.".$this->group)['name'];
    }
    
/*
    public function __get($name){
	    parent::__get($name);
    }
*/
    
}
