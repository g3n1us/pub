@extends("pub::layouts.layout")

@section('head')
@parent
@endsection

@section('body')



<article class="mb-6">
	<div class="container">
		<div class="row">	
			<div class="col-xl-9 col-md-7">		
				<h1 class="display-4">{{$heading}}</h1>
@foreach($authors as $author)
				<div class="row mt-3 mb-1">
					<div class="col-xl-12 col-md-12 article-byline">
						
						@if($author->displayname)
						<h5 style="font-weight: normal"><a href="/author/{{$author->displayname}}">
							<img class="rounded-circle pull-left mr-1" src="{{$author->mugshot}}" style="max-width: 150px;">
							{{$author->displayname}}</a></h5>
						<nav class="author-social">
							@if($author->twitter)
							<a target="_blank" class="btn btn-circle btn-primary" href="https://twitter.com/{{$author->twitter}}"><i class="fa fa-twitter"></i> </a>
							@endif
							@if($author->facebook_page)
							<a target="_blank" class="btn btn-circle btn-primary" href="{{$author->facebook_page}}"><i class="fa fa-facebook"></i></a>
							@endif
							@if($author->email)
							<a target="_blank" class="btn btn-circle btn-primary" href="mailto:{{$author->email}}"><i class="fa fa-envelope"></i> </a>
							@endif
						</nav>
						@endif
						{!! nl2br($author->bio) !!}
						<div class="clearfix"></div>
						<hr class="mt-3">
					</div>
				</div>
@endforeach	
@if(method_exists($authors, 'render'))
<p class="text-center-sm">
{!! $authors->render() !!}		
</p>
@endif
			</div>
			<div class="col-xl-3 col-md-5">
				@include("pub::parts.sidebar")
			</div>
		</div> <!-- close .row -->
	</div>
</article>


@endsection