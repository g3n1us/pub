<?php
	
return [
	
	'users_model' => \G3n1us\Pub\Models\User::class,
	
	'filesystem' => env('PUB_FILESYSTEM', 's3'),
	
	'versions_bucket' => env('VERSIONS_BUCKET'),
	
	's3_bucket' => env('S3_BUCKET'),
	
	'iframely_key'  => env('IFRAMELY_API_KEY'),
	
	'theme'         => env('THEME'),
	
	'google_auth' => [
		'client_id' => env('GOOGLE_CLIENT_ID'),
		'client_secret' => env('GOOGLE_CLIENT_SECRET'),
		'redirect' => env('GOOGLE_CLIENT_REDIRECT_URL'),
	],
	
	'dropbox_filesystem'  => [
        'driver'           => 'dropbox',
		'accessToken'      => env('DROPBOX_ACCESS_TOKEN'),
        'clientIdentifier' => env('DROPBOX_APP_SECRET'), //app_secret
        'client_id'        => env('DROPBOX_CLIENT_ID'),
        'app_secret'       => env('DROPBOX_APP_SECRET'),
    ],

];
