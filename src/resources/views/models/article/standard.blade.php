	<div class="card mb-1 card-no-border" href="{{$article->url}}">
		<a href="{{$article->url}}">
			<picture class="card-img-top">
			  <source 
			    media="(min-width: 960px)"
			    srcset="{{object_get($article->photo, 'url')}}">
			  <source 
			    media="(min-width: 768px)"
			    srcset="{{object_get($article->photo, 'medium')}}">
			  <source 
			    media="(max-width: 480px)"
			    srcset="{{object_get($article->photo, 'thumb')}}">
			  <img 
			    src="{{object_get($article->photo, 'url')}}" 
			    alt="{{object_get($article->photo, 'url')}}">
			</picture>
			
		</a>
		<div class="card-block">
			<h3><a href="{{$article->url}}">{{$article->title}}</a></h3>
			
			@include('pub::models.author.article_display')
			
			<a href="{{$article->url}}" class="text-muted">
				{!! $article->summary !!}
			</a>
		</div>
	</div>
