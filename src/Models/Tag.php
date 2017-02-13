<?php

namespace G3n1us\Pub\Models;

use Illuminate\Database\Eloquent\Model;
use DB;


class Tag extends BaseModel
{
	
	protected $fillable = ['handle', 'name'];
	    
    public function articles(){
        return $this->morphedByMany(Article::class, 'taggable');
    }
    
    public function files(){
        return $this->morphedByMany(File::class, 'taggable');
    }
    public function getLegacyArticlesAttribute(){


	}
}
