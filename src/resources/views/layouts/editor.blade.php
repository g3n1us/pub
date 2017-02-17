<!DOCTYPE html>   
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js editor-bootstrap @stack('body_html_classes')"> <!--<![endif]-->
<head>
@section('head')
	<meta charset="utf-8">
	<!--[if IE]><![endif]-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link href="https://fonts.googleapis.com/css?family=Roboto+Slab:300,400,700" rel="stylesheet">
	<title>{{$heading or ""}}</title>
	<meta name="description" content="">
	<meta name="keywords" content="" />
	<meta name="author" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- !Bootstrap -->
	<link rel="stylesheet" href="/vendor/pub/additional.css?v=1234">
@if(Auth::check())
	<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,400i,700" rel="stylesheet">
	<link rel="stylesheet" href="/vendor/pub/dist/css/both-compiled.min.css">
	<link rel="stylesheet" href="/vendor/pub/dist/css/private-compiled.min.css">
@endif
<!--
	<link rel="stylesheet" href="/vendor/pub/_assets/js/vendor/ckeditor_4.6/plugins/prism/lib/prism/prism_patched.min.css">
	<script src="/vendor/pub/_assets/js/vendor/ckeditor_4.6/plugins/prism/lib/prism/prism_patched.min.js"></script>
-->
	<script src="http://ajax.aspnetcdn.com/ajax/modernizr/modernizr-2.8.3.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script>
		var article; // do this a better way. This is just to avoid errors for Vue.js
	</script>
	<style>
		.editor-bootstrap .pagination{
			justify-content: center;
		}
	</style>

@show
</head>
<body class="@stack('body_html_classes')">
@include('pub::parts.messages')
@section('header')

@show
@section('body')

@show
@include('pub::parts.foot_assets')

@if(isset($user))
@include('pub::parts.editor')
@endif
@section('footer_extras')

@show
</body>
</html>
