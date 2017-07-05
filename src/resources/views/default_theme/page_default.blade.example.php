@extends('pub::layouts.layout')


@section('body')
<div class="container">
	<div class="row">	
		<div class="col-xl-9 col-md-7">		
		{!! show_area('main', $page) !!}
		</div>
		<div class="col-xl-3 col-md-5">
			@include('pub::parts.sidebar')
		</div>
	</div> <!-- close .row -->
</div>

@endsection

@section('footer_extras')

@if(edit_mode())
@include('pub::parts.frontend_editor')
@endif

@endsection