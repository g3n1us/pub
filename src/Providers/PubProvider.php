<?php

namespace G3n1us\Pub\Providers;

if(file_exists(dirname(dirname(__DIR__)).'/vendor/autoload.php'))
	require dirname(dirname(__DIR__)).'/vendor/autoload.php';

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Relations\Relation;
// use Illuminate\Support\Facades\View;


// use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use View;
use User;
use Cache;
use Illuminate\Http\Request;
use DB;

use Article;
use File;
use Storage;
use Workflow;
use Brand;
use UserGroup;

use G3n1us\Pub\Install;

// define('BRAND_SLUG', 'wex');	
// define('BRAND_HANDLE', 'wex');	

class PubProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     * To install, run `php -r "require('vendor/autoload.php'); new \G3n1us\Pub\Install;"` from the  project's base directory
     * @return void
     */
    public function boot()
    {
	    if ($this->app->runningInConsole()) {
	        $this->commands([
	            Install::class,
	        ]);
	    }	       	    

       //  if(!config('pub.setup_complete')) return;
	    $theme = env('THEME');
	    if($theme)
    	    $this->loadViewsFrom(resource_path("/views/$theme"), 'pub');

	    $this->loadViewsFrom(dirname(__DIR__).'/resources/views', 'pub');
	    
	    if(config('pub.users_model'))
	        config(['auth.providers.users.model' => config('pub.users_model')]);
	        
        
        if(config('pub.dropbox_filesystem'))
	        config(['filesystems.disks.dropbox' => config('pub.dropbox_filesystem')]);
	        
	        
        if(config('pub.google_auth.client_id'))
            config(['services.google' => config('pub.google_auth')]);
         

         if(config('pub.s3_bucket'))   
            config(['filesystems.disks.s3.bucket' => config('pub.s3_bucket')]);
	        
		try {
		    DB::connection()->getPdo();
		    
	        View::share('brand', Brand::first());

	        View::share('theme', $theme);
		    
		} catch (\Exception $e) {

		}	    	        
	        
        
        
		Relation::morphMap([
		    'article' => Article::class,
		    'file' => File::class,
		]); 
		
		
		Article::saving(function($article, $article2 = null){
			$new_assigned_user_id = (int)request()->input('workflow.assigned_user');
			$prev_assigned_user_id = (int)object_get($article, 'workflow.assigned_user');

			if($new_assigned_user_id && $prev_assigned_user_id != $new_assigned_user_id){
				$new_assigned_user = User::find($new_assigned_user_id);
				// a user assignmnet has changed - send email here!
				$Name = "Pub Site Admin"; //senders name 
				$email = "noreply@development.jmbdc.com"; //senders e-mail adress 
				$recipient = $new_assigned_user->email; //recipient 
				$mail_body = 'You have been assigned a task. Go to '. url('/article/'.$article->id.'/edit'); //mail body 
				$subject = "Workflow Assignment"; //subject 
				$header = "From: ". $Name . " <" . $email . ">\r\n"; //optional headerfields 
				
				mail($recipient, $subject, $mail_body, $header); //mail command :)
			}
		});
		
		User::saved(function($user){
			if(User::count() == 1){
				$user->groups()->save(new UserGroup(['group' => 'admin']));
			}

		});
		
        Article::saved(function ($article) {
	        if(config('filesystems.disks.s3.bucket')){
		        $article->load('tags', 'photo', 'files', 'content', 'authors'); 
				Storage::disk('s3')->put('page_versions/'.$article->id, serialize($article));		        
	        }
// 			clear caches
			$article->flush_cache();
        });	    

        
	    $this->loadMigrationsFrom(dirname(__DIR__).'/database/migrations');        
        
        
	    $this->publishes([
	        dirname(__DIR__).'/config/pub.php' => config_path('pub.php'),
	        dirname(__DIR__).'/config/default_groups.php' => config_path('groups.php'),
	    ], 'config');        
		    	    
	    $this->publishes([
	        dirname(__DIR__).'/database/factories/PubModelFactory.php' => database_path('factories/PubModelFactory.php'),
	        dirname(__DIR__).'/database/seeds/PubDatabaseSeeder.php' => database_path('seeds/PubDatabaseSeeder.php'),
	    ], 'database');        
		    	    
	    
	    $this->publishes([
	        dirname(__DIR__).'/resources/assets/dist' => public_path('vendor/pub'),
	    ], 'public');
	    
	    
		// Rethink this with regard to how themes are made
	    $this->publishes([
	        dirname(__DIR__).'/resources/views/default_theme' => resource_path('views/vendor/pub/default_theme'),
	    ], 'views');	 
	    
	    
		    
    }
    
    

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
	    
	    $this->mergeConfigFrom(dirname(__DIR__).'/config/pub.php', 'pub');        	    
	    
	    $this->mergeConfigFrom(dirname(__DIR__).'/config/groups.php', 'groups');        	    
	    
        View::addExtension('svg','blade');
        
		$loader = \Illuminate\Foundation\AliasLoader::getInstance();
	    $loader->alias('Socialite', \Laravel\Socialite\Facades\Socialite::class);		
	    
	    $loader->alias('Area', \G3n1us\Pub\Models\Area::class);		
	    $loader->alias('Article', \G3n1us\Pub\Models\Article::class);		
	    $loader->alias('ArticleContent', \G3n1us\Pub\Models\ArticleContent::class);		
	    $loader->alias('Author', \G3n1us\Pub\Models\Author::class);		
	    $loader->alias('Block', \G3n1us\Pub\Models\Block::class);		
	    $loader->alias('Brand', \G3n1us\Pub\Models\Brand::class);		
	    $loader->alias('Edition', \G3n1us\Pub\Models\Edition::class);		
	    $loader->alias('File', \G3n1us\Pub\Models\File::class);		
	    $loader->alias('Form', \G3n1us\Pub\Models\Form::class);		
	    $loader->alias('Page', \G3n1us\Pub\Models\Page::class);		
	    $loader->alias('PageAlias', \G3n1us\Pub\Models\PageAlias::class);
	    $loader->alias('Photo', \G3n1us\Pub\Models\Photo::class);		
	    $loader->alias('SocialAccount', \G3n1us\Pub\Models\SocialAccount::class);		
	    $loader->alias('Svg', \G3n1us\Pub\Models\Svg::class);		
	    $loader->alias('Tag', \G3n1us\Pub\Models\Tag::class);		
	    $loader->alias('User', \G3n1us\Pub\Models\User::class);		
	    $loader->alias('UserGroup', \G3n1us\Pub\Models\UserGroup::class);		
	    $loader->alias('Workflow', \G3n1us\Pub\Models\Workflow::class);		

	    $loader->alias('BlockMorpher', \G3n1us\Pub\Services\BlockMorpher::class);	
	    $loader->alias('SmartyS3Resource', \G3n1us\Pub\Services\SmartyS3Resource::class);	
	    $loader->alias('SmartyView', \G3n1us\Pub\Services\SmartyView::class);	
	    $loader->alias('Editor', \G3n1us\Pub\Services\Editor::class);	
	    $loader->alias('S3Versions', \G3n1us\Pub\Services\S3Versions::class);	
	    $loader->alias('SocialAccountService', \G3n1us\Pub\Services\SocialAccountService::class);	
	    $loader->alias('WordProcessor', \G3n1us\Pub\Services\WordProcessor::class);	
	    $loader->alias('DropboxServiceProvider', \G3n1us\Pub\Services\DropboxServiceProvider::class);	
	    $loader->alias('ArticlePolicy', \G3n1us\Pub\Policies\ArticlePolicy::class);	
	    
	    $loader->alias('SavePageRequest', \G3n1us\Pub\Requests\SavePageRequest::class);	
	    $loader->alias('SaveUserRequest', \G3n1us\Pub\Requests\SaveUserRequest::class);	
	    $loader->alias('DeleteUserRequest', \G3n1us\Pub\Requests\DeleteUserRequest::class);	
	    
	    
    }
}
