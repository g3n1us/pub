<?php

namespace G3n1us\Pub\Models;

// use Illuminate\Database\Eloquent\Model;
//use App\Scopes\DateScope;
use Illuminate\Database\Eloquent\Builder;
use DB;
use Cache;
use BlockMorpher;
use \Carbon\Carbon as Carbon;


class Page extends BaseModel
{
    
    public $timestamps = false;
    
    protected $fillable = [
        'config->keywords', 'config->robots',
    ];
    

	protected $casts = [
		'config'  => 'collection',
	];


	protected $appends = ['blocks'];

    protected static function boot()
    {
        parent::boot();

//         static::addGlobalScope(new DateScope);
/*
        static::addGlobalScope('pub_date', function(Builder $builder) {
            $builder->where('pub_date', '<', Carbon::now());
        });
        static::addGlobalScope('order_desc', function(Builder $builder) {
            $builder->orderBy('pub_date', 'desc');
        });
*/
        
    }
    public function tags(){
        return $this->morphToMany(Tag::class, 'taggable');
    }    

    public function metadata(){
	    return $this->hasOne(DittoPageMetadata::class, 'page_sid', 'page_sid');
    }

    public function workflow(){
	    return $this->hasOne(Workflow::class);
    }
        
    public function blocks(){
	    return $this->hasMany(Block::class, 'pageSid', 'page_sid');
    }
    
    public function original_blocks(){
	    return $this->hasMany(Block::class, 'pageSid', 'page_sid');
    }
    
/*
    public function getUrlAttribute($value){
	    return starts_with($value, '/') ? $value : "/$value";
    }
*/
    
/*
    public function setUrlAttribute($value){
	    return ltrim($value, '/');
    }
*/
    
    public function getBlocksAttribute(){
	    $blocks = $this->blocks()->get();
	    $blocks = $blocks->transform(function($v, $k){
		    return new BlockMorpher($v);
	    });
	    return $blocks;
    }
    
    public function areas(){
        return $this->hasMany(Area::class);
    }    
    
	public function showArea($handle, array $options = []){
		$default_options = [
			'is_global'      => false,
			'area_wrap'   => ['before' => '', 'after' => ''],
			'block_wrap'  => ['before' => '', 'after' => ''],
			'area_classes'=> isset($options['area_classes']) ? $options['area_classes'] . ' ccm_area_wrapper' : 'ccm_area_wrapper',
			
		];
		$options = collect($default_options)->merge(collect(array_except($options, ['area_classes'])));
// 		dd($options);
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
		else if(!$area) throw new \Exception('Page has unregistered areas. Please log in and visit the page to complete registration.');
		if(!$area->config && $authok){
			$area->config = $options;
			$area->save();
		}
		// This is only set here, so this property is only set at runtime and not persisted. Better way??
		$area->options = $options;
		return $area->display();

	}    
    
}


class DittoPageMetadata extends BaseModel
{
/*
    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'modified_date';
*/
	
    protected $table = "pg_page_metadatas";
	
    protected $primaryKey = "page_sid";
        
    public $timestamps = false;
    
    
//     protected $appends = ['lead_photo', 'url', 'link', 'id', 'body', 'author_from_displayname', 'tags', 'files', 'authors'];
//     protected $appends = ['page'];
    
//     protected $dateFormat = 'U';

    protected static function boot()
    {
        parent::boot();

    }

    public function page(){
	    return $this->belongsTo(Page::class, 'page_sid', 'page_sid');
    }
    
    
}

