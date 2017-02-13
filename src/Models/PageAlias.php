<?php

namespace G3n1us\Pub\Models;

use Illuminate\Database\Eloquent\Builder;
use Cache;


class PageAlias extends BaseModel
{
//     protected $table = "page_aliases";
	
    protected $primaryKey = "page_id";
        
    public $timestamps = false;

    protected static function boot(){
        parent::boot();
    }

    public function page(){
	    return $this->belongsTo(Page::class);
    }
}
