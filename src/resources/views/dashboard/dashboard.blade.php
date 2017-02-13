@extends("pub::layouts.editor")

@push('body_html_classes')
logged_in
@endpush

@section('body')
<div class="container-fluid pt-3" style="background-color: rgba(255, 255, 255, 0.85)">
    <div class="row">
	    <div class="col-md-12">
		    <a class="btn btn-primary btn-lg" href="/">&larr; Return to Site</a> 
	    </div>
    </div>
    <div class="row mt-1">
	    <div class="col-md-4">
		    <a href="/dashboard/pages">
			<div class="card text-center">
				<div class="card-block">
					<h3 class="text-center">Pages</h3>
				</div>				
			</div>	
		    </a>
	    </div>
	    
	    <div class="col-md-4">
		    <a href="/dashboard/articles">
			<div class="card text-center">
				<div class="card-block">
					<h3 class="text-center">Articles</h3>
				</div>				
			</div>	
		    </a>
	    </div>
	    
	    <div class="col-md-4">
		    <a href="/dashboard/users">
			<div class="card text-center">
				<div class="card-block">
					<h3 class="text-center">Users</h3>
				</div>				
			</div>	
		    </a>
	    </div>
	    
    </div>
    
    
</div>


@endsection