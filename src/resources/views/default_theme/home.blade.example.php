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

<div class="container">
<div class="row">
	<div class="col-md-12">
		{!! show_area('main', $page) !!}
	</div>
</div>
</div>

@endsection