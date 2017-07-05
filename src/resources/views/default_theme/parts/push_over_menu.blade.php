<div class="subpage-sidebar-clicker Xhidden-md-up fullheight"></div>
<div class="subpage-sidebar fullheight" style="top: 0;">
    <div class="mt-2"></div> <!-- spacer -->
	<div class="sidebarinside text-center">		
@if(isset($articles) && is_object($articles))
@foreach($articles as $ind => $article)
@if($ind < 5)
		<div class="card card-transparent ">
			<a href="{$article->link}" title="{{$article->link}}">
				<div class="embed-responsive embed-responsive-1by1">
					<div class="embed-responsive-item card-img-top" style="background-image: url('{{object_get($article,"photo.thumb")}}');background-position: center center; background-size: cover; " title="{{$article->short_title}}"></div>
				</div>
				<div class="card-block">
					<h6 class="sidebartext card-text text-center">{{$article->short_title}}</h6>
				</div>
			</a>
		</div>	
@endif	
@endforeach
@endif
	</div>
</div>

<button type="button" class="btn btn-info btn-lg sidebar-toggle hidden_not_in" style="position: fixed; right: 20px; top:20px; z-index: 999"><i class="fa fa-arrow-left fa-lg fa-fw"></i></button>