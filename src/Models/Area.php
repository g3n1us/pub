<?php

namespace G3n1us\Pub\Models;

use Illuminate\Database\Eloquent\Model;
use Editor;
use BlockMorpher;


class Area extends BaseModel
{
	
	protected $editmode;	
	
	protected $fillable = ['handle', 'page_id', 'article_id'];	
	
	protected $casts = [
		'config'  => 'object',
	];

	
	public function __construct(){

	}	
			
    public function page(){
        return $this->belongsTo(Page::class);
    }

    public function article(){
        return $this->belongsTo(Article::class);
    }

    public function blocks(){
        return $this->hasMany(Block::class);
    }
    
    public function getBlocksAttribute(){
	    $blocks = $this->blocks()->get() ?: collect([]);
		$associated_page_or_article = $this->page ?: $this->article;
		if(!$associated_page_or_article) return $blocks;
	    $pageareas = $associated_page_or_article->areas;
	    
	    if($pageareas->count() == 1 && $associated_page_or_article->original_blocks){
		    return $blocks->merge($associated_page_or_article->original_blocks);
	    }
	    else
	    	return $blocks;
    }
    
    public function display($template = null){
		$editmode = edit_mode();
		$listofbuttons = '<div class="block_add_content" style="display:none"><div class="editor-bootstrap">
		
	<button class="btn btn-secondary mb-half XEDITOR--btn_sm" data-editoraction="EDITOR--add_block" data-formdata=\'{"type":"content"}\' data-aID="'.$this->id.'">Content Block</button>
	<button class="btn btn-secondary mb-half XEDITOR--btn_sm" data-editoraction="EDITOR--add_block" data-formdata=\'{"type":"html"}\' data-aID="'.$this->id.'">HTML Block</button>
	<button class="btn btn-secondary mb-half XEDITOR--btn_sm" data-editoraction="EDITOR--add_block" data-formdata=\'{"type":"html"}\' data-aID="'.$this->id.'">Smarty Block</button>
	<button class="btn btn-secondary mb-half XEDITOR--btn_sm" data-editoraction="EDITOR--add_block" data-formdata=\'{"type":"article_list"}\' data-aID="'.$this->id.'">Article List</button>
	<button class="btn btn-secondary mb-half XEDITOR--btn_sm" data-editoraction="EDITOR--add_block" data-formdata=\'{"type":"page_list"}\' data-aID="'.$this->id.'">Page List</button>
	<button class="btn btn-secondary mb-half XEDITOR--btn_sm" data-editoraction="EDITOR--add_block" data-formdata=\'{"type":"lead_story"}\' data-aID="'.$this->id.'">Lead Story</button>
	<button disabled class="btn btn-secondary  mb-half" data-editoraction="EDITOR--add_block" data-formdata=\'{"type":"google_sheets"}\' data-aID="'.$this->id.'">Google Sheets Block</button>
	<button disabled class="btn btn-secondary  mb-half " data-editoraction="EDITOR--add_block" data-formdata=\'{"type":"data_api"}\' data-aID="'.$this->id.'">Data/API Block</button>
	
</div></div>';
		$globalstring = $this->config->is_global ? "Global " : "";
	    $addblocksbutton = ($editmode) ? "<div class=\"editor-bootstrap\">$listofbuttons".'<button class="EDITOR--btn EDITOR--btn-block btn btn-primary" data-toggle="popover" title="Add Block">Add Block to ' . $globalstring  . studly_case($this->handle) . ' Area</button></div>' : '';	
	    
	    $blocks = $this->blocks;
	    $sortingarray = object_get($this->config, 'block_order', []);
	    $blocks = $blocks->sortBy(function($v,$k) use($sortingarray){
		    return array_search($v->id, $sortingarray);
	    });
	    $default_content = isset($this->config->default_content) ? $this->config->default_content : '';
	    $blocks = $blocks->transform(function($value){
		    return new BlockMorpher($value);
	    });
	    
// $noblocksstring = auth()->check() ? '<span class="editor-bootstrap"><div class="alert alert-info" data-defaultcontent="'.$this->id.'">No blocks added.</div></span>' : '';
		
	    $blocks_string = $blocks->isEmpty() ? $default_content : $blocks->pluck('content')->implode("\n");

	    $pageid = $this->page ? $this->page->id : 'global';

	    return '<section data-aid="'.$this->id.'" id="ccm_area_wrapper_page_'. $pageid .'_'. $this->handle .'" class="' . $this->config->area_classes . '">' . 
		    $this->config->area_wrap->before . 
		    $blocks_string . 
		    $this->config->area_wrap->after .
		    $addblocksbutton . 
		    '</section>';

    }
    
    public function __toString(){
	    return $this->display();
    }
}
