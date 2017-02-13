<?php
return [
			'handle' => 'lead-story',
			'editbuttons' => function($block){
				return editbuttons($block);
			},
			'default_content' => ['key1' => 0],
			'resolve_content' => function($block){
/*
				$wrap = (empty($block->wrap->before) && empty($block->wrap->after)) ? $block->area->config->block_wrap : $block->wrap;
				$wrappedcontent =  $wrap->before . "\n" . $content . "\n" . $wrap->after;
*/
				if(!$block->key1) $article = Article::first();
				else $article = Article::findOrFail($block->key1);
				$data = ['articles' => [$article]];
// 				dd($data);
				$wrappedcontent = SmartyView::fetch('parts/raw_article_list.tpl', $data);
				return "<h2>{$block->title}<small><br>{$block->text1}</small></h2>$wrappedcontent";
// 			    return ($block->editmode) ? '<div class="blockeditwrapper" style="position:relative;" data-bID="' . $block->id . '" data-container="body" data-content=\''.$block->editorbuttons.'\' id="blockID-'.$block->id.'">' . $wrappedcontent . '</div>' : $wrappedcontent;
			},
			'edit_form' => function($block){
				$article_id = $block->text1 ?: Article::first()->id;
				return '<form id="modal_editor_form" method="post" data-editoraction="EDITOR--save_button" data-bid=' . $block->id . '>
				<fieldset class="form-group">
				<label for="field1">Title</label>
				<input id="blocktitle" class="form-control" name="model[title]" value="' . $block->title . '">
				</fieldset>
				
				<fieldset class="form-group">
				<label for="field1">Subitle/Supporting Text</label>
				<input id="blocksubtitle" class="form-control" name="model[text1]" value="' . $block->text1 . '">
				</fieldset>
				
				<fieldset class="form-group">
				<label for="field1">Article ID</label>
				<input id="key1" class="form-control" name="model[key1]" value="' . $article_id . '">
				<button type="button" data-filepicker="#key1" class="btn btn-info btn-sm">Choose Article</button>
				</fieldset>
				
				</form><div class="mt-1 text-xs-right"><button type="submit" class="hide formsubmitter btn btn-primary btn-lg">Save</button></div>';
			}
		];