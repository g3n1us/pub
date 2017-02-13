{extends file="s3:layouts/editor.tpl"}
{block name=bodyclasses}{{if $user}}logged_in bg-inverse{{/if}}{/block}
{block name=body}
	<div class="container-fluid m-t-2 ">
		<div class="row">
			<form method="post">
			<div class="col-md-3 ">
				<div class="form-group">
					<label class="form-label">Description</label>
					<textarea name="description" class="form-control">{$articles->first()}</textarea>
				</div>
				<div class="form-group">
					<label class="form-label">Title</label>
					<textarea name="title" class="form-control h2">{$articles->first()->title}</textarea>
				</div>
				<div class="form-group">
					<label class="form-label">Subtitle</label>
					<input type="text" name="subtitle" class="form-control" value="{$articles->first()->subtitle}">
				</div>
				<div class="form-group">
					<label class="form-label">Author Display</label>
					<input type="text" name="author_display" class="form-control" value="{$articles->first()->author_display}">
				</div>
				<div class="form-group">
					<label class="form-label">Pub Date</label>
					<input type="datetime" name="pub_date" class="form-control" value="{$articles->first()->pub_date}">
				</div>
				<div class="form-group">
					<label class="form-label">Summary</label>
					<textarea name="summary" class="form-control">{$articles->first()->summary}</textarea>
				</div>
				<div class="form-group">
					<label class="form-label">Lead Photo</label>
					<input type="text" name="lead_photo" class="form-control" value="{$articles->first()->lead_photo->hash}">
					<img src="http://cdn.washingtonexaminer.biz/cache/1060x600-{{$articles->first()->lead_photo->hash}}.jpg">					
				</div>
				
				
				
			</div>
			<div class="col-md-9">
				
					<div class="form-group">
						<textarea name="content[body]" class="ckeditor">{$articles->first()->body}</textarea>
					</div>
					{$csrf_field}
					<div class="form-group">
					<button class="btn btn-primary" type="submit">Submit</button>
						</div>
					
				
			</div>
			</form>
		</div>
	</div>




{/block}