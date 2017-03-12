<?php
namespace G3n1us\Pub\Providers;
	
// namespace G3n1us\Pub\Providers;

use Illuminate\Support\Facades\Route;
// use Illuminate\Routing\RoutingServiceProvider as ServiceProvider;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
// use Route;
use Cache;
use Config;
use Illuminate\Http\Request;
use Storage;

use Article;
use Page;
use Brand;
use Block;
use BlockMorpher;
use PageAlias;

use Illuminate\Routing\Router;


class PubRouteProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'G3n1us\Pub\Controllers';


    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
	    
        Route::pattern('domain', '(.*)');

        Route::pattern('page', '^[^.]*$');
        
        // Domain return a Brand
		Route::bind('domain', function ($value) {
			$brand = Brand::where('handle', $brandstring)->firstOrFail();
		    return $brand;
		});        

		Route::bind('block', function($value) {
			$block = Block::findOrFail($value);
		    return new BlockMorpher($block);
		});   
		
		Route::bind('page_or_article', function($value, $route) {
			$page = Page::whereId($value);
			if(auth()->check())
				return $page ?: Article::withoutGlobalScopes(['is_approved'])->whereId($value);
			else	
				return $page ?: Article::whereId($value);
		});
		
		Route::bind('page', function($value, $route) {
			$pathstring = $route->page;
			if(is_numeric($pathstring)) return Page::findOrFail($pathstring);
			$definitive_page = Page::where('url', $pathstring)->first();
			if($definitive_page) 
				return $definitive_page;
			$alias = false;	
			foreach(PageAlias::get() as $testpage){
				$string = str_replace('/', '\/', $testpage->alias);
				$string = str_replace('\\/', '\/', $string);
				$string = "/$string/";
				$matched = @preg_match($string, '/'.$route->page, $matches);
				if($matched && $testpage->page){
					$alias = $testpage->page;
					break;
				}
			}
			if($alias) return $alias;
			else abort(404);
		});   
		
		$handleArticleOrSlug = function ($value, $route){
			$article_version = $this->app->request->input('article_version');
			if($article_version){
				$client = new \Aws\S3\S3Client([
				    'version' => 'latest',
				    'region'  => 'us-east-1'
				]);
				$version = $client->getObject([
					'Bucket' => config('pub.versions_bucket'),
					'Key' => "page_versions/$value",
					'VersionId' => $article_version,
				]);
				$article = unserialize($version['Body']);
				return $article;
			}
			else{
				return Cache::remember(cache_key('article_', $value)[0], 999, function() use($value){
					if(auth()->check())
						$article =  is_numeric($value) ? Article::withoutGlobalScopes(['is_approved'])->find($value) : Article::withoutGlobalScopes(['is_approved'])->where('slug', $value)->first(); 					
					else
						$article =  is_numeric($value) ? Article::find($value) : Article::where('slug', $value)->first(); 					
					
					if($article) $article->load('tags', 'photo', 'files', 'content', 'authors');
					else abort(404);
					return $article;
				});
			}
		};

		Route::bind('article', $handleArticleOrSlug);
		Route::bind('slug', $handleArticleOrSlug);	
		
       parent::boot();
		    
    }

    public function map(){
        Route::group([
            'middleware' => 'web',
            'namespace' => $this->namespace,
        ], function ($router) {
            require dirname(__DIR__) . '/Controllers/routes.php';
        });	    
    }


}
