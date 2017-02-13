<?php
return [
			'handle' => 'default',
			'editbuttons' => function($block){
				return editbuttons($block);
			},
			'default_content' => ['text1' => '<h4>New Block Added!</h4><p>Example content.</p>'],
			'resolve_content' => function($block){
				return $block->text1;
// 				$wrap = (empty($block->wrap->before) && empty($block->wrap->after)) ? $block->area->config->block_wrap : $block->wrap;
// 				$wrappedcontent =  $wrap->before . "\n" . $content . "\n" . $wrap->after;
				$wrappedcontent = $block->text1;
				if($block->handle == 'smarty'){
					$smarty = new \Smarty;
					foreach($block->block as $k => $v) $smarty->assign($k, $v);
	// 				return $smarty->fetch('string:'.$block->text1);
					$wrappedcontent = $block->text1;
				}
				else
					$wrappedcontent = $block->text1 ? $block->text1 : $block->handle . ' doesn\'t have a handler';
				
			    return (edit_mode()) ? '<div class="blockeditwrapper" style="position:relative;" data-bID="' . $block->id . '" data-container="body" data-content=\''.$block->editorbuttons.'\' id="blockID-'.$block->id.'">' . $wrappedcontent . '</div>' : $wrappedcontent;
			},
			'edit_form' => function($block){
				return '<form id="modal_editor_form" method="post" data-editoraction="EDITOR--save_button" data-bid="' . $block->id . '"><textarea class="editor advanced-only" name="content">' . $block->content . '</textarea></form><div class="margin-top20 text-right"><button type="submit" class="hide formsubmitter btn btn-primary btn-lg">Save</button></div>';
			}
		];