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
    <div class="row">
	    <div class="col-md-6 offset-md-3">
	    <div class="card-deck">
			<div class="card text-center">
				<a href="/">
				<img class="card-img-top" src="/files/{{$brand['logo']}}" alt="{{$brand['name']}}">
				</a>
				<div class="card-block">
					<h3 class="text-center">{{$brand['name']}}</h3>
<!-- 					<p class="card-text">Pages:</p> -->
				</div>
				<div class="list-group list-group-flush brand-sort-list text-xs-right">
				
@foreach($brand['pages'] as $brandpage)
					 <a href="{{url($brandpage['url'])}}" class="list-group-item text-right dashboard-pagelink" data-pageid="{{$brandpage['id']}}" data-order="{{$brandpage['order']}}" style="background-image: url('/files/thumb/{{$brandpage['image']}}'); background-position: left center; background-repeat: no-repeat; background-size: 80px auto; padding-left: 100px;">
			       
						<span class="card-title" style="line-height: 1">
						@if($brandpage['nav_hide'])<span class="tag tag-warning" rel="tooltip" title="Hidden from Navigation">Hidden</span>@endif
						@if(!$brandpage['active'])<span class="tag tag-danger" rel="tooltip" title="Page is Inactive">Inactive</span>@endif 
						 &nbsp; &nbsp;{{trim($brandpage['name'])}} &nbsp; &nbsp;
	<!-- 						<span class="fa-stack"> -->
								<i data-sort="up" rel="tooltip" title="Change page order" data-delay="900" class="sorter fa fa-sort-up fa-lg"></i>
								<i data-sort="down" rel="tooltip" title="Change page order" data-delay="900" class="sorter fa fa-sort-down fa-lg"></i>
	<!-- 						</span> -->
						</span>
					
				    </a>
@endforeach
				    
				</div>
				
				<div class="card-block">
<a class="btn btn-block btn-xs btn-primary" data-modal_handlebars_template="new_page_modal_template" data-toggle="modal" data-target="#multi_modal" href="/page/-/edit" data-name="Add a Page"><i class="fa fa-plus"></i> New Page</a>					
				</div>

			</div>	
			
	    
	    </div>
	    </div>
    </div>
    
    
</div>


@endsection