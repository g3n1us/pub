<?php
	
if(!function_exists('editbuttons')){
	function editbuttons($block){
	    $editorbuttons = [];
	    $editorbuttons[] = '<span data-editoraction="EDITOR--delete_block" data-bID="' . $block->id . '" class="tiny-edit-button tiny-delete-button">Delete Block</span>';
	    $editorbuttons[] = '<span data-XXtarget="#blockID-'.$block->id.'" data-editoraction="EDITOR--edit_button" data-bID="' . $block->id . '" class="tiny-edit-button EDITOR--edit_button">Edit Block</span>';
		
		return implode('', $editorbuttons);
	}		
}
	
return [
	
	'users_model' => \G3n1us\Pub\Models\User::class,
	
	'dropbox_filesystem'  => [
	        'driver'           => 'dropbox',
			'accessToken'      => env('DROPBOX_ACCESS_TOKEN'),
	        'clientIdentifier' => env('DROPBOX_APP_SECRET'), //app_secret
	        'client_id'        => env('DROPBOX_CLIENT_ID'),
	        'app_secret'       => env('DROPBOX_APP_SECRET'),
    ],
    
    'versions_bucket'   => env('VERSIONS_BUCKET'),
	
/*
	'block_types' => [
		'content'  => include __DIR__."/blocks/content.php",
		
		'html'  => include __DIR__."/blocks/html.php",
		
		'page_list'  => include __DIR__."/blocks/page_list.php",
		
		'default'  => include __DIR__."/blocks/default.php",
		
		'google_sheets' => include __DIR__."/blocks/google_sheets.php",
		
		'data_api' => include __DIR__."/blocks/data_api.php",
		
		'article_list'  => include __DIR__."/blocks/article_list.php",
		
		'lead_story'  => include __DIR__."/blocks/lead_story.php",
		
	]
*/
];