<?php
	
return [
	
	'users_model' => \G3n1us\Pub\Models\User::class,
	
	'filesystem' => env('PUB_FILESYSTEM'),
	
	'dropbox_filesystem'  => [
	        'driver'           => 'dropbox',
			'accessToken'      => env('DROPBOX_ACCESS_TOKEN'),
	        'clientIdentifier' => env('DROPBOX_APP_SECRET'), //app_secret
	        'client_id'        => env('DROPBOX_CLIENT_ID'),
	        'app_secret'       => env('DROPBOX_APP_SECRET'),
    ],
    
    'versions_bucket'   => env('VERSIONS_BUCKET'),

];