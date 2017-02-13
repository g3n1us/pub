<?php

namespace G3n1us\Pub\Models;

// use Illuminate\Database\Eloquent\Model;
//use App\Scopes\DateScope;
use Illuminate\Database\Eloquent\Builder;
use DB;
use Cache;
use \Carbon\Carbon as Carbon;
use BlockMorpher;


class Block extends BaseModel
{
    
//     public $timestamps = false;
    
	protected $casts = [
		'config'  => 'object',
	];

	protected $fillable = ['config'];

    protected static function boot(){
        parent::boot();
    }

    public function area(){
	    return $this->belongsTo(Area::class);
    }

    public function page(){
	    return $this->belongsTo(Page::class);
    }
    
    public function type(){
	    return $this->hasOne(BlockType::class, 'widget_type_id', 'widgetTypeId');
    }
    
/*
    public function getIdAttribute(){
	    return $this->widgetId;
    }
*/
    
    public function getContentAttribute(){
	    return $this->text1;
    }
    
    public function display($template = null){
	    return new BlockMorpher($this);
    }
}



class BlockType extends BaseModel{
    protected $table = "block_types";
	
    protected $primaryKey = "widget_type_id";
        
    public $timestamps = false;
    
    protected $appends = ['handle'];
    
    public function getHandleAttribute($handle){
	    return $handle ?: str_slug($this->widget);
    }
    
}