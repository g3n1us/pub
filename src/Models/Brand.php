<?php

namespace G3n1us\Pub\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Brand extends BaseModel
{
	
	use SoftDeletes;
	
	protected $dates = ['deleted_at'];
	
    protected $casts = [
        'styles' => 'array',
    ];	
	
	public function getRouteKeyName(){
	    return 'handle';
	}
	
    public function allpages(){
        return $this->hasMany('App\Page');
    }
    
/*
    public function articles(){
	    return $this->hasMany('App\Article');
    }
*/

	public function getFeaturedArticlesAttribute(){
		return Article::orderBy('modified_date', 'desc')->limit(5)->get();
	}
	
	public function getStylesAttribute($value){
		$brandstyles = config('app.brands.' . $this->handle);
		$defaults = config('app.brands.default');
		$brandstyles = $brandstyles ? $brandstyles : [];
		$brandstyles = array_merge($defaults, $brandstyles);
// 		database settings take priority
/*
		$brandstyles = array_merge($brandstyles, [
			'sidebar_bg_color' => $this->sidebar_bg_color ? $this->sidebar_bg_color : 'rgba(238, 238, 238, 0.7)', 
			'bg_color' => $this->bg_color ? $this->bg_color : '#FFF',  
			'body-bg' => $this->bg_color ? $this->bg_color : '#FFF',  
		]);
*/
		if(!empty($this->bg_image)) $brandstyles['bg_image'] = $this->bg_image;
// Sort One...
		$brandstyles = array_sort($brandstyles, function ($value, $key) use($brandstyles) {
			$allstring = implode(" ", $brandstyles);
			$pattern = '/\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9\-_\x7f-\xff]*)/';
			$matches = [];
			$matches2 = [];
			$result = preg_match_all($pattern, $value, $matches);
			$result2 = preg_match_all($pattern, $allstring, $matches2);
			
			$orderval = 0;
			if(str_contains($value, '$')) {
				$orderval = 1;
			}
			if($orderval > 0) {
				$intersection = array_intersect(array_keys($brandstyles), last($matches));

				if(!empty($intersection)) {
					$orderval = 3;
				}
				
				if(in_array($key, last($matches2))){
					$orderval = 2;
				}
			}
		    return $orderval;
		});		
		
		if(empty($value)) return $brandstyles;
		
		return array_merge($brandstyles, json_decode($value, true));
	}
		

    public function getPagesAttribute($value){
	    
/*
	    $pages = $this->allpages->unique('path');
	    
		$pages->transform(function ($item, $key) {

			$versions = \App\Page::where('path', $item->path)->get();
		    return $versions->last();			
		});	    
*/
		$pages = Page::get();
	    return $pages;
    }
    
    
    public function scopeListable($query){
	    return $query->where('nav_hide', 0);
    }
    
	
}
