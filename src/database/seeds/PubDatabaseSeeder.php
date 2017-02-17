<?php

use Illuminate\Database\Seeder;

use G3n1us\Pub\Models\User;
use G3n1us\Pub\Models\Article;
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
		factory(Article::class, 5)
			->create()
			->each(function ($art){
                $art->content()->save(factory(ArticleContent::class)->make());
                factory(File::class, 3)->make()->each(function($f) use($art){
	                $art->files()->save($f);
                });
                
            });      
            
            
// 		factory(User::class, 15)->create();
              

    }
}
