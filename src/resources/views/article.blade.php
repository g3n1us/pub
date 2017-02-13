@extends('pub::article_parent')

@section('article_lead_photo')
	@if(isset($articles[0]) && object_get($articles[0], 'lead_photo'))
	<figure class="m-b-3 article-lead-photo">
		<img style="display: block" src="{{$articles[0]->lead_photo->url}}">
	<cite>{{$articles[0]->short_title}}</cite>
	</figure>
	@endif
@endsection

@push('body_html_classes') article_page @endpush

@section('head')
@parent
@if(isset($articles[0]))
	
	<link rel="canonical" href="{{$articles[0]->url}}">

    <meta property="og:url" content="{{$articles[0]->url}}" />	
    <meta property="og:site_name" content="{{$brand->name}}" />
	<meta property="og:type" content="article" />
@if(isset($articles[0]->lead_photo))
	<link rel="image_src" href="{{$articles[0]->lead_photo->url}}">	
	<meta property="og:image" content="{{$articles[0]->lead_photo->url}}">
@endif
	<meta property="og:title" content="{{$articles[0]->title}}">
	
	<meta name="description" 
	      content="{{str_replace('"','', strip_tags($articles[0]->summary))}}">
	<meta property="og:description" content="{{ str_replace('"','',strip_tags($articles[0]->summary)) }}">
	      
	<meta name="author" content="{{$articles[0]->author_display}}">	
	<link rel="alternate" type="application/json+oembed" href="{{url('/oembed?format=json&article_id='.$articles[0]->id.'&url='.$articles[0]->url, [$articles[0]->id])}}" title="{{$articles[0]->title}}">
	
@endif
@endsection