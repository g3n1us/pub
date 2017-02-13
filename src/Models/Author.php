<?php

namespace G3n1us\Pub\Models;

use Illuminate\Database\Eloquent\Model;

use Storage;
use DB;
use Cache;
use Request;

use \Illuminate\Pagination\LengthAwarePaginator as Paginator;

class Author extends BaseModel
{
	public $timestamps = false;
	
    public function articles(){
        return $this->morphedByMany(Article::class, 'authorable');
    }	
    
    protected $appends = ['heading'];
    
    public $template = 'article_list.tpl';
    
    public function getHeadingAttribute(){
	     return "Articles by " . $this->displayname;
    }
}


