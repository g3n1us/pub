<?php
return [
			'handle' => 'google_sheets',
			'editbuttons' => function($block){
				return editbuttons($block);
			},
			'default_content' => ['ID_FROM_SPREADSHEET', 'name_of_sheet'],
			'resolve_content' => function($block){
// 				$wrap = (empty($block->wrap->before) && empty($block->wrap->after)) ? $block->area->config->block_wrap : $block->wrap;
				$parts = explode('~', $block->content);
				$spreadsheetkey = $parts[0];
				$spreadsheettab = array_get($parts, 1, 0);
				$template = array_get($parts, 2);
				$url = 'gproxy://' . $spreadsheetkey;
				$sheets = lep_simple_data($url);
				$hashindex = $sheets->keys()->flip()->get($spreadsheettab, 0);
				$rows = ( $spreadsheettab && array_key_exists($spreadsheettab, $sheets->toArray()) ) ? $sheets[$spreadsheettab] : $sheets->first();
				$rows = collect($rows);
				// apply limit from config

				if(!$block->config) $block->config = collect(json_decode('{"limit":0}'));
				if($block->config->limit) $rows = $rows->take($block->config->limit);

				if(!$template) $output = $rows->toJson();
				else{
					$rows->transform(function($row, $key) use($template){
						foreach($row as $k => $v){
							$template = str_replace('{{'.$k.'}}', $v, $template);
						}
						return $template;
					});
					$output = $rows->implode("\n");
				}
				return $output;
/*
				$wrappedcontent = $wrap->before . "\n" . $output . "\n" . $wrap->after;
				
			    return ($block->editmode) ? '<div class="blockeditwrapper" style="position:relative;" data-bID="' . $block->id . '" data-container="body" data-content=\''.$block->editorbuttons.'\' id="blockID-'.$block->id.'">' . $wrappedcontent . '</div>' : $wrappedcontent;
*/

			},
			'edit_form' => function($block){
				$parts = explode('~', $block->content);
				$key = array_get($parts, 0);
				$tab = array_get($parts, 1);
				$template = array_get($parts, 2);
				$url = 'gproxy://' . $key;
				$sheets = lep_simple_data($url);
				$othertabs = $sheets->keys();
				$hashindex = $othertabs->flip()->get($tab, 0);
				$othertabs = $othertabs->transform(function($c){
					return '<option value="' . $c . '">';
				})->unique()->implode("\n");

				$otherformkeys = "";
				$otherformkeys = \App\Block::where('type', 'google_sheets')->pluck('content');
				$otherformkeys = $otherformkeys->transform(function($c){
					return '<option value="' . explode('~', $c)[0] . '">';
				})->unique()->implode("\n");
				if(is_null($block->config)) $block->config = json_decode('{"limit":9999999}');


				return '<form id="modal_editor_form" method="post" data-editoraction="EDITOR--save_button" data-bid="' . $block->id . '">
				<h4 class="text-center"><a onclick="sheetswindow=window.open(\'https://docs.google.com/spreadsheets/d/'.$key.'/edit#gid='.$hashindex.'\',\'sheetswindow\')" target="_blank">Edit this in Google Sheets</a></h4>
				<div class="form-group">
					<label>Spreadsheet Key</label>
					<input class="form-control" list="existingformkeys" name="content[0]" value="' . $key . '">
					<datalist id="existingformkeys">
						'.$otherformkeys.'
					</datalist>
				</div>
				<div class="form-group">
					<label>Spreadsheet Tab</label>
					<input class="form-control" list="existingformtabs" name="content[1]" value="' . $tab . '">
					<datalist id="existingformtabs">
						'.$othertabs.'
					</datalist>					
				</div>
				<div class="form-group">
					<label>Template</label>
					<textarea class="form-control editor XXadvanced-only" name="content[2]">'.$template.'</textarea>
				</div>
				<div class="form-inline">
					<fieldset class="form-group">
						<label>Limit (Max rows to return)</label>
						<input class="form-control" type="number" name="config[limit]" value="' . $block->config->limit . '">
						<!-- <button class="btn btn-default">Save</button> -->
					</fieldset>
				</div>
				</form>
				<div class="margin-top20 text-right"><button type="submit" class="hide formsubmitter btn btn-primary btn-lg">Save</button></div>';
			}
			
		];