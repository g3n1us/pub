<?php

namespace G3n1us\Pub\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
/*
use \App\Article;
use \App\User;
*/

class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
	public function create(Article $article)
	{
		// return boolean
	}    
	public function update(Article $article)
	{

	}    
}

