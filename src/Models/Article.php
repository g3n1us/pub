<?php

namespace G3n1us\Pub\Models;

// use Illuminate\Database\Eloquent\Model;
//use App\Scopes\DateScope;
use Illuminate\Database\Eloquent\Builder;
use DB;
use Cache;


use \Carbon\Carbon as Carbon;

class Article extends BaseModel
{

	protected $fillable = ['id'];
    
    protected $dates = ['created_at', 'updated_at', 'pub_date'];
    
	protected $keystoforget = [
		'article_full' => 'article_',
		'article_lead_photo_' => 'article_lead_photo_',
		'article_versions_' => 'article_versions_',
	];
    // protected $hidden = ['created_at', 'updated_at', 'pub_date'];
        
    protected $appends = ['lead_photo', 'url', 'link', 'body', 'author_from_displayname', 'legacy_sections', 'status', 'relative_date'];
    

    protected static function boot()
    {
        parent::boot();
        

//         static::addGlobalScope(new DateScope);
        static::addGlobalScope('order_desc', function(Builder $builder) {
            $builder->orderBy('pub_date', 'desc');
        });

        static::addGlobalScope('is_approved', function(Builder $builder) {
	        //if(!auth()->check())
	            $builder->where('pub_date', '<', Carbon::now())->where('approved', 1);
        });

/*
        static::addGlobalScope('pub_date', function(Builder $builder) {
            $builder->where('pub_date', '<', Carbon::now());
        });
*/

        
    }

    
    public function content(){
	    return $this->hasOne(ArticleContent::class);
    }
    
    public function workflow(){
	    return $this->hasOne(Workflow::class);
    }
        
    public function ext(){
	    return $this->hasOne(ArticleContent::class);
    }
    
    public function tags(){
        return $this->morphToMany(Tag::class, 'taggable');
    }    
    
    public function files(){
        return $this->morphToMany(File::class, 'fileable')->withPivot('metadata');
    }    
    
    public function authors(){
        return $this->morphToMany(Author::class, 'authorable');
    }    
    
    public function photo(){
        return $this->hasOne(File::class, 'article_id', 'photo_id');
	    
// 	    return $this->hasManyThrough(\App\Photo::class, \App\PhotoPub::class, 'photo_pub_id', 'photo_id', 'lead_photo_id');
    }
    
    public function getStatusAttribute(){
	    $return = [];
	    $return['approved'] = true;
	    $return['reasons'] = [];	    	    
	    if(!$this->approved) {
		    $return['approved'] = false;
		    $return['reasons'][] = 'Article is not approved';
	    }
	    if($this->pub_date > Carbon::now()){
		    $return['approved'] = false;		    
		    $return['reasons'][] = 'Article\'s publication date is set to a date in the future.';
	    }
	    $return['render'] = function() use($return){
		    $dangerstring = $return['approved'] ? "success" : "danger";
		    $activestring = $return['approved'] ? "Active" : "Inactive";
		    $markup = "<div class='alert fade show alert-$dangerstring' style='padding:.5rem'><a class='close' data-dismiss='alert'>&times;</a><b>Article is $activestring</b><ul class='pl-half mb-0' style='list-style-type:none;'>";
		    foreach($return['reasons'] as $reason)
			    $markup .= "<li>$reason</li>";
		    $markup .= "</ul></div>";
		    return $markup;
	    };

	    return (object)$return;
    }
    
    public function getAuthorFromDisplaynameAttribute(){
	    return Author::where('displayname', $this->author_display)->first();
    }
    
    public function getUrlAttribute(){
	    return url( $this->slug ? 'article/'.$this->slug : 'article/'.$this->id );
    }
    
/*
    public function getIdAttribute(){
	    return $this->id;
    }
*/

	public function getRelativeDateAttribute(){
		return $this->pub_date->diffForHumans();
	}
    
    public function getLinkAttribute(){
// 	    dd($this->connection == 'wex');
		return url('article/' . $this->id);
// 	    if($this->connection == 'wex') return 'http://www.washingtonexaminer.com/article/' . $this->id;
// 	    else return 'http://www.weeklystandard.com/article/' . $this->id;
    }

    public function getBodyAttribute(){
	    $content = !$this->content ? null : $this->content->body;
	    preg_match_all('/\[\[area=(.*?)\]\]/', $content, $matches);
	    $areas = [];
	    foreach($matches[0] as $i => $m){
		    $areas[$matches[1][$i]] = $matches[0][$i];
	    }
	    foreach($areas as $area_handle => $area_placeholder){
		    $content = str_replace($area_placeholder, "<div class='area_loader' data-loadurl='/dashboard/area-ajax/{$this->id}/$area_handle'>Loading...</div>", $content);
	    }

	    return $content;

    }   
    
    public function getLeadPhotoAttribute(){
	    
	    return Cache::remember('article_lead_photo_'.$this->id, 99999, function(){
		    if(!$this->photo){
			    $explicit_lead_photo = $this->files()->wherePivot('metadata->lead_photo', "true")->first();
			    if($explicit_lead_photo) return $explicit_lead_photo;
			    else return $this->files()->first();
		    }
		    return $this->photo;
	    });
    }
    
    
    public function getLegacySectionsAttribute(){
	    return $this->tags;
    }
    
/*
    public function getTagsAttribute($tags){
	    dd($this);
// 	    dd($this->legacy_sections);
	    return $this->legacy_sections->merge($tags === null ? [] : $tags);
    }
*/
    public function getAllTagsAttribute(){
		$tags = $this->tags;
		return $tags;
    }
    
    public function areas(){
        return $this->hasMany(Area::class);
    }    
    
    
    // This is manually copied from \App\Page -- edit there.
	public function showArea($handle, array $options = []){
		
		
		
		die('not using showArea from the Article class');
		
		
		
		$default_options = [
			'is_global'      => false,
			'area_wrap'   => ['before' => '', 'after' => ''],
			'block_wrap'  => ['before' => '', 'after' => ''],
			'area_classes'=> isset($options['area_classes']) ? $options['area_classes'] . ' ccm_area_wrapper' : 'ccm_area_wrapper',
			
		];
		$options = collect($default_options)->merge(collect(array_except($options, ['area_classes'])));

		$options = json_decode(json_encode($options->toArray()));
		if($options->is_global) $area = Area::where('handle', $handle)->first();
		else $area = $this->areas()->where('handle', $handle)->first();
		$authok = auth()->check() || defined('INITIAL_SETUP_INCOMPLETE');

		if(!$area && $authok){
			$area = new Area;
			$area->handle = $handle;
			if($options->is_global) $area->save();
			else $this->areas()->save($area);
		}
		else if(!$area) return null;
// 		else if(!$area) throw new \Exception('Page has unregistered areas. Please log in and visit the page to complete registration.');
		if(!$area->config && $authok){
			$area->config = $options;
			$area->save();
		}
		// This is only set here, so this property is only set at runtime and not persisted. Better way??
		$area->options = $options;
		return $area->display();
	}    
}


class PhotoPub extends BaseModel
{
	
	protected $primaryKey = 'photo_pub_id';
	
	protected $connection = 'images';

	protected $table = 'photos_pub';

    public function photo(){
	    return $this->belongsTo(Photo::class, 'photo_id', 'photo_id');
    }
	
    public function article(){
	    return $this->belongsTo(Article::class, 'photo_pub_id', 'lead_photo_id');
    }
	
}
