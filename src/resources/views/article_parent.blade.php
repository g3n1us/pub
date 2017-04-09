@extends("pub::layouts.layout")

@section('head')
@parent
@endsection

@push('body_html_classes')article_page @endpush

@section('body')
@foreach($articles as $article)

<article class="m-b-6 article" id="article-{{$article->article_id}}">
	<div class="container">
		<div class="row">
		</div>
		<div class="row">	
			<div class="col-xl-9 col-md-7">
				<div class="m-b-1X">
					{!! show_block('<h1>'.$article->title.'</h1>', 'article_headline') !!}
					{{-- show_area('article_headline', $article, ['empty_content' => show_block('<h1>'.$article->title.'</h1>')]) --}}
				</div>
				
				@include('pub::models.author.article_display')
												
				@section('article_lead_photo')
				
				@show
				<summary class="pb-1 article-summary h5">
					{!! $article->summary !!}
				</summary>
				<section class="article-body" style="{{-- max-width:600px --}}">
					{!! show_area('main', $article) !!}

					{!! $article->body !!}
					
					<nav class="article-social my-2">
						<b class="text-primary">Share this article: </b>
						<a class="btn btn-circle btn-primary" href="https://twitter.com/intent/tweet?text={{$article->title}}&via={{config('pub.twitter_handle')}}&url={{$article->url}}"><i class="fa fa-twitter"></i></a> 
						<a class="btn btn-circle btn-primary ml-quarter js-window" data-window_size="700x400" href="https://www.facebook.com/sharer.php?u={{$article->url}}"><i class="fa fa-facebook"></i></a> 
						<a class="btn btn-circle btn-primary ml-quarter js-window" href="mailto:?subject=Read+This!+{{rawurlencode($article->title)}}&body={{rawurlencode(strip_tags($article->summary))}}%0D%0A%0D%0A{{rawurlencode(str_limit(str_replace("\n", "\n\n",strip_tags($article->body)), 900))}}%0D%0A%0D%0A%0D%0A Continue Reading at: {{$article->url}}"><i class="fa fa-envelope"></i></a> 
					</nav>
					
				</section>
				<aside class="m-t-2">
					@foreach($article->tags as $tag)
					<a class="badge badge-default" style="margin-bottom: .5rem" href="/tag/{{$tag->name}}">{{$tag->name}}</a>
					@endforeach
				</aside>
			</div>
			<div class="col-xl-3 col-md-5">
				@include("pub::parts.sidebar")
			</div>
		</div> <!-- close .row -->
	</div>
	<footer>
		
	</footer>
</article>

@if(!$article->status->approved)
<div class="" style="position: fixed; top: 100px;margin:auto;right:3rem;">{!! call_user_func($article->status->render) !!}</div>
@endif

@endforeach

@endsection