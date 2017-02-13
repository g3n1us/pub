<?php

namespace G3n1us\Pub\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cache;

use Storage;


class File extends BaseModel
{
	
	use SoftDeletes;
	
	protected $fillable = ['filename'];	
	
	protected $appends = ['url', 'thumb', 'square', 'pivot_metadata', 'medium'];

    protected $casts = [
        'metadata' => 'collection',
        'pivot_metadata' => 'collection',
    ];	
	
	
    public function tags(){
        return $this->morphToMany(Tag::class, 'taggable', 'taggables')->withPivot('metadata');
    }    
    
    public function articles(){
        return $this->morphedByMany(Article::class, 'fileable', 'fileables')->withPivot('metadata');
    }
    
    private static function getPhotoFilenameAndDir($file){
	    $filename = $file->filename;
	    $return = [];
	    $return['dir'] = 'originals';
	    $return['full_filename'] = null;
	    if($file->bucket == 'image.washingtonexaminer.biz') {
		    $split = explode('/', $file->filename);
		    $hash = rtrim($split[count($split) - 1], '.jpg');
		    $return['full_filename'] = "http://cdn.washingtonexaminer.biz/cache/1060x600-$hash.jpg";
	    }
	    $return['filename'] = str_replace('originals/', '', $filename);	    	    
	    if(ends_with($return['filename'], '.pdf')) $return['full_filename'] = Storage::disk('s3')->url("placeholders/pdf.svg");	    
	    if(ends_with($return['filename'], '.svg')) $return['dir'] = "originals";
	    if(ends_with($return['filename'], '.gif')) $return['dir'] = "originals";
	    return $return;
    }
    
    public function display($template = null){
	    extract(File::getPhotoFilenameAndDir($this));
	    if($full_filename) 
		    return '<img src="'.$full_filename.'">';	    	    	    
	    return '<img src="'.Storage::disk('s3')->url("$dir/$filename").'">';
    }
    
    public function getUrlAttribute(){
	    extract(File::getPhotoFilenameAndDir($this));	
	    if($full_filename) return $full_filename;		    	    
	    return Storage::disk('s3')->url("$dir/$filename");
    }
    
    
    public function getThumbAttribute(){
	    extract(File::getPhotoFilenameAndDir($this));	
	    if($full_filename) return $full_filename;
	    $dir = $dir ?: 'resized/small';
	    return Storage::disk('s3')->url("$dir/$filename");
    }
    
    public function getSquareAttribute(){
	    extract(File::getPhotoFilenameAndDir($this));	
	    if($full_filename) return $full_filename;
	    $dir = $dir ?: 'resized/cropped-to-square';
	    return Storage::disk('s3')->url("$dir/$filename");
    }
        
    public function getMediumAttribute(){
	    extract(File::getPhotoFilenameAndDir($this));	
	    if($full_filename) return $full_filename;		    		    		    
	    $dir = $dir ?: 'resized/large';
	    return Storage::disk('s3')->url("$dir/$filename");
    }
    
    
    public function getPivotMetadataAttribute(){
	    if($this->pivot){
		    $metadata = collect(json_decode($this->pivot->metadata));
		    if($metadata->get('lead_photo', null)) $metadata->put('lead_photo', $metadata->get('lead_photo') === "true");
		    return $metadata;
	    }
    }
    
    
    
}


