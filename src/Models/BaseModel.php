<?php

namespace G3n1us\Pub\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use SmartyView;

class BaseModel extends Model
{
	protected $keystoforget = [];
	
    public function flush_cache(){
	    foreach($this->keystoforget as $keytoforget) {
		    if($keytoforget == 'article_')
		    Cache::forget(cache_key($keytoforget, $this, false));
		    Cache::forget(cache_key($keytoforget, $this, false) . '_auth');
	    }
    }
    
    public function getBrandAttribute(){
	    return Brand::where('slug', BRAND_SLUG)->first();
    }
    
    
    public function display($template = null){
	    $classhandle = strtolower(class_basename(get_class($this)));
	    if(!$template)
		    $template = $this->template ?: $classhandle;
	    $data[$classhandle] = $this;
	    if($classhandle == "article") 
		    $data['articles'] = collect([$this]);
	    else if($this->articles)
		    $data['articles'] = $this->articles;
	    else 
		    $data['articles'] = collect([]);
	    $data['user'] = auth()->user();
	    $data['brand'] = $this->brand;
	    $data['page'] = $classhandle == "page" ? $this : null;
	    $data['heading'] = $this->heading;
		return view("pub::$template", $data);
// 		return SmartyView::fetch($template, $data);

    }
    
/*
    public function __wakeup(){
	    if(class_basename( get_class($this) ) == 'Article'){
*/
/*
		    if(!array_get($this->original, 'pub_date') || array_get($this->original, 'pub_date') == '0000-00-00 00:00:00'){
			    $this->pub_date = '1970-01-01 00:00:00';
		    }
		    if(!array_get($this->original, 'status')){
			    $this->status = 0;
		    }
*/
// 		    $this->pub_date = '1970-01-01';
/*
		    
		    $article_defaults = [
			    'pub_date'   => '1970-01-01',
		    ];
		    dump($this->getAttribute('pub_date'));
		    foreach($article_defaults as $k => $article_default){
			    if(!is_object($this->{$k}))
				    $this->{$k} = $article_default;
		    }
			    
*/
/*
	    }
	    parent::__wakeup();
	    
    }
*/

}
