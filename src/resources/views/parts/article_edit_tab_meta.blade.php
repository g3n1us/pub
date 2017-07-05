@if(isset($articles) && is_object($articles) && method_exists($articles, 'first') && $articles->count())
@php
$article = $articles->first();
@endphp
<!-- 	<link rel="stylesheet" href="/vendor/pub/js/includes/At.js-master/dist/css/jquery.atwho.min.css"> -->

				<form method="post" id="editorForm" class="pa-1" action="{{ route('article.update', $articles->first(), false) }}">
				{!! csrf_field() !!}
				{!! method_field('PUT') !!}
				<fieldset class="form-group mt-3 card card-block">
					<label class="col-form-label form-control-label">Send to Print</label>
					<button type="button" style="cursor: pointer;" class="pub--onajax btn btn-primary btn-block" data-href="/word/{{$article->id}}">Send</button>

					<label class="mt-3 col-form-label form-control-label" for="workflow[status]">Workflow Status</label>
					<select class="form-control" name="workflow[status]" id="workflow[status]">
						<option value="draft">Draft</option>
						<option value="under-review" @if($articles->first()->workflow && $articles->first()->workflow->status == "under-review") selected @endif >Initial Review</option>
						<option value="final-review" @if($articles->first()->workflow && $articles->first()->workflow->status == "final-review") selected @endif >Final Review</option>
						<option value="final" @if($articles->first()->workflow && $articles->first()->workflow->status == "final") selected @endif>Final</option>
					</select>
					
					
					<input type="hidden" name="approved" value="0">
					<label class="custom-control custom-checkbox form-control-label mt-4" for="approved">
						<input type="checkbox" id="approved" name="approved" @if($articles->first()->approved) checked @endif value="1" class="custom-control-input">
						<span class="custom-control-indicator"></span>
						<span class="custom-control-description">Approved</span>
					</label>	
					<label class="col-form-label form-control-label" for="workflow[assigned_user]">Assign to User</label>
					<select class="form-control" name="workflow[assigned_user]" id="workflow[assigned_user]">
						<option>--</option>
						@foreach(G3n1us\Pub\Models\User::all() as $u)
						<option value="{{$u->id}}" @if($u->id == object_get($article, 'workflow.assigned_user')) selected @endif>{{$u->name}} - {{$u->username}}</option>
						@endforeach
					</select>
					<label class="col-form-label form-control-label" for="inputor">Add Note</label>
					<textarea class="form-control" name="workflow[notes][]" id="inputor"></textarea>
					<style>
						.editor-bootstrap .quote-badge{
							white-space: normal;
							line-height: 1.3;
							position: relative;
							overflow: visible;
							border-radius: 1rem;
							box-shadow: 1px 1px 5px black;
						}
						.editor-bootstrap .quote-badge:before{
							content: " ";
							position: absolute;
							left: -8px;
							bottom: 8px;
						    width: 0px;
						    height: 0px;
						    border-top: 10px solid transparent;
						    border-bottom: 10px solid transparent;
						    border-right: 10px solid #93121c;							
							
						}
					</style>
@php
$notes = object_get($article, 'workflow.notes', []);
@endphp
					<div class="mt-3">
					@foreach($notes as $note)
@php
$note_message = array_get($note, 'message');
foreach($note['mentions'] as $mention){
	$note_message = str_replace('@'.$mention['username'], '<a class="text-warning" rel="tooltip" title="'.$mention['name'].'">@'.$mention['username'].'</a>', $note_message);
}
// dd($note);
@endphp					
					<span class="h4 d-block mt-1"><span class="badge badge-danger quote-badge text-left ml-3" style="padding: .5em; font-weight: 300;">{!! $note_message !!} <small class="text-black ml-2" style="color: #afafaf;" data-title="{{ (new \Carbon\Carbon(array_get($note, 'timestamp.date')))->toDayDateTimeString() }}" rel="tooltip" data-delay="1500" data-placement="top" >{{ (new \Carbon\Carbon(array_get($note, 'timestamp.date')))->diffForHumans() }}</small></span></span>
					@endforeach
					</div>
				</fieldset>
				<fieldset class="form-group">
					<label class="col-form-label form-control-label" for="title">Title</label>
					<textarea name="title" id="title" class="form-control h2" rows="3" required>{{$articles->first()->title}}</textarea>
				</fieldset>
				<fieldset class="form-group">
					<label class="col-form-label form-control-label" for="subtitle">Subtitle</label>
					<input type="text" name="subtitle" id="title" class="form-control" value="{{$articles->first()->subtitle}}">
				</fieldset>
				<fieldset class="form-group">
					<label class="col-form-label form-control-label" for="author_selector">Authors</label>
					<select class="form-control m-b-1" id="author_selector" oninput="$(this).after($('#newauthortemplate').html()).next().val($(this).val()).wrap('<div class=\'input_token selected_author h3 mb-1\'><span class=\'tag tag-primary\'><a class=\'close\'>&times;</a><span class=\'input_token_text\'>'+$(this).find('option:selected').text()+'</span></span></div>');$(this).val('none')">
						<option value="none" selected>-- Choose authors --</option>
					@foreach(get_all('Author') as $author)
						<option value="{{$author->id}}">{{$author->displayname}}</option>
					@endforeach
					</select>
					@foreach($articles->first()->authors as $existingauthor)
					<div class="input_token selected_author h3 mt-2"><span class="tag tag-primary"><a class="close">&times;<input type="hidden" name="authors[]" value="{{$existingauthor->id}}"></a><span class="input_token_text">{{$existingauthor->displayname}}</span></span></div>						
					@endforeach
					
				</fieldset>
				<fieldset class="form-group">
					<label class="col-form-label form-control-label" for="author_display">Author Display</label>
					<input type="text" name="author_display" id="author_display" class="form-control" value="{{$articles->first()->author_display}}">
					
				</fieldset>
				<fieldset class="form-group">
					<label class="col-form-label form-control-label" for="pub_date">Pub Date</label>
					<input type="datetime-local" name="pub_date" id="pub_date" class="form-control" value="{{$articles->first()->pub_date->format("Y-m-d\TH:i:s")}}">
				</fieldset>
				<fieldset class="form-group">
					<label class="col-form-label form-control-label" for="summary">Summary</label>
					<textarea name="summary" id="summary" rows="6" class="form-control">{{$articles->first()->summary}}</textarea>
				</fieldset>
				
				<fieldset class="form-group">
					<label class="col-form-label form-control-label">Tags</label>
					<div class="Xform-inline">
						@foreach($articles->first()->tags as $tag)
						<input type="text" name="tags[]" value="{{$tag->name}}" placeholder="Add Tag Name" class="tokenable_input form-control margin-bottom10 remove_if_empty_on_save"> 
						@endforeach
						<button type="button" class="btn btn-success margin-bottom10" onclick="$(this).before($('#newtagtemplate').html()).prev().focus()"><i class="fa fa-plus"></i> Add</button>
					</div>				
				</fieldset>
				<datalist id="alltags" data-handlebars_template="tags_datalist" data-sourceurl="/ajax/tags?pluck=name&paginate=0">
				</datalist>
				<fieldset class="form-group">
					<label class="col-form-label form-control-label" for="article_versions">Article Versions</label>
					<select id="article_versions" class="form-control" data-handlebars_template="article_versions_template" data-sourceurl="/article_versions/{{$articles->first()->id}}">
						<option> -- </option>
					</select>
					<button type="button" id="view_article_version" class="btn btn-primary mt-1">View</button>
					<button type="button" id="view_article_version_edit" class="btn btn-primary mt-1">Edit Version</button>
				</fieldset>
				
			</form>
@endif	