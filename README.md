# Pub :beer:
An opiniated, Laravel based CMS for publications with deep AWS integration, installable via Composer

## Getting Started
The best way to get started using Pub is by setting up a dedicated EC2 instance. An instance profile should be set up with administrator priveleges. This way, the installer can set up an S3 bucket and other AWS resources for use with the application. After setup is complete, the extra priveleges that are not needed of the profile should be removed. 
> IMPORTANT! The instance should not be made publicly accessible at any time during setup and be sure to remove the extra priveleges from the instance profile

## Installation
Install Laravel as normal. 
https://laravel.com/docs/installation

It is best to install Pub on top of a fresh Laravel installation. The Pub CLI installer will set up database connections, AWS resources, Google login and Dropbox integration.

After Laravel is installed, setup the basic authentication scaffolding by running:
~~~~
php artisan make:auth
php artisan migrate
~~~~
> Note: if you get the error: `SQLSTATE[42000]: Syntax error or access violation: 1071 Specified key was too long; max key length is 767 bytes` Add the following to the `boot()` method of `AppServiceProvider`: `Schema::defaultStringLength(191);`

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
	
If you'd like to send mail out of the box, you'll need to either set up an smtp and add it's credentials to the .env file. Or you can change

	MAIL_DRIVER=smtp
	
to 

	MAIL_DRIVER=sendmail
	
to send email using the built in sendmail executable.

> Note: Don't use this for production! You should use a dedicated mail option. Check out `config/mail.php` to set this and other mail related options.

Cheers! :beers: :beers:
