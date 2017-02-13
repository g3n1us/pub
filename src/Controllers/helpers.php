<?php
// use Block;	

	
function webstatskey(){
	if(isset($_SERVER['AMW_WEBSTATS_KEY']))
		$key = $_SERVER['AMW_WEBSTATS_KEY'];
	else
		$key = 0;
	$time = time();
	return (int)$key * $time;
}
	
if(!function_exists('webstatsaddlinfo')){
	function webstatsaddlinfo(){
		$user_entity_id = array_get($_COOKIE, 'amw_webstats_user_entity_id', false);
		$visit_id = array_get($_COOKIE, 'amw_webstats_visit_id', false);
		$webstatskey = config('app.amw_webstats_key');
		
		if($visit_id) $addlinfo['visit_id'] = $visit_id;
		if($user_entity_id) $addlinfo['user_entity_id'] = $user_entity_id;
		$addlinfo['server'] = array_change_key_case(array_only($_SERVER, ['REMOTE_ADDR', 'HTTP_USER_AGENT', 'HTTP_HOST', 'REQUEST_URI', 'SERVER_NAME', 'REQUEST_TIME', 'HTTP_REFERER']), CASE_LOWER);
		$addlinfo['session'] = isset($_SESSION) ? $_SESSION : [];
		$addlinfo['cookie'] = isset($_COOKIE) ? $_COOKIE : [];
		$addlinfo['request'] = Request::all();
		return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $webstatskey, json_encode($addlinfo), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
	}
}
	
	
	function dir_exists($dir){
		if(count(Storage::allFiles($dir))) return true;
		else return false;
	}
	
	
	function copy_directory($from, $to){
		$from = str_finish(ltrim($from, "/"), "/");
		$to = str_finish(ltrim($to, "/"), "/");
		foreach(Storage::allFiles($from) as $fromfile){
			$tofile = str_replace($from, $to, $fromfile);
// 			echo("$fromfile || $tofile<br>");
			if(!Storage::exists($tofile)) Storage::copy($fromfile, $tofile);
		}
		return count(Storage::allFiles($from)) == count(Storage::allFiles($to));
	}
	


	
	// returns only letters and numbers
	
	function alphanumeric($string){
		return (string) preg_replace('/[^ \w]+/', '', $string);
	}
	
	function sanitize_page_name($path){
		return str_slug($path, "-");
	}
	
	
	function trim_path_prefix($request_path){
		$request_path = tidy_path($request_path);
		if(str_is('accounts/*/*', $request_path)){
			$parts = explode("/", trim(ltrim($request_path, "/")));
			array_forget($parts, '0');
			array_forget($parts, '1');
			
			return implode("/", $parts);
		}
		else return $request_path;
	}
	
	
	function tidy_path($path){
		$path = trim($path);
		$path = ltrim($path, "/");
		$path = rtrim($path, "/");
		while(str_contains($path, '..'))
			$path = str_replace("..", "", $path);
		if($path == "") return "/";
		else return $path;
	}





	
	function tidy_snake($string){
		return ucwords(str_replace("_", " ", $string));
	}
	//aliases
	if(!function_exists('title_case')){
		function title_case($string){
			return tidy_snake($string);
		}		
	}

	function tidy_title($string){
		return tidy_snake($string);
	}
		
		


// snippet: us states select fields
	function us_states_options(){
		return file_get_contents(dirname(__FILE__) . "/_snippets/us_states_select.php");
	}

		


if(!function_exists('mime')){
	function mime($path){
		$path = strtolower($path);
		if(ends_with($path, ".css")) $mime = "text/css";
		else if(ends_with($path, ".less")) $mime = "text/css";
		else if(ends_with($path, ".sass")) $mime = "text/css";
		else if(ends_with($path, ".scss")) $mime = "text/css";
		else if(ends_with($path, ".mp4")) $mime = "video/mp4";
		else if(ends_with($path, ".mov")) $mime = "video/quicktime";
		else if(ends_with($path, ".js")) $mime = "application/javascript";
		else if(ends_with($path, ".pdf")) $mime = "application/pdf";
		else if(ends_with($path, ".svg")) $mime = "image/svg+xml";
		else if(ends_with($path, ".jpg")) $mime = "image/jpeg";
		else if(ends_with($path, ".jpeg")) $mime = "image/jpeg";
		else if(ends_with($path, ".png")) $mime = "image/png";
		else if(ends_with($path, ".gif")) $mime = "image/gif";
		else if(ends_with($path, ".ico")) $mime = "image/vnd.microsoft.icon";
		else if(ends_with($path, ".json")) $mime = "application/json";
		else if(ends_with($path, ".ttf")) $mime = "application/x-font-truetype";
		else if(ends_with($path, ".woff")) $mime = "application/font-woff";
		else if(ends_with($path, ".woff2")) $mime = "application/font-woff2";
		else if(ends_with($path, ".otf")) $mime = "application/x-font-opentype";
		else if(ends_with($path, ".eot")) $mime = "application/vnd.ms-fontobject";
		else if(ends_with($path, ".md")) $mime = "text/markdown; charset=UTF-8";
		else if(ends_with($path, ".swf")) $mime = "application/x-shockwave-flash";
		else if(ends_with($path, ".php")) $mime = "text/plain";
			
		else{
			$mime = "text/html";
		}
		return $mime;
	}		
}

if(!function_exists('edit_mode')){
	function edit_mode(){
		return isset($_GET['editmode']) || isset($_GET['edit_mode']) || session('edit_mode') == true;
//		return (new \App\Services\Editor)->editmode;
	}
}

/*
if(!function_exists('show_area')){
	function show_area($article_or_page, $handle, $config = []){
		return $article_or_page->showArea($handle, $config);
	}
}
*/


if(!function_exists('show_area')){
	function show_area($handle, $article_or_page = null, array $options = []){
		$default_options = [
			'is_global'      => false,
			'area_wrap'   => ['before' => '', 'after' => ''],
			'block_wrap'  => ['before' => '', 'after' => ''],
			'area_classes'=> isset($options['area_classes']) ? $options['area_classes'] . ' ccm_area_wrapper' : 'ccm_area_wrapper',
		];
		$options = collect($default_options)->merge(collect(array_except($options, ['area_classes'])));
		$empty_content = $options->get('empty_content');
		$options = json_decode(json_encode($options->toArray()));
		if(is_null($article_or_page)) $options->is_global = true;
		if($options->is_global) $area = Area::where('handle', $handle)->first();
		else $area = $article_or_page->areas()->where('handle', $handle)->first();
// 		dump($area);
		$authok = auth()->check() || defined('INITIAL_SETUP_INCOMPLETE');

		if(!$area && $authok){
			$area = new Area;
			$area->handle = $handle;
			if($options->is_global) $area->save();
			else $article_or_page->areas()->save($area);
		}
		else if(!$area && $empty_content) {
			return $empty_content;

		}
		else if(!$area)
			return null;
			
		if(!$area->config && $authok){
			$area->config = $options;
			$area->save();
		}
		
		return $area->display();
	}
}


if(!function_exists('show_block')){
	function show_block($config, $standalone_handle = null){
		if(is_string($config)){
			$config = collect(['text1' => $config]);
		    $type = 'content';	
		}
		else{
			$config = collect($config);
		    $type = $config->get('type', 'content');	   			
		}
		
	    $newblock = new Block;
	    $newblock->handle = $type;
	    $newblock->config = ['isDefault' => true];
	    $defaultcontents = collect(config('concrete.block_types.'.$type.'.default_content', 'concrete.block_types.default.default_content'));
	    $properties = $defaultcontents->merge($config);
	    foreach($properties as $k => $v){
		    $newblock->{$k} = $v;
	    }
	    return new BlockMorpher($newblock, $standalone_handle);
	}
}


if(!function_exists('query')){
	function query($modelname, array $query = []){
		$classname = studly_case(strtolower(str_singular($modelname)));
// 		$model = "\App\\".$classname;
		$model = $classname;
		
		$public_models = ['Article', 'Block', 'Area', 'File', 'Author', 'Edition', 'Page'];
		abort_if(!auth()->check() && !in_array($classname, $public_models), 401, 'Unauthorized');

		$id =                  array_get($query, 'id');
		$html =                array_get($query, 'html');
		$offset =              array_get($query, 'offset', 0);
		$limit =               array_get($query, 'limit');
		$per_page =            array_get($query, 'per_page');
		$paginated =           array_get($query, 'paginate', true);		
		$pluck = $property =   array_get($query, 'pluck', array_get($query, 'property', false));
		
		if($id) {
			$m = $model::find($id);
			if($html && method_exists($m, 'display')) return $m->display();
			return $property ? $m->$property : $m;
		}
		else if(is_plural($modelname)){

			if($paginated || $per_page)
				$results =  $model::paginate($limit);
			else
				$results = $limit ? $model::skip($offset)->take($limit)->get() : $model::get();
				
			if($html && method_exists($results[0], 'display')){
				$return = [];
				foreach($results as $result)
					$return[] = $result->display();
				return implode("\n", $return);
			}
				
			return $pluck ? $results->pluck($pluck) : $results;
		}
		else {
			if($html && method_exists($model::first(), 'display'))
				return $model::first()->display();

			return $model::first();
		}
			
	}	
}


if(!function_exists('get_all')){
	function get_all($class){
// 		$class = "\App\\$class";
		$class = "$class";
		return $class::get();
	}
}

if(!function_exists('user_id_string')){
	function user_id_string(){
		$user = auth()->user();
		return !$user ?: $user->id;		
	}
}
// 'article_'.$value.''.auth()->check()
if(!function_exists('cache_key')){
	function cache_key($prefix, $model_or_id, $authcheck = null){
		if(is_string($model_or_id) || is_integer($model_or_id))
			$id = $model_or_id;
		else{
			$id = $model_or_id->getKey();
// 			dd($model_or_id->getKey());
		}
		$authcheck = is_null($authcheck) ? auth()->check() : $authcheck;
		$authstring = $authcheck ? '_auth' : '';
		return "$prefix$id$authstring";
	}
}


if(!function_exists('is_plural')){
	function is_plural($string){
		return str_plural($string) == $string;
	}
}
if(!function_exists('array_to_object')){
	function array_to_object($array) {
	    return (object) $array;
	}
}


if(!function_exists('editbuttons')){
	function editbuttons($block){
	    $editorbuttons = [];
	    $editorbuttons[] = '<span data-editoraction="EDITOR--delete_block" data-bID="' . $block->id . '" class="tiny-edit-button tiny-delete-button">Delete Block</span>';
	    $editorbuttons[] = '<span data-XXtarget="#blockID-'.$block->id.'" data-editoraction="EDITOR--edit_button" data-bID="' . $block->id . '" class="tiny-edit-button EDITOR--edit_button">Edit Block</span>';
		
		return implode('', $editorbuttons);
	}		
}
	
if(!function_exists('block_config')){
	function block_config($type, $prop = null){
		$block_types = [
			'content'  =>         include dirname(__DIR__)."/block_helpers/blocks/content.php",

			'html'  =>            include dirname(__DIR__)."/block_helpers/blocks/html.php",

			'page_list'  =>       include dirname(__DIR__)."/block_helpers/blocks/page_list.php",

			'default'  =>         include dirname(__DIR__)."/block_helpers/blocks/default.php",

			'google_sheets' =>    include dirname(__DIR__)."/block_helpers/blocks/google_sheets.php",

			'data_api' =>         include dirname(__DIR__)."/block_helpers/blocks/data_api.php",

			'article_list'  =>    include dirname(__DIR__)."/block_helpers/blocks/article_list.php",

			'lead_story'  =>      include dirname(__DIR__)."/block_helpers/blocks/lead_story.php",			
		];	
	    $defaultcontents = config('pub.block_types.'.$type.'.default_content', 'pub.block_types.default.default_content');
		
		if($prop)
			return array_get($block_types[$type], $prop, array_get($block_types, "default.$prop"));	
		else	
			return $block_types[$type];
	}
}