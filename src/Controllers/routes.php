<?php
// namespace G3n1us\Pub\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use G3n1us\Pub\Services\WordProcessor;
use G3n1us\Pub\Models\Article;
		

// Route::group(['domain' => '{domain}'], function () {
Route::group(['middleware' => ['web']], function () {
    // your routes here
	
	Route::get('oauth/{provider}', 'Auth\PubLoginController@redirectToProvider');
	Route::get('oauth/{provider}/callback', 'Auth\PubLoginController@handleProviderCallback');
		
	Route::get('article/{article}/files', function(Article $article){
		return  $article->files;
	});		
	
	Route::get('test', function(){
		ddd(new WordProcessor);
		
		// Storage::disk('dropbox')->put('sdfsdf.txt', 'sdfsdfsd.txt');
	});
	
	Route::post('word/{article?}', function($article = null){
		$word = new WordProcessor($article);
		return $word->write();
	})->middleware(['auth']);
	
	Route::get('dashboard', 'DashboardController@getIndex');
	Route::get('dashboard/pages', 'DashboardController@getPages');
	Route::get('dashboard/users', 'DashboardController@getUsers');
	Route::get('dashboard/users/{user}', 'DashboardController@usermod');
	Route::post('dashboard/users/{user}', 'DashboardController@usersave');
	Route::delete('dashboard/users/{user}', 'DashboardController@userdelete');
	Route::get('dashboard/articles', 'DashboardController@getArticles');
	
// 	Route::get('dashboard/cklist/{tab?}', 'DashboardController@getPageList');
	
	// Route::get('/', 'PublicController@index');
	
// 	Route::get('author/{author?}', 'PublicController@showAuthor');
	Route::resource('author', 'AuthorController');	
	
	Route::get('search/{term?}', 'PublicController@showSearch');

	Route::post('filesave/{file}/{article?}', 'PublicController@fileSaveAttributes')->middleware(['auth']);
	
	Route::delete('filesave/{file}/{article?}', 'PublicController@deleteFile')->middleware(['auth']);

	Route::get('tag/{tag?}', 'PublicController@showTag');

	Route::get('by-date/{date?}/{range?}/{order?}', 'PublicController@byDate');
		
// 	Route::post('submit-form', 'FormController@store');
	
// 	Route::delete('ajax/files', 'HomeController@deleteFile');	
	
//	Route::get('ajax/article-files/{article}', function($brand, $article){
//		return $article ? $article->files : null;
//	});
	
	Route::get('oembed/{article?}', function(Request $request, $article = null){
		if(is_null($article)) {
			$article = Article::findOrFail($request->article_id);
		}
		$author = $article->authors->first();
		
		$o = [];
		$o['success'] = "true";
		$o["version"] = "1.0";	
		$o["type"] = "rich";
		$o["width"] = 240;
		$o["height"] = 160;
		$o["title"] = $article->title;
		$o["url"] = $article->url;
		$o["author_name"] = $author->displayname;
		$o["author_url"] = '/author/'.$o["author_name"];
		$o["provider_name"] = "Washington Examiner";
		$o["provider_url"] = url('');
		$o['html'] = '<div>Hello World</div>';
		
		return $o;
	});
	
	
	Route::get('ajax/authors/{author?}', function($brand, \App\Author $author = null){
		// if($author->id) return $author->id);
		$authors = \App\Author::get();
		foreach($authors as $a){
			if(!$a->handle){
				if($a->displayname)
					$a->handle = str_slug($a->displayname);
				else if($a->firstname && $a->lastname)
					$a->handle = str_slug($a->firstname . ' ' . $a->lastname);
				else if($a->title)
					$a->handle = str_slug($a->title);
				$a->save();
			}
		}
		return $authors;
	});
	
	// Usage: modelname is the snake cased version of the model. If singular, will display either the first model or the one specified by id.
	// If plural, a paginated list of items will be output. 
	// If html is specified, the __toString method will explicitly be called, returning any overloaded verion in the model, usually custom html output.
	
//	$_GET parameters for plural form only:  
//	paginate - 0 results in no pagination, default = 1
//	per_page = results per page, disables pagination, integer default is pagination default, 15
//	offset = offset, return results starting at this offset
//	limit - limit to return, disables pagination
//	pluck/property - pluck a value from the returned objects
//	$_GET parameters for singular form only:  
//	html - returns the model's overloaded __toString method instead of the default JSON representation, also an URL parameter
	Route::get('ajax/{modelname}/{id?}/{html?}', function(Request $request, $modelname, $id = null, $html = false){
		// dd(query('pages', ['html' => 1, 'limit' => 2]));
		$classname = studly_case(strtolower(str_singular($modelname)));
		$model = $classname;
		
		$public_models = ['Article', 'Block', 'Area', 'File', 'Author', 'Edition', 'Page', 'Tag'];
		abort_if(!auth()->check() && !in_array($classname, $public_models), 401, 'Unauthorized');

		$html = $request->input('html', $html);
		$offset = $request->input('offset', 0);
		$limit = $request->input('limit');
		$per_page = $request->input('per_page');
		$template = $request->input('template');
		$paginated = $request->input('paginate', true);		
		$pluck = $property = $request->input('pluck', $request->input('property', false));		
		
		if($id) {
			$m = $model::find($id);
			if($html && method_exists($m, 'display')) return $m->display($template);
			return $property ? $m->$property : $m;
		}
		else if(is_plural($modelname)){

			if($paginated || $per_page)
				$results =  $model::paginate($per_page);
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
			
	}); //->middleware(['auth']);
	
	
	Route::get('svg/{template}/{imgdata?}/{brand?}', 'PublicController@dynamicSvg')->middleware(['cacheable']);
	
	Route::get('files/{anything}/{filename}', 'PublicController@imagefallback')->where(['filename' => '^.*\.(?!jpg$|png$|jpeg$)[^.]+$'])->middleware(['cacheable']);
	
	Route::get('article_versions/{article_id}', function($article_id){
		Cache::forget('article_versions_'.$article_id);
		return Cache::store('file')->remember('article_versions_'.$article_id, 99999, function() use($article_id){
			$client = new Aws\S3\S3Client([
			    'version' => 'latest',
			    'region'  => 'us-east-1'
			]);
			$versions = $client->ListObjectVersions([
				'Bucket' => 'mediadc-file-uploads',
				'Prefix' => "page_versions/$article_id"
			]);
			$versions = collect($versions['Versions'])->transform(function($v, $i) use($client, $article_id){
				$newv = collect($v);
				$last_mod = $newv->get('LastModified', '1999-01-01');
				$last_mod = method_exists($last_mod, '__toString') ? $last_mod->__toString() : $last_mod;
				$newv->put('LastModified', new \Carbon\Carbon($last_mod));
				$newv->put('date_diff', $newv['LastModified']->diffForHumans());
				
				$version = $client->getObject([
					'Bucket' => 'mediadc-file-uploads',
					'Key' => "page_versions/$article_id",
					'VersionId' => $newv['VersionId'],
				]);
				$article = unserialize($version['Body']);
				if(!$article->getOriginal('pub_date') || $article->getOriginal('pub_date') == "0000-00-00 00:00:00") {
					$article->makeHidden('pub_date');
				}
				if(!$article->getOriginal('updated_at') || $article->getOriginal('updated_at') == "0000-00-00 00:00:00") {
					$article->makeHidden('updated_at');
				}
				if(!$article->getOriginal('created_at') || $article->getOriginal('created_at') == "0000-00-00 00:00:00") {
					$article->makeHidden('created_at');
				}
// 				dump($article);
				$newv->put('article', $article);
				$newv->put('index', ++$i);
				
				
				return $newv;
			});
// 			dd($versions);
			return $versions;
		});		
	});

	Route::resource('article', 'ArticleController');

    Route::model('file', File::class);

// 	Route::get('article/{article}', 'ArticleController@show');

	Route::resource('block', 'BlockController');
	
	Route::resource('filemanager', 'FileController', ['parameters' => [
	    'filemanager' => 'file'
	]]);
		
	Route::get('filemanager/tab/{tab}', 'FileController@index');
	
	Route::put('/dashboard/area/{area}', 'DashboardController@putAreaAdd');
	
	Route::post('/dashboard/area/{area}', 'DashboardController@postMoveAreaBlocks');
	
	Route::get('/dashboard/area/{area}/{html?}', function(Area $area, $html = false){
		return $html ? $area->display() : $area;
	});
	
	Route::get('/dashboard/area-ajax/{page_or_article}/{area}', function($page_or_article, $area_handle){
		echo $page_or_article->showArea($area_handle);
	});
	
	Route::resource('page', 'PageController');
	
	Route::get('{page}', 'PageController@show');
	// Other image files are handles by Image class cache routing
// 	Route::get('{slug}', 'ArticleController@article');
// });
});	
