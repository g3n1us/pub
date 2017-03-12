# Pub :beer:
An opiniated, Laravel based CMS for publications with deep AWS integration, installable via Composer Edit

## Installation
Install Laravel as normal. 
https://laravel.com/docs/installation

It is best to install Pub on top of a fresh Laravel installation. The Pub CLI installer will set up database connections, AWS resources, Google login and Dropbox integration.

After Laravel is installed, setup the basic authentication scaffolding by running:
~~~~
php artisan make auth
php artisan migrate
~~~~


Install via Composer:

	composer require g3n1us/pub
	
> Note: you may have to include `"minimum-stability": "dev"` in your composer.json while Pub is still in development.

After this you must add the Pub service providers array in `config/app.php`.

    'providers' => [

          /*
           * Laravel Framework Service Providers...
           */
          Illuminate\Auth\AuthServiceProvider::class,
          Illuminate\Broadcasting\BroadcastServiceProvider::class,
		  // ...    

          G3n1us\Pub\Providers\PubProvider::class,        
          G3n1us\Pub\Providers\PubRouteProvider::class,        
          G3n1us\Pub\Providers\DropboxServiceProvider::class,
      ],

Once this is done, you can run setup via our CLI Artisan command:

	php artisan pub

This will walk you through setup.

## Post Setup

After setup is complete, publish your assets from the Pub package to the site:

	php artisan vendor:publish
	composer dump autoload

### There are some other optional steps you can take:

Seed the site with placeholder content:

	php artisan db:seed --class=PubDatabaseSeeder

Cheers! :beers:
