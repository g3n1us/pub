@extends("pub::layouts.layout")

@push('body_html_classes')
article_list_page 
@endpush


@section('body')
<article class="m-b-6 Xarticle">
	<div class="container">
		<div class="row">	
			<div class="col-xl-9 col-md-7">		
				@if(isset($heading))
				<h1 class="display-4">{{$heading}}</h1>
				@endif
@if(isset($extra) && $extra == "author")
@include("pub::extras.author")
@endif
@if(isset($extra) && $extra == "years")
@include("pub::extras.years")
@endif
@include("pub::parts.raw_article_list")
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