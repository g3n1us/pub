<?php
return [
			'handle' => 'data_api',
			'editbuttons' => function($block){
				return editbuttons($block);
			},
			'default_content' => ['/theme/data', 'addl_data'],
			'resolve_content' => function($block){
// 				$wrap = (empty($block->wrap->before) && empty($block->wrap->after)) ? $block->area->config->block_wrap : $block->wrap;
				$template = object_get($block->config, 'template');
				if(starts_with('http', $block->content)) $url = $block->content;
				else  $url = url($content);

				$useroptionsfromquery = [];
				$query = array_get(parse_url($url), 'query', null);
				parse_str($query, $useroptionsfromquery);
				$data = lep_simple_data($url, $useroptionsfromquery);
				$output = simple_template($data, $template);
				return $output;
/*
				$wrappedcontent = $wrap->before . "\n" . $output . "\n" . $wrap->after;
				
			    return ($block->editmode) ? '<div class="blockeditwrapper" style="position:relative;" data-bID="' . $block->id . '" data-container="body" data-content=\''.$block->editorbuttons.'\' id="blockID-'.$block->id.'">' . $wrappedcontent . '</div>' : $wrappedcontent;
*/
				
			},
			'edit_form' => function($block){
				$template = object_get($block->config, 'template');
				$limit = object_get($block->config, 'limit');
				return '<form id="modal_editor_form" method="post" data-editoraction="EDITOR--save_button" data-bid="' . $block->id . '">
				<div class="form-group">
				</div>
				<div class="form-group">
					<label>URL to API/Data</label>
					<input class="form-control" name="content" value="' . $block->content . '">
				</div>
				<div class="form-group">
					<label>Template</label>
					<textarea class="form-control editor XXadvanced-only" name="config[template]">'.$template.'</textarea>
				</div>
				<div class="form-inline">
					<fieldset class="form-group">
						<label>Limit (Max rows to return)</label>
						<input class="form-control" type="number" name="config[limit]" value="' . $limit . '">
						<!-- <button class="btn btn-default">Save</button> -->
					</fieldset>
				</div>
				</form>
				<div class="margin-top20 text-right"><button type="submit" class="hide formsubmitter btn btn-primary btn-lg">Save</button></div>';
			}
		];