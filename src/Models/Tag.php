<?php

namespace G3n1us\Pub\Models;

use Illuminate\Database\Eloquent\Model;
use DB;


class Tag extends BaseModel
{
/*
	protected $primaryKey = "section_id";	
	
	protected $table = "sections";
	
	protected $connection = "article";
*/
	
	protected $fillable = ['handle', 'name'];
	
// 	protected $connection = 'mysql';


    
    public function articles(){
        return $this->morphedByMany(Article::class, 'taggable');
    }
    
    public function files(){
        return $this->morphedByMany(File::class, 'taggable');
    }
    public function getLegacyArticlesAttribute(){
/*
	    $articles = DB::table('newsok7.section_rels')
		    ->leftJoin('newsok7.section_authors', 'newsok7.section_authors.id', '=', 'newsok7.section_rels.section_id')	    
		    ->where('newsok7.section_rels.section_id', $this->id)
		    ->leftJoin('newsok7.articles', 'newsok7.articles.article_id', '=', 'newsok7.section_rels.module_id')
		    ->get();
*/

// NEEDS WORK
/*
	    $articles = DB::table('newsok7.section_rels')
		    ->leftJoin('newsok7.sections', 'newsok7.sections.section_id', '=', 'newsok7.section_rels.section_id')	    
		    ->where('newsok7.section_rels.section_id', $this->id)
		    ->pluck('module_id');
	    $articles = \App\Article::whereIn('article_id', $articles)->paginate();
	    return $articles;
*/
//		    ->pluck('section');
	    
    }
    
/*
    public function getArticlesAttribute($articles){
	    if(!$articles) return $this->legacy_articles;
	    return $this->legacy_articles->merge($articles);
    }
*/
}
