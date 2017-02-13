@extends('pub::layouts.layout')


@push('body_html_classes')
search_page 
@endpush


@section('body')

<article class="m-b-6 Xarticle">
	<div class="container">
		<div class="row">	
			<div class="col-xl-6 col-xl-offset-2 col-md-7">		
				<h1 class="display-4 m-b-1">Search</h1>
                <form action="/search" class="form-inline">
	                <div class="form-troup">
	                <input type="search" autofocus class="form-control" name="q">
	                <button type="submit" class="btn btn-primary">Search</button>
	                </div>
                </form>
			</div>
			<div class="col-xl-3 col-md-5">
				@include("pub::parts.sidebar")

			</div>
		</div> <!-- close .row -->
	</div>
	<footer>
		
	</footer>
</article>



@endsection