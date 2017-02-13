<?php
namespace G3n1us\Pub\Services;

use \Carbon\Carbon;
use Request;

use Block;

	
class BlockMorpher{
	
	public $content = '';
	
	public $wrap = "";
	
	public function __construct(\G3n1us\Pub\Models\Block $block, $standalone_handle = null){
		$this->originalblock = $block;
		$this->block = $block;
		$this->config = is_null($block->config) ? json_decode('{"_isDefault":true}') : $block->config;
		

		
// 		$this->block->type = $this->block->type ?: 'content'; 
		$this->editor = new Editor;
		$this->editmode = $this->editor->editmode;
// 		$this->block->editmode = $this->editor->editmode;

		$this->handle = $this->block->handle ?: 'content';
		if($this->handle == 'html-block') $this->handle = 'html';
		$func = config("pub.block_types.{$this->handle}.resolve_content");
		if(!$func) $func = config("pub.block_types.default.resolve_content");

		$this->content = call_user_func_array($func, [$this]);
		
		$before_block = object_get($this->config, 'block_wrap.before','');
		$after_block = object_get($this->config, 'block_wrap.after',  '');
		$this->wrap = json_decode(json_encode(['before' => $before_block, 'after' => $after_block]));
		$this->wrap = (empty($this->wrap->before) && empty($block->wrap->after) && $block->area) ? $block->area->config->block_wrap : $this->wrap;
		
		$blockclasses = object_get($this->config, 'blockclasses');
		$blockstyles = object_get($this->config, 'styles');
		if($blockstyles) $blockstyles = " style='$blockstyles'";
		$style_before_block = $style_after_block = null;
		if($blockclasses || $blockstyles) {
			$style_before_block = "<div class='$blockclasses'$blockstyles>";
			$style_after_block = "</div>";
		}
		
		$editing_id = $this->block->id ?: $standalone_handle;
		$content =  $this->wrap->before . "\n" . "\t$style_before_block{$this->content}$style_after_block" . "\n" . $this->wrap->after;
	    $this->content = ($this->editmode) ? '<div class="blockeditwrapper" data-bID="'.$editing_id.'" data-container="body" data-content=\''.$this->block->editorbuttons.'\' id="blockID-'.$this->block->id.'">' . $content . '</div>' : $content;
		
	}


	public function __get($prop){
// 		$from_config = config("concrete.block_types.{$this->block->type}.$prop", false);
		$from_config = config("pub.block_types.{$this->handle}.$prop", false);
		if(method_exists($this, "get".studly_case($prop)))
			return $this->{"get".studly_case($prop)}();
		else if( $from_config )
			return $from_config;
		else
			return object_get($this->block, $prop);
	}


	public function getEditbuttons(){
		$block = $this->block;
	    $editorbuttons = [];
	    $editorbuttons[] = '<span data-editoraction="EDITOR--delete_block" data-bID="' . $block->id . '" class="tiny-edit-button tiny-delete-button">Delete Block</span>';
	    $editorbuttons[] = '<span data-XXtarget="#blockID-'.$block->id.'" data-editoraction="EDITOR--edit_button" data-bID="' . $block->id . '" class="tiny-edit-button EDITOR--edit_button">Edit Block</span>';
		
		$this->block->editbuttons = implode('', $editorbuttons);
		return implode('', $editorbuttons);
	}		
	
	public function getContent(){
		return $this->content;
	}
	
	public function getEditmode(){
		return $this->editmode;
	}
	
	public function __toString(){
		return $this->getContent();
	}
	
}