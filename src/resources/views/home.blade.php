@extends('pub::layouts.layout')

@section('htmlclasses')@if( $user )logged_in @endif @endsection
@section('bodyclasses')home @if( $user)logged_in @endif 
@endsection
@section('head')
@parent
@endsection

@section('navbuttons') 

@endsection

@section('header')

@parent

@endsection


@push('body_html_classes')
home 
@endpush


@section('body')
@php
$articles = Article::limit(20)->get();
@endphp
<article class="mb-6 article" id="article-{{$articles[0]->article_id}}">
	<div class="container">
		<div class="row">
			<div class="col-xl-9 col-md-7 mb-1">
				<a href="{{$articles[0]->link}}">
				<div class="card card-inverse" style="margin-left:auto; margin-right:auto; width: max-content; max-width: 100%;">
					
					@if( $articles[0]->lead_photo )
					<img class="card-img" style="border-radius: 0" src="{{$articles[0]->lead_photo->url}}">
					@else
					<img class="card-img" style="width: 100%; border-radius: 0" src="http://influence.mediadc.com/files/small/weekly-standard-logo.svg">
					@endif
					<div class="card-img-overlay">
						<h2 class="card-title" style="background-color: rgba(0, 0, 0, 0.7); display: inline-block; padding: 5px 10px;">{{$articles[0]->title}}<br>
							<small class="text-muted" style="font-size: 60%">By {{$articles[0]->author_display}}<br>
 				{{$articles[0]->pub_date->toDayDateTimeString()}} 
				</small>							
						</h2>

					</div>
					
				</div>	
				</a>


			</div>
			<div class="col-xl-3 col-md-5 mb-1">
				<h6 class="mb-1" style="font-weight: normal"><span class="bg-danger" style="padding:1px 5px 2px">News</span> Stream</h6>
					<div class="list-group timeline-list-group">
@foreach($articles as $ind => $article)
@if( $ind < 5 )						
						<a href="{{$article->link}}" class="list-group-item list-group-item-action">
							<p style="transform: translateY(-9px)"><small class="text-danger">{{$article->pub_date->diffForHumans()}}</small>
							<br>{{$article->short_title}}</p></a>
@endif	
@endforeach					

					</div>				
				
			</div>
		</div>
	</div>
</article>

<div class="container">
<div class="row">
	<div class="col-md-12">
		{!! show_area('main', $page) !!}
	</div>
</div>
</div>


{{--
@foreach($articles as $ind => $article)
@if($ind > 5)
<article class="mb-6 article" id="article-{{$article->article_id}}">
	<div class="container">
		<div class="row">
			<div class="col-xl-9 col-md-7 mb-1">
				<h2 class="mb-2 article-title"><a href="{{$article->link}}">{{$article->title}}</a></h2>
				@if( $article->lead_photo )	
				<figure class="mb-3 article-lead-photo ">
					<a href="{{$article->link}}">
						<img src="{{$article->lead_photo->url}}">
					</a>
				<cite><a href="{{$article->link}}">{{$article->short_title}}</a></cite>
				</figure>
				@endif
				<h5 style="font-weight: normal">
					@if( $article->author_from_displayname )
					<a href="/author/{{$article->author_display}}">
						<img class="rounded-circle pull-left mr-1" style="max-width: 75px" src="{{$article->author_from_displayname->mugshot}}">
					</a>
					@endif
					By <a href="/author/{{urlencode($article->author_display)}}">{{$article->author_display}}</a><br>
					<date class="text-muted"><a tabindex="0" href="/by-date/{{$article->pub_date->toAtomString()}}" data-date="{{$article->pub_date->toAtomString()}}">{{$article->pub_date->toDayDateTimeString()}}</a></date>
				</h5>

			</div>
		</div>
	</div>
</article>


@endif
@endforeach
--}}
<script>
var sliderData = {};
sliderData.articles = {!! $articles !!};
var site_is_production = false;

</script>
<div data-subfolder="userdata/user-us-east-1:643c4fb0-9762-4f46-9723-5e6fa2da3b0e/" data-mdc_component="VideoSlider" class="VideoSlider mediadc-video-showcase-wrapper"></div>


@endsection