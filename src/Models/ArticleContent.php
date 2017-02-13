<?php

namespace G3n1us\Pub\Models;

use Illuminate\Database\Eloquent\Model;


class ArticleContent extends BaseModel
{	
	protected $fillable = ['id', 'article_id'];
	
	public $timestamps = false;
	
    public function article(){
	    return $this->belongsTo(Article::class);
    }
}
