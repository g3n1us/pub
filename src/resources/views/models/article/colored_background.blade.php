	<div class="card card-inverse mb-1 card-no-border" href="{{$article->url}}">
		<picture class="card-img">
			<source 
			media="(min-width: 960px)"
			srcset="{{$article->lead_photo->large_size}}">
			<source 
			media="(min-width: 768px)"
			srcset="{{$article->lead_photo->medium_size}}">
			<source 
			media="(max-width: 480px)"
			srcset="{{$article->lead_photo->thumb_size}}">
			<img 
			src="{{$article->lead_photo->medium_size}}" 
			alt="{{htmlspecialchars($article->lead_photo->caption)}}">
		</picture>
		<a class="card-img-overlay" href="{{$article->url}}" style="background-color: rgba(164, 18, 18, 0.66)">
{* 			<a href="{{$article->url}}"> *}
				<h4 class="card-title">{{$article->title}}</h4>
{*
			</a>
			<a href="{{$article->url}}">
*}
				<p class="card-text">{{$article->summary}}</p>
{* 			</a> *}
		</a>			
{*

		<h3><a href="{{$article->url}}">{{$article->title}}</a>
		<small class="text-muted"><a href="/author/{{$article->author_display}}">By {{$article->author_display}}</a></small></h3>
		<date>{$article->pub_date->toDayDateTimeString()}</date>
		<date>{$article->pub_date->diffForHumans()}</date>
		<a href="{{$article->url}}">
			<p>{{$article->description}}</p>
		</a>
		<div>
			{{foreach $article->tags() as $tag}}
			<a class="badge badge-default mb-half" href="/tag/{$tag->section}">{$tag->section}</a>
			{{/foreach}}
		</div>
*}
	</div>
