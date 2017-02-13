<?php

namespace G3n1us\Pub\Models;

use Illuminate\Database\Eloquent\Model;


class Workflow extends BaseModel
{
	protected $fillable = ['status']; 
	
    protected $casts = [
        'notes' => 'collection',
    ];	

    
    public function article(){
	    return $this->belongsTo(Article::class);
    }
    
    public function page(){
	    return $this->belongsTo(Page::class);
    }
    
}
