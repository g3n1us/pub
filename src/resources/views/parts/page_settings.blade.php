@if(isset($page))

<form id="page_settings_form" name="page_settings" method="post" action="{{route('page.update', [null,$page], false)}}">
	{!! csrf_field() !!}
	{!! method_field('PUT') !!}
	<fieldset class="form-group">
		<label class="form-control-label">Name</label>
		<input type="text" name="name" value="{{$page->name}}" required class="form-control">
	</fieldset>
	<fieldset class="form-group">
		<label class="form-control-label">URL</label>
		<input type="text" name="url" value="{{$page->url}}" required class="form-control">
	</fieldset>
	<fieldset class="form-group">
		<label class="form-control-label">Description</label>
		<textarea name="description" minlength="25" class="form-control">{{$page->description}}</textarea>
	</fieldset>
	<fieldset class="form-group">
		<label class="form-control-label">Meta Keywords</label>
		<input type="text" name="config->keywords" class="form-control" value="{$page->config->get('keywords')}">
	</fieldset>
	<fieldset class="form-group">
		<label class="form-control-label">Robots</label>
		<select name="config->robots" class="form-control">
			<option value="">Allow All</option>
			<option value="noindex, nofollow" {if $page->config->get('robots') == "noindex, nofollow"}selected{/if}>noindex, nofollow</option>
			<option value="noindex" {if $page->config->get('robots') == "noindex"}selected{/if}>noindex</option>
			<option value="nofollow" {if $page->config->get('robots') == "nofollow"}selected{/if}>nofollow</option>
		</select>	
    </fieldset>
	<fieldset class="form-group">
		<label class="col-form-label form-control-label">Tags</label>
		<div class="Xform-inline">
			@foreach($page->tags as $tag)
			<input type="text" name="tags[]" value="{$tag->name}" placeholder="Add Tag Name" class="tokenable_input form-control margin-bottom10 remove_if_empty_on_save"> 
			@endforeach
			<button type="button" class="btn btn-success margin-bottom10" onclick="$(this).before($('#newtagtemplate').html()).prev().focus()"><i class="fa fa-plus"></i> Add</button>
		</div>				
		<script type="text/template" id="newtagtemplate"><input type="text" list="alltags" name="tags[]" placeholder="Add Tag Name" class="tokenable_input form-control margin-bottom10 remove_if_empty_on_save"> </script>
	</fieldset>
	
	<datalist id="alltags" data-handlebars_template="tags_datalist" data-sourceurl="/ajax/tags?pluck=name&paginate=0"></datalist>
	
</form>
@endif