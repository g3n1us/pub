<?php
namespace G3n1us\Pub\Services;

use \Carbon\Carbon;
use Request;

	
class Editor{
	public $theme = 'editor';
	public $editmode;
	public $page;
	public $site;
	
	
	public function __construct(){
		
		$this->editmode = isset($_GET['editmode']) || isset($_GET['edit_mode']);
	}

	public function editbuttons($block){
	    $editorbuttons = [];
	    $editorbuttons[] = '<span data-editoraction="EDITOR--delete_block" data-bID="' . $block->id . '" class="tiny-edit-button tiny-delete-button">Delete Block</span>';
	    $editorbuttons[] = '<span data-XXtarget="#blockID-'.$block->id.'" data-editoraction="EDITOR--edit_button" data-bID="' . $block->id . '" class="tiny-edit-button EDITOR--edit_button">Edit Block</span>';
		
		return implode('', $editorbuttons);
	}		
	
	
	public function __toString(){
		return json_encode($this);
	}
	
}