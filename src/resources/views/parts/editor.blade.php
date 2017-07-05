<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,400i,700" rel="stylesheet">
<link rel="stylesheet" href="/vendor/pub/editor/css/pub-editor.min.css">


<style>
	.editor-bootstrap .edit_article_button, .editor-bootstrap .article_edit .edit_article_button, .article_edit .editor-bootstrap .edit_mode_button, .article_edit .editor-bootstrap .settings_button{
		display: none;
	}
	
	 .article_list_page .editor-bootstrap .settings_button, .search_page .editor-bootstrap .settings_button, .home .editor-bootstrap .settings_button, .editor-bootstrap .return_to_article_button{
		 display: none;
	 }
	
	.article_page .edit_article_button, .article_edit .return_to_article_button
	{
		display: initial;
	}
</style>

<div class="editor-bootstrap" id="editorouter">
	
	<nav class="navbar navbar-toggleable-xl navbar-fixed-top editor-bar" id="editorbar">					
		<div class="Xnav navbar-nav align-items-center" style="flex-grow: 1;">
			<a class="nav-item Xnav-link btn btn-primary btn-sm" href="/"><i class="fa fa-home"></i></a>
			@if(isset($articles) && is_object($articles))
			@if($articles->count() == 1)
			<a class="nav-item Xnav-link btn btn-primary btn-sm return_to_article_button" href="{{$articles->first()->url}}"><i class="fa fa-arrow-left"></i> <span class="hidden-sm-down">Return to Article</span></a>
			<a class="nav-item nav-link btn btn-primary btn-sm edit_article_button" href="{{$articles->first()->url}}/edit"><i class="fa fa-pencil"></i> <span class="hidden-sm-down">Edit Article</span></a>
			<button class="nav-item xnav-link btn btn-success btn-sm" type="submit" form="editorForm"><i class="fa fa-save"></i> <span class="hidden-sm-down">Save</span></button>
			@endif						
			@if(request('article_version'))
			<a class="nav-item xnav-link btn btn-danger btn-sm" rel="tooltip" title="This will open the editor with this version of the article. Save to make this the current version." href="{{$articles->first()->url}}/edit?article_version={{request('article_version')}}"><i class="fa fa-pencil"></i> <span class="hidden-sm-down">Restore Article Version</span></a>
			@endif
			@endif
			@if(edit_mode())
			<a class="nav-item xnav-link btn btn-success btn-sm redirectToNoQueryString" href><i class="fa fa-pencil"></i> <span class="hidden-sm-down">Exit Edit Mode</span></a>
			
			@else
			<a class="nav-item xnav-link btn btn-primary btn-sm edit_mode_button" href="?edit_mode=1"><i class="fa fa-pencil"></i> <span class="hidden-sm-down">Edit Mode</span></a>
			<div class="nav-item xnav-link btn btn-primary btn-sm loadcontents settings_button" data-name="Settings" data-toggle="modal"
				@if(isset($articles) && is_object($articles))								
				data-modal_handlebars_template="article_settings"
				@else
				data-modal_handlebars_template="page_settings"
				@endif
					href="#editor_modal"><i class="fa fa-cog"></i> <span class="hidden-sm-down">Settings</span>
			</div>
			
			@endif
			
			<a class="nav-item xnav-link btn btn-primary btn-sm" data-container="#editorouter" data-trigger="focus" href="#" data-toggle="popover" data-placement="bottom" data-content='<a class="ml-0 btn btn-block btn-primary btn-sm" data-modal_handlebars_template="new_article_modal_template" data-toggle="modal" data-name="Add an Article" data-target="#multi_modal" href="/article/-/edit">Article</a><a class="ml-0 btn btn-block btn-primary btn-sm" data-modal_handlebars_template="new_page_modal_template" data-toggle="modal" data-target="#multi_modal" href="/page/-/edit" data-name="Add a Page">Page</a>'><i class="fa fa-plus"></i> <span class="hidden-sm-down">New</span></a>
			
			<a class="nav-item xnav-link btn btn-primary btn-sm" href="/dashboard"><i class="fa fa-dashboard"></i> <span class="hidden-sm-down">Dashboard</span></a>
			
			
						<a href="/filemanager" class="nav-item xnav-link btn btn-primary btn-sm js-window mr-auto" title="Open Filemanager in another window" rel="tooltip"><i class="fa fa-external-link"></i> Content Browser</a>
				
			@if(!auth()->user()->social_account->isEmpty())
			@if(auth()->user()->social_account->first()->avatar)
			<a href="/dashboard/users/{{auth()->user()->id}}"><img style="max-height: 30px; " class="img-circle xpull-right hidden-sm-down mr-2" src="{{auth()->user()->social_account->first()->avatar}}"></a>
			@endif
			<a class="Xpull-right nav-item xnav-link hidden-sm-down" href="/dashboard/users/{{auth()->user()->id}}">{{auth()->user()->social_account->first()->name}}</a>
			@endif
			<form method="post" class="nav-item" action="/logout">{!! csrf_field() !!}
			<button class="xnav-item xnav-link Xpull-right btn btn-danger btn-sm"><i class="fa fa-sign-out"></i><span class="hidden-sm-down">Sign Out</span></button>
			</form>
		</div>
	</nav>





<div class="uploadcover dz-outercontainer"><span>Drop to Upload</span></div>
<div id="dz-previews" class="dz-outercontainer not-filled XXXdropzone"></div>


<!-- the multi-modal -->
<div class="modal fade mt-3" id="multi_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="padding: 10px;">
        <h6 class="modal-title"> &nbsp;</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="
            position: absolute;
		    top: 10px;
		    right: 10px;
    "><span aria-hidden="true"><i class="fa fa-close"></i></span></button>        
      </div>
      <div class="modal-body">
        
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- the editor_modal -->
<div class="modal fade mt-3" id="editor_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="padding: 10px;">
        <h4 class="modal-title"> &nbsp;</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="
            position: absolute;
		    top: 10px;
		    right: 10px;
    "><span aria-hidden="true"><i class="fa fa-close"></i></span></button>        
      </div>
      <div class="modal-body">
        
        
      </div>
      <div class="modal-footer">
		<button type="submit" form="modal_editor_form" class="formsubmitterXX btn btn-primary btn-lg">Save</button>	      
      </div>
    </div>
  </div>
</div>

@include('pub::parts.design_toolbar')

</div> <!-- close .editor-bootstrap -->
<script type="text/javascript">
	var _token = '{{csrf_token()}}';
	var csrf='{{csrf_token()}}';	
	var projectPath = '';
	window.CKEDITOR_BASEPATH = '/vendor/pub/editor/js/ckeditor/';
	var item;
	var sbitem; 
	var btn; 
	var btn1; 
	var btn2;
	var article_id="@if(isset($articles) && is_object($articles) && !$articles->isEmpty()) {{$articles->first()->id}} @else 0 @endif";
	article_id = article_id.trim();
	console.log(article_id);
</script>

@if(auth()->check())
@include('pub::handlebars_templates.private')
	<script>
		var usernames = {!! G3n1us\Pub\Models\User::pluck('username') !!};
	</script>

@endif
<script src="/vendor/pub/editor/js/pub-editor.js" data-ace-base="/vendor/pub/js/ace/src-min-noconflict" type="text/javascript" charset="utf-8"></script>
