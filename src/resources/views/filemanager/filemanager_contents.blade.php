<style>
	@media(min-width: 576px){
/*
		#filemanager_blade_outer .tab-pane .card-columns{
			column-count: 2;
			-webkit-column-count: 2;
			-moz-column-count: 2;
		}
*/
	}
</style>
<div id="filemanager_blade_outer">
	
<h1 class="text-center" style="text-shadow: 1px 1px 1px gray, 2px 2px 1px gray, 3px 3px 1px gray, 4px 4px 1px gray; color:white;">Site Browser</h1>	
					
<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
	<li class="nav-item"><a class="nav-link active" data-tab="files" href="#filestab" role="tab" data-toggle="tab">Files</a></li>
	<li class="nav-item"><a class="nav-link" data-tab="pages" href="#pagestab" role="tab" data-toggle="tab">Pages</a></li>
	<li class="nav-item"><a class="nav-link" data-tab="articles" href="#articlestab" role="tab" data-toggle="tab">Articles</a></li>
	<li class="nav-item"><a class="nav-link" data-tab="articles" href="#uploadtab" role="tab" data-toggle="tab">Upload</a></li>
<!-- 	<li><a href="/ajax/ProjectFiles/add_site_files">Upload</a></li> -->
</ul>	
				
<div class="tab-content">
  <div class="tab-pane active" id="filestab">
		<div class="clearfix"></div>
		<div id="files" class="mt-2">
			<p class="text-center" style="text-align: center;">
			<i class="fa fa-cog fa-spin fa-2x"></i>
			</p>					
		</div>
		<div class="clearfix"></div>	  
		<div id="files_nav" data-tab="files" class="text-center" style="text-align: center;"></div>		
  </div>
  <div class="tab-pane" id="pagestab">
		<div class="clearfix"></div>
		<div id="pages" class="mt-2">
			<p class="text-center" style="text-align: center; color: blue;">
			<i class="fa fa-cog fa-spin fa-2x"></i>
			</p>
		</div>
		<div class="clearfix"></div>
		<div id="pages_nav" data-tab="pages" class="text-center" style="text-align: center;"></div>		
  </div>
  <div class="tab-pane" id="articlestab">
		<div class="clearfix"></div>
		<div id="articles" class="mt-2">
			<p class="text-center" style="text-align: center; color: blue;">
			<i class="fa fa-cog fa-spin fa-2x"></i>
			</p>
		</div>
		<div class="clearfix"></div>
		<div id="articles_nav" data-tab="articles" class="text-center" style="text-align: center;"></div>		
  </div>
  <div class="tab-pane" id="uploadtab">
		<div class="clearfix"></div>
		<div id="filebrowseruploadtab" class="mt-2">
			Drag Files Here<br><small>Or click to upload</small>
		</div>
		<div class="clearfix"></div>
		<div id="articles_nav" data-tab="articles" class="text-center" style="text-align: center;"></div>		
  </div>
</div>					
</div>
