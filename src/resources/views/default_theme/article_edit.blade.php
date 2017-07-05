@extends("pub::layouts.editor")

@push('body_html_classes')logged_in editor-bootstrap article_edit @endpush

@section('body')
<script>
	var article = {!! $articles->first() !!};
</script>
<style>
	#editor_messages{
		margin-top: 26px;
	}
	
	@media(min-width:768px){
		.independent-scrollable{
			max-height: calc(100vh - 50px); overflow: auto;
			
		}			
	}
	#right_slidepanel{
		display: none;
	}
</style>
<div id="editor_toolbar" style="position: fixed; top: 50px;"></div>
	<div class="container-fluid XXmt-2 ">
		<div class="row">			
			<div class="col-md-4 py-1 independent-scrollable" style="">
				<div class="article-status">
					{!! call_user_func($articles->first()->status->render) !!}
				</div>
				<ul class="nav nav-pills">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" data-load="article_edit_tab_meta" href="#page-meta">Meta</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" data-load="file_manager"  href="#file-manager">Site Browser</a>
					</li>
				</ul>				
								
				<div class="tab-content">
					<div class="tab-pane active" id="page-meta" role="tabpanel">
						@include("pub::parts.article_edit_tab_meta")
					</div>
					<div class="tab-pane" id="file-manager" role="tabpanel" XXdata-ajaxload="/dashboard/cklist">
						<a href="/filemanager" class="js-window" style="position: absolute; right: 10px;" title="Open Filemanager in another window" rel="tooltip"><i class="fa fa-external-link"></i></a>
						@include("pub::filemanager.filemanager_contents")
					</div>
				</div>			
				
				
			</div>
			<div class="col-md-8 independent-scrollable" style="">
					<div style="" id="editor_messages"></div>
					<fieldset class="form-group">
						<textarea form="editorForm" name="content[body]" class="editor hide">{{$articles->first()->body}}</textarea>
					</fieldset>
					<fieldset class="form-group">
						<button class="btn btn-primary" form="editorForm" type="submit">Save</button>
					</fieldset>

					<fieldset class="form-group">
						<h6>Lead Photo</h6>
						<div style="min-height: 40px">
						@if($articles->first()->lead_photo)
						<input type="hidden" name="lead_photo" class="form-control" value="{{$articles->first()->lead_photo->id}}">
						<a href="{{$articles->first()->lead_photo->url}}" target="_blank"><img class="img-thumbnail" style="width: auto; max-height: 175px; margin-bottom: 10px;" src="{{$articles->first()->lead_photo->url}}"></a>		
						@else
						<div class="alert alert-info">No Lead Photo</div>
						@endif
						</div>
{{-- 						<button type="button" id="add-edit-lead-photo" class="btn btn-primary">Add/Change Lead Photo</button> --}}
					</fieldset>
					
					<h6>Related Files</h6>
					<div class="Xtext-center related-files-outer Xcard-group card-columns mb-2" data-action="" style="min-height: 100px; background-color: white; "></div>
@verbatim					
<script type="text/template" id="related_file">
	{{#each files}}
	<div class="card">
		<img class="card-img-top" style="width: auto; margin-bottom: 10px;" src="{{square}}">
		<div class="card-block" style="color: black">
			<form action="/filesave/{{id}}/{{../article.id}}" method="post">
				@endverbatim
				{!! csrf_field() !!}
				@verbatim
				<div class="form-group">
					<label>Caption</label>
					<input type="text" class="form-control" name="metadata->caption" value='{{pivot_metadata.caption}}'>
				</div>
				<div class="form-group">
					<label>Credit {{pivot_metadata.lead_photo}}</label>
					<input type="text" class="form-control" name="metadata->credit" value='{{pivot_metadata.credit}}'>
				</div>
				
				<div class="form-group clearfix">
					<input type="hidden" name="metadata->lead_photo" value="false">
					<label><input type="checkbox" name="metadata->lead_photo" value="true"
					{{#if pivot_metadata.lead_photo}}checked {{/if}}
					class="checkbox"> Make Lead Photo</label>
					<input type="submit" value="Save Changes to File" class="btn btn-primary btn-block m-t-1">
					<a class="btn btn-sm btn-xs btn-danger m-t-1 pull-right" data-toggle="collapse" href="#removefilesbox{{id}}"><i class="fa fa-trash"></i></a>
				</div>
				
				<div class="collapse " id="removefilesbox{{id}}">
				<fieldset class="form-group">
					<button type="submit" onclick="if(!confirm('Are you sure?')) return false" name="_remove" value="true" class="btn btn-block btn-sm btn-warning m-t-3">Remove File from Article</button>
					<button type="submit" onclick="if(!confirm('Are you sure? This will permanently delete the file and dissassociate it from all articles.')) return false" name="_method" value="DELETE" class="btn btn-block btn-sm btn-danger m-t-1">Delete File</button>									
				</fieldset>
				</div>
			</form>	
		</div>
	</div>	
	{{/each}}
</script>					
@endverbatim			
									
				</div>
					
			</div>
			
	</div>

@endsection