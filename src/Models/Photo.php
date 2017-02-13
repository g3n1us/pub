<?php

namespace G3n1us\Pub\Models;

use Illuminate\Database\Eloquent\Model;


class Photo extends BaseModel
{
	
	protected $primaryKey = 'photo_id';
	
	public $timestamps = false;
	
	protected $connection = 'images';
	protected $table = 'photos';
// 	protected $table = 'p_photos';
	
	protected $appends = ['url', 'thumb', 'medium', 'square', 'credit', 'id'];
	
    public function article(){
	    return $this->belongsTo(Article::class);
    }
    
    public function getUrlAttribute(){
	    // 53ce555ddee342d08f07d733aa81f91a
	    // 1060x600-
	    // r960-
	    if($this->connection == 'weximages') return 'http://cdn.washingtonexaminer.biz/cache/r960-' . $this->hash . '.jpg';
	    else return 'http://cdn.washingtonexaminer.biz/cache/r960-' . $this->hash . '.jpg';
// 	    else return 'http://cdn.weeklystandard.biz/cache/w760-' . $this->hash . '.jpg';
	    
	    
    }
    
    public function getThumbAttribute(){
	    if($this->connection == 'weximages') return 'http://cdn.washingtonexaminer.biz/cache/newsstand-' . $this->hash . '.jpg';
	    else return 'http://cdn.washingtonexaminer.biz/cache/newsstand-' . $this->hash . '.jpg';
    }
    
    public function getMediumAttribute(){
	    return $this->getUrlAttribute();
    }
    
    public function getSquareAttribute(){
	    if($this->connection == 'weximages') return 'http://cdn.washingtonexaminer.biz/cache/sq280-' . $this->hash . '.jpg';
	    else return 'http://cdn.washingtonexaminer.biz/cache/sq280-' . $this->hash . '.jpg';
    }
    
    public function getIdAttribute(){
	    return $this->photo_id;
    }
    
    public function getCreditAttribute(){
	    return strlen($this->source) ? $this->source : $this->photographer;
    }
    
}
