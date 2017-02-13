<?php
return 
[
	'handle' => 'page_list',
	'editbuttons' => function($block){
		return editbuttons($block);
	},
	'default_content' => ['text1' => '5'],
	'resolve_content' => function($block){
		$limit = $block->text1 ?: 5;
		$pages = Page::limit($limit)->get();
// 				dd($pages->first());
		$data = ['pages' => $pages];
		return SmartyView::fetch('parts/raw_page_list.tpl', $data);
	},
	'edit_form' => function($block){
		return '<form id="modal_editor_form" method="post" data-editoraction="EDITOR--save_button" data-bid=' . $block->id . '><label for="text1">Pages to Show</label><input id="text1" class="form-control" name="model[text1]" value="' . $block->text1 . '"></form><div class="mt-2 text-right"><button type="submit" class="hide formsubmitter btn btn-primary btn-lg">Save</button></div>';
		
	}
];