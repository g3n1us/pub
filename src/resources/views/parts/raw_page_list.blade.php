<nav class="nav nav-justified">
{foreach $pages as $page}	
	<a class="nav-item nav-link" href="/{$page->url}">{$page->name}</a>
{/foreach}
</nav>
