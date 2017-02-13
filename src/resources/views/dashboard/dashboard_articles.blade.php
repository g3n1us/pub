@extends("pub::layouts.editor")

@push('body_html_classes')
logged_in
@endpush

@section('body')
<script>
	var handlebars_template_data = { articles: {{$articles->toJson()}} };
</script>
<div class="container-fluid pt-3" style="background-color: rgba(255, 255, 255, 0.85)">
	
    <div class="row">
	    <div class="col-md-12">
		    <a class="btn btn-primary btn-lg" href="/dashboard">&larr; Return to Dashboard</a> 
	    </div>
	    <div class="col-md-12 mt-3">
		    <div class="list-group ">
	    @foreach($articles as $article)
	    <a href="{{$article->url}}/edit" class="list-group-item list-group-item-action justify-content-between @if(!$article->status->approved) list-group-item-danger @endif"><span>{{$article->title}} &nbsp;<small class="text-muted">{{$article->pub_date}}</small></span>
		    <span>
		    @foreach($article->status->reasons as $reason)
		    <span class="badge badge-default badge-pill">{{$reason}}</span>
		    @endforeach
		    </span>
	    </a>
	    @endforeach
		    </div>
	    </div>
	    <div class="col-md-12 center-block mt-2">
	    {{$articles->links()}}
	    </div>
    </div>

{{--     <div data-handlebars_template="articles_template" data-XXtype="articles" data-sourceurl="/filemanager/tab/articles?page={{Request::input('page', 1)}}"></div>	     --}}
    
</div>


<div class="card card-block">
<a class="btn btn-block btn-xs btn-primary" data-modal_handlebars_template="new_article_modal_template" data-toggle="modal" data-target="#multi_modal" href="/page/-/edit" data-name="Add an Article"><i class="fa fa-plus"></i> New Article</a>					
{{--					<a class="btn btn-block btn-xs btn-primary" href="/dashboard/edit/page/new?brand_id={{$brand['id']}}"><i class="fa fa-plus"></i> New Page</a> --}}
</div>

@endsection