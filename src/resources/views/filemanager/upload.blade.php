@extends('filemanager.core')

@section('content')

					<h1 class="text-center" style="text-shadow: 1px 1px 1px gray, 2px 2px 1px gray, 3px 3px 1px gray, 4px 4px 1px gray; color:white;">Upload Files</h1>
					<p><a href="/ajax/ProjectFiles/filemanager" class="btn btn-default"><i class="fa fa-chevron-left"></i> Back to Site Files</a></p>
					<form action="" class="well margin-top40 form-horizontal" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<div class="col-sm-12">
								<input class="form-control" style="padding: 25px 20px; height: auto; " type="file" multiple name="upload[]"/>
							</div>
						</div>
						@if($numfiles) <div class="well well-sm"><code> {{ $successfiles }} out of {{ $numfiles }} uploaded. </code></div> @endif
						{!! $message !!}
						
						<div class="form-group">
							<div class="col-sm-3 text-center">
								<div class="checkbox">
									<label><input type="checkbox" name="skip_duplicates" value="1"> Skip duplicate files</label>	
								</div>	
							</div>
							<label class="col-sm-2 control-label">Directory</label>
							<div class="col-sm-7">
								<input name="folder" placeholder="directory/folder" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">Filename <small>(Overrides original name. If multiple files, will be numbered sequentially)</small></label>
							<div class="col-sm-7">
								<input name="userfilename" placeholder="filename (no extension)" class="form-control">
							</div>
							
						</div>
						<div class="form-group">	
							<div class="col-sm-12">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">								
								<button class="btn btn-success btn-lg" type="submit">Upload</button>
							</div>
						</div>
					</form>

@endsection

