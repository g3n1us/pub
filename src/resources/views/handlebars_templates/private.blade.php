
@verbatim
<script type="text/x-handlebars-template" id="edit_popover_template">
    <div id="edit_popover" class="ccm-menu ccm-ui" tabindex="0" style="outline: 0; display: block; top: {{top}}px; left: {{left}}px; position: absolute; ">
        <div class="popover below">
            <div class="arrow"></div>
            <div class="inner">
                <div class="content">
                    <ul>
                        <li><a class="ccm-menu-icon ccm-icon-edit-menu" Xonclick="ccm_hideMenus()" dialog-title="" dialog-append-buttons="true" dialog-modal="false" dialog-on-close="ccm_blockWindowAfterClose()" dialog-width="550" dialog-height="420" data-editoraction="EDITOR--edit_button" data-bID="{{bid}}">Edit</a></li>

<!--                          <li><a class="ccm-menu-icon ccm-icon-clipboard-menu" data-bID="{{bid}}">Copy to Clipboard</a></li>  -->

                         <li><a class="ccm-menu-icon ccm-icon-move-menu" data-editoraction="EDITOR--move_block" data-direction="up" data-bID="{{bid}}">Move Up</a></li>

                         <li><a class="ccm-menu-icon ccm-icon-move-menu" data-editoraction="EDITOR--move_block" data-direction="down" data-bID="{{bid}}">Move Down</a></li>

                        <li><a class="ccm-menu-icon ccm-icon-delete-menu" data-editoraction="EDITOR--delete_block" data-bID="{{bid}}">Delete</a></li>
                         <li><a class="ccm-menu-icon ccm-icon-design-menu" onclick="selectArea('{{tmpid}}')">Design</a></li>
                         
                         <!-- <li><a class="ccm-menu-icon ccm-icon-custom-template-menu">Custom Template</a></li> -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</script>
@endverbatim

<script type="text/x-handlebars-template" id="new_page_modal_template">
	<form action="/page" method="post">
		<fieldset class="form-group">
			<label for="new_page_modal_name">Name</label>
			<input type="text" id="new_page_modal_name" class="form-control" data-sluggify="#new_page_modal_path" autofocus name="name">
		</fieldset>
		
		<fieldset class="form-group">
			<label for="new_page_modal_path">Path</label>
			<input type="text" id="new_page_modal_path" class="form-control" name="url">
		</fieldset>
		
		<fieldset class="form-group">
			<label for="new_page_modal_description">Description</label>
			<input type="text" id="new_page_modal_description" class="form-control" name="description">
		</fieldset>
		
		<fieldset class="form-group">
			{!! csrf_field() !!}
			<button type="submit" class="btn btn-primary">Submit</button>
		</fieldset>
	<form>
</script>


<script type="text/x-handlebars-template" id="new_article_modal_template">
	<form action="/article" method="post">
		<fieldset class="form-group">
			<label for="new_article_modal_title">Title</label>
			<input type="text" id="new_article_modal_title" class="form-control" data-sluggify="#new_article_modal_slug" autofocus name="title">
		</fieldset>
		
		<fieldset class="form-group">
			<label for="new_article_modal_slug">Slug</label>
			<input type="text" id="new_article_modal_slug" class="form-control" name="slug">
		</fieldset>
		
		<fieldset class="form-group">
			<label for="new_article_modal_summary">Summary</label>
			<input type="text" id="new_article_modal_summary" class="form-control" name="summary">
		</fieldset>
		
		<fieldset class="form-group">
			{!! csrf_field() !!}
			<input type="hidden" name="approved" value="0">
			<button type="submit" class="btn btn-primary">Submit</button>
		</fieldset>
	<form>
</script>


<script type="text/x-handlebars-template" id="files_template">
<div class="card-columns">	
@verbatim
	{{#each this.data}}
	<div class="card">
		<img class="card-img-top img-fluid" data-id="{{ id }}" data-path="{{ medium }}" src="{{ square }}">
		<i class="fa fa-pencil" data-toggle="collapse" data-target="#edit-files-box-{{id}}" style="position: absolute; top: 3px; right: 3px;"></i>
		<div class="collapse" id="edit-files-box-{{id}}">
		<div class="card-block" style="color: black">
			<form action="/filesave/{{ id }}/{{ ../article.id }}" class="editimageform" method="post">
				@endverbatim
				{!! csrf_field() !!}
				@verbatim
				<div class="form-group">
					<label>Credit</label>
					<input type="text" class="form-control" name="metadata->credit" value='{{credit}}'>
				</div>
				
				<div class="form-group clearfix">
					<input type="submit" value="Save Changes" class="btn btn-primary">
					<a class="btn btn-sm btn-xs btn-danger pull-right" data-toggle="collapse" href="#remove-files-box-{{id}}"><i class="fa fa-trash"></i></a>
					
				</div>
				<div class="collapse" id="remove-files-box-{{id}}">
				<fieldset class="form-group">
					<button type="submit" onclick="if(!confirm('Are you sure? This will permanently delete the file and dissassociate it from all articles.')) return false" name="_method" value="DELETE" class="btn btn-block btn-sm btn-danger m-t-1">Delete File</button>									
				</fieldset>
				</div>
			</form>	
		</div>
		</div>
<!-- 		<span class="navbar-light"><div class="navbar-toggler"></div></span> -->
	</div>	
	{{/each}}
	@endverbatim
</div>
</script>
			
<script type="text/x-handlebars-template" id="pages_template">
<div class="card-columns">	
	@verbatim
	{{#each this.data}}
	<div class="card card-block">
		<img class="card-img-top img-fluid" data-path="{{url}}" src="{{#if lead_photo}}{{lead_photo.square}}{{/if}}">
		<i class="fa fa-pencil" data-toggle="collapse" data-target="#edit-pages-box-{{id}}" style="position: absolute; top: 3px; right: 3px;"></i>
		<a data-path="{{url}}" href="{{url}}">{{name}}</a>
		<div class="collapse" id="edit-pages-box-{{id}}">
		
		<div class="card-block" style="color: black">
<!-- 			<form action="" method="post"> -->
				@endverbatim 
				{!! csrf_field() !!}
				@verbatim
				<div class="form-group">
					<label>Name</label>
					<input type="text" class="form-control" name="title" value='{{name}}{{title}}'>
				</div>
				
				<div class="form-group">
					<label>URL</label>
					<input type="text" class="form-control" name="url" value='{{url}}'>
				</div>
				
<!-- 			</form>	  -->
		</div>
		</div>
	</div>	
	{{/each}}
	@endverbatim
</div>
</script>

<script type="text/x-handlebars-template" id="tags_datalist">
@verbatim
{{#each this}} 
<option>{{this}}</option>
{{/each}}
@endverbatim
</script>


<script type="text/template" id="newauthortemplate"><input type="hidden" name="authors[]"> </script>

<script type="text/template" id="newgrouptemplate"><input type="hidden" name="groups[]"> </script>

<script type="text/template" id="newtagtemplate"><input type="text" list="alltags" name="tags[]" placeholder="Add Tag Name" class="tokenable_input form-control margin-bottom10 remove_if_empty_on_save"> </script>


<script id="article_versions_template" type="text/template">
@verbatim
{{#each this}} 
<option value="{{VersionId}}">Version {{index}} - {{date_diff}} - {{LastModified.date}}</option>
{{/each}}
@endverbatim
</script>

<script type="text/x-handlebars-template" id="page_settings">
@include('pub::parts.page_settings')
</script>


<script type="text/x-handlebars-template" id="article_settings">
@include('pub::parts.article_edit_tab_meta')
</script>


<script type="text/x-handlebars-template" id="articles_template">
@verbatim
	<div>{{{this.links}}}</div>
<div class="card-columns">	
	{{#each this.data}}
	<div class="card">
		<a class="embed-responsive embed-responsive-4by3 card-img-top" data-article_id="{{id}}" title="Article {{id}}" style="background-size: auto 100%; background-position: center center; background-image: url({{#if lead_photo}}{{lead_photo.square}}{{/if}})" draggable="true" data-draggablecontent="text">
			<span class="sr-only">{{url}}</span>
		</a>
		<i class="fa fa-pencil" data-toggle="collapse" data-target="#edit-articles-box-{{id}}" style="position: absolute; top: 3px; right: 3px;"></i>
		<div class="card-block" style="color: black">
			{{name}}{{title}}
		</div>
		<div class="collapse" id="edit-articles-box-{{id}}">
		
		<div class="card-block" style="color: black">
			<form action="/filesave/{{id}}/{{../article.id}}" method="post">
				@endverbatim {!! csrf_field() !!} @verbatim
				
				<div class="form-group">
					<label>URL</label>
					<a href="{{url}}">{{url}}</a>
					<input type="text" class="form-control hidden" name="url" value='{{url}}'>
				</div>
				
				<div class="form-group clearfix">
					<input type="submit" value="Save Changes" class="btn btn-primary">
					<a class="btn btn-sm btn-xs btn-danger pull-right" data-toggle="collapse" href="#remove-files-box-{{id}}"><i class="fa fa-trash"></i></a>
				</div>
				<div class="collapse" id="remove-files-box-{{id}}">
				<fieldset class="form-group">
					<button type="submit" onclick="if(!confirm('Are you sure? This will permanently delete the file and dissassociate it from all articles.')) return false" name="_method" value="DELETE" class="btn btn-block btn-sm btn-danger m-t-1">Delete File</button>									
				</fieldset>
				</div>
			</form>	
		</div>
		</div>
	</div>	
	{{/each}}
</div>
	<div>{{{this.links}}}</div>
	@endverbatim

</script>
