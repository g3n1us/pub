<?php
return [
			'handle' => 'article-list',
			'editbuttons' => function($block){
				return editbuttons($block);
			},
			'default_content' => ['field1' => 'News', 'field2' => 5, 'field3' => 0],
			'resolve_content' => function($block){

				$tag = rawurlencode($block->field1);
				$limit = $block->field2 ?: 5;
				$show_pagination = $block->field3;
			    $foundtag = Tag::where('handle', $tag)->orWhere('name', $tag)->first();
			    if(!$foundtag) $articles = [];
			    else if($show_pagination)
					$articles = $foundtag->articles()->paginate($limit);
				else 
					$articles = $foundtag->articles()->limit($limit)->get();
			    $data['articles'] = $articles;
// 				dd((string) view('pub::parts.raw_article_list', $data));
				return view('pub::parts.raw_article_list', $data)->__toString();

// 				return SmartyView::fetch('parts/raw_article_list.tpl', $data);
			},
			'edit_form' => function($block){
				$show_pagination = $block->field3;				
				$checked = $show_pagination ? 'checked' : '';
				$pagination_checkbox = "<br><input type='hidden' name='model[field3]' value='0'><label for='field3'>Show Pagination? <input type='checkbox' id='field3' name='model[field3]' $checked></label>";
				return '<form id="modal_editor_form" method="post" data-editoraction="EDITOR--save_button" data-bid=' . $block->id . '><label for="field1">Tag to List</label><input id="field1" class="form-control" name="model[field1]" value="' . $block->field1 . '"><label for="field1">Articles to Show</label><input id="field2" class="form-control" name="model[field2]" value="' . $block->field2 . '">'.$pagination_checkbox.'</form><div class="mt2 text-right"><button type="submit" class="hide formsubmitter btn btn-primary btn-lg">Save</button></div>';
			}
		];