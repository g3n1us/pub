<?php
return [
			'handle' => 'content',
			'editbuttons' => function($block){
				return editbuttons($block);
			},
			'default_content' => ['text1' => '<h4>New Block Added!</h4><p>Example content.</p>'],
			'resolve_content' => function($block){
				return $block->text1;
/*
				$wrap = (empty($block->wrap->before) && empty($block->wrap->after)) ? $block->area->config->block_wrap : $block->wrap;
				$wrappedcontent =  $wrap->before . "\n" . $block->text1 . "\n" . $wrap->after;
				
			    return ($block->editmode) ? '<div class="blockeditwrapper" style="position:relative;" data-bID="' . $block->id . '" data-container="body" data-content=\''.$block->editorbuttons.'\' id="blockID-'.$block->id.'">' . $wrappedcontent . '</div>' : $wrappedcontent;
*/
			},
			'edit_form' => function($block){
				return '<form id="modal_editor_form" method="post" data-editoraction="EDITOR--save_button" data-bid=' . $block->id . '><textarea class="editor" name="model[text1]">' . $block->text1 . '</textarea></form><div class="mt-1 text-right"></div>';
			}
		];