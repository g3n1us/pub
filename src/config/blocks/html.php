<?php
return [
			'handle' => 'html',
			'editbuttons' => function($block){
				return editbuttons($block);
			},
			'default_content' => ['text1' => '<h4>New Block Added!</h4><p>Example content.</p>'],
			'resolve_content' => function($block){
				return $block->text1;
// 				dd($block->id);
// 				$wrap = (empty($block->wrap->before) && empty($block->wrap->after)) ? $block->area->config->block_wrap : $block->wrap;
// 				$wrappedcontent =  $wrap->before . "\n" . $content . "\n" . $wrap->after;
				$wrappedcontent = $block->text1;
			    return (edit_mode()) ? '<div class="blockeditwrapper" style="position:relative;" data-bID="' . $block->id . '" data-container="body" data-content=\''.$block->editorbuttons.'\' id="blockID-'.$block->id.'">' . $wrappedcontent . '</div>' : $wrappedcontent;
			},
			'edit_form' => function($block){
				return '<form id="modal_editor_form" method="post" data-editoraction="EDITOR--save_button" data-bid="' . $block->id . '"><textarea class="editor xadvanced-only" name="model[text1]">' . $block->text1 . '</textarea></form><div class="mt-1 text-right"><button type="submit" class="formsubmitter hide btn btn-primary btn-lg">Save</button></div>';
			}
		];