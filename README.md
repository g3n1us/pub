# Pub
A CMS for publications. Built on Laravel, installable as a Composer package.

## Installation
Install Laravel as normal. 
https://laravel.com/docs/5.4/installation

It is best to install Pub on top of a fresh Laravel installation. The Pub installer will set up database connections, AWS resources, Google login and Dropbox integration.

Install via Composer:
`composer require g3n1us/pub`

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

	php composer pub

This will walk you through setup.

Enjoy :)
