<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use G3n1us\Pub\Models\User;
use G3n1us\Pub\Models\File;
use G3n1us\Pub\Models\Tag;
use G3n1us\Pub\Models\Author;
use G3n1us\Pub\Models\Article;
use G3n1us\Pub\Models\ArticleContent;


/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(G3n1us\Pub\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'username' => snake_case($faker->name),
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(Article::class, function (Faker\Generator $faker) {
    static $password;
	$title = $faker->realText(50);
    return [
        'title' => $title,
        'slug' => str_slug($title),
        'short_title' => str_limit($title, 50),
        'summary' => $faker->realText(300),
        'pub_date' => \Carbon\Carbon::now(),
        'approved' => 1,
    ];
});


$factory->define(Author::class, function (Faker\Generator $faker) {
	$firstname = $faker->firstName;
	$lastname = $faker->lastName;
	$tmppath = $faker->image("/tmp", 800, 800, 'people');
    $file = new Illuminate\Http\UploadedFile($tmppath, "og.jpg");
	$path =  $file->store('originals', config('pub.filesystem'));
	$url = Storage::disk(config('pub.filesystem'))->url($path);
    return [
        'title' => $faker->jobTitle,
        'lastname' => $lastname,
        'firstname' => $firstname,
        'department' => $faker->catchPhrase,
        'email' => $faker->email,
        'bio' => '<p>' . implode('</p><p>', $faker->paragraphs(rand(1,5))) . '</p>',
        'displayname' => "$firstname $lastname",
        'handle' => str_slug("$firstname $lastname"),
        'mugshot' => $url,
    ];
});


$factory->define(ArticleContent::class, function (Faker\Generator $faker) {

    return [
        'body' => '<p>' . implode('</p><p>', $faker->paragraphs(rand(5,15))) . '</p>',

    ];
});



$factory->define(Tag::class, function (Faker\Generator $faker) {
	$tags = [];
	$tags[] = $faker->unique()->company;	
	$tags[] = $faker->unique()->jobTitle;	
	$tqgs[] = $faker->unique()->catchPhrase;
	$tag = collect($tags)->random();
    return [
        'handle' => str_slug($tag),
        'name' => $tag,
		'metadata' => [],
    ];
});


$factory->define(File::class, function (Faker\Generator $faker) {
	$category = collect(['people', 'city', 'transport', 'technics', 'fashion', 'nature', 'food'])->random();
	$tmppath = $faker->image("/tmp", 1600, 1000, $category);
    $file = new Illuminate\Http\UploadedFile($tmppath, "og.jpg");
	$path =  $file->store('originals', config('pub.filesystem'));
    
    return [
        'filename' => basename($path),
        'mime_type' => mime($path),
        'extension' => $file->extension(),
        'bucket' => config('pub.s3_bucket'),
        'metadata' => [],
    ];
});


