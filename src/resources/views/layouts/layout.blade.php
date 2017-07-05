<!DOCTYPE html>   
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js @section('htmlclasses') @endsection {{ auth()->check() ? 'logged_in' : '' }}"> <!--<![endif]-->
<head>
@section('head') 

@include('pub::parts.head')

@show
</head>
<body class="{{ auth()->check() ? 'logged_in' : '' }} @section('bodyclasses') @show @stack('body_html_classes')" style="padding-top: 4rem;">
@include('pub::parts.messages')

@section('header')
@include('pub::parts.header')
@show

@section('body')

@show

@include('pub::parts.footer')
@include('pub::parts.push_over_menu')
@include('pub::parts.foot_assets')


@if(Auth::check())
@include('pub::parts.editor')
@endif

@section('pub::footer_extras')
@show
</body>
</html>