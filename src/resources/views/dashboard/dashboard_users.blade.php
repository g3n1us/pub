@extends("pub::layouts.editor")

@push('body_html_classes')
logged_in
@endpush

@section('body')
<div class="container-fluid pt-3" style="background-color: rgba(255, 255, 255, 0.85)">
	
    <div class="row">
	    <div class="col-md-12">
		    <a class="btn btn-primary btn-lg" href="/dashboard">&larr; Return to Dashboard</a> 
	    </div>
    </div>
    <div class="row mt-3">
@foreach($users as $user)
		<div class="col-md-3 mb-3">
	    @include('pub::models.user.user_media_object')
		</div>
@endforeach
    </div>
    
   {{$users->links()}}
 
    
</div>


@endsection