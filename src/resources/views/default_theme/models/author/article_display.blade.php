			<h5 style="font-weight: normal" class="clearfix article-byline">
				@if( $article->author_from_displayname )
				<a href="/author/{{$article->author_display}}">
					<img class="rounded-circle pull-left mr-1" style="max-width: 75px" src="{{$article->author_from_displayname->mugshot}}">
				</a>
				<div class="d-block mt-1">
					<a href="/author/{{urlencode($article->author_display)}}" rel="author">By {{$article->author_display}}</a>
					<nav class="author-social d-inline-block ml-1 text-muted" style="xtransform: translate(0,-4px);">
						@if($article->author_from_displayname->twitter)
						<a target="_blank" class="xbtn xbtn-circle xbtn-primary" href="https://twitter.com/{{$article->author_from_displayname->twitter}}"><i class="fa fa-twitter fa-fw"></i> </a>
						@endif
						@if($article->author_from_displayname->facebook_page)
						<a target="_blank" class="xbtn xbtn-circle xbtn-primary" href="{{$article->author_from_displayname->facebook_page}}"><i class="fa fa-facebook fa-fw"></i></a>
						@endif
						@if($article->author_from_displayname->email)
						<a target="_blank" class="xbtn xbtn-circle xbtn-primary" href="mailto:{{$article->author_from_displayname->email}}"><i class="fa fa-envelope fa-fw"></i> </a>
						@endif
					</nav>
				</div>
				@endif
				<time class="text-muted" pubdate datetime="{{$article->pub_date->toAtomString()}}" title="{{$article->pub_date->toDayDateTimeString()}}">
				<a tabindex="0" pubdate href="/by-date/{{$article->pub_date->toAtomString()}}" data-date="{{$article->pub_date->toAtomString()}}">{{$article->pub_date->toDayDateTimeString()}}</a></time>
			</h5>

GGGGG
