<?php

use Illuminate\Database\Seeder;

use G3n1us\Pub\Models\User;
use G3n1us\Pub\Models\Article;
use G3n1us\Pub\Models\Author;
use G3n1us\Pub\Models\ArticleContent;
use G3n1us\Pub\Models\File;


class PubDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $author_count = Author::count();
	    if($author_count < 15){
		    factory(Author::class, 15)->create();
	    }
	    
	    
		factory(Article::class, 5)
			->create()
			->each(function ($art){
				$author = Author::inRandomOrder()->first();
                $art->content()->save(factory(ArticleContent::class)->make());
                $art->authors()->save($author);
                factory(File::class, 3)->make()->each(function($f) use($art){
	                $art->files()->save($f);
                });
                $art->author_display = $author->displayname;
                $art->save();
            });      
            
            
// 		factory(User::class, 15)->create();
              

    }
}
