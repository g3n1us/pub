
<header class="pt-6 header" style="background-image: none;">
	<nav class="topbar flex-row navbar nav fixed-top navbar-dark bg-inverse mb-1 justify-content-between" style="z-index: 900;">
		
 		&#9776; 
		<a href="/"><img src="/vendor/pub/files/SpringfieldShopper.png" style="max-height: 25px; background-color: white;" alt="SpringfieldShopper" id="washington-examiner-logo" alt="SpringfieldShopper"></a>
		@verbatim
		<script type="text/template" id="header_article_list">
			{{#each this}} <a style="color:#fff" href="{{url}}" class="mr-1"><small class="text-danger">{{relative_date}}</small> {{str_limit short_title 40}}</a> {{/each}}
		</script>
		@endverbatim
		<div class="mr-auto hidden-sm-down" data-handlebars_template="header_article_list" data-sourceurl="/ajax/articles?limit=2&paginate=0"></div>
		<div class="">
			<a style="text-decoration: none;" href="/search"><i class="fa fa-search text-muted fa-lg ml-1"></i> </a>
			<a><i class="fa fa-youtube text-muted fa-lg ml-1"></i></a>
			<a><i class="fa fa-twitter text-muted fa-lg ml-1"></i></a>
			<a><i class="fa fa-facebook text-muted fa-lg ml-1"></i></a>
		</div>	
	</nav>
	
	<div id="top-banner-ad" class="m-b-3">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="mdc--ad" data-size="970x250"></div>
				</div>
			</div>
		</div>			
	</div>
	
	<div class="container mainnav-container ">
		@if(session('error', null))
		<div class="alert alert-danger">{{session('error')}}</div>
		@endif
		<div class="row">
			<div class="col-md-12">
				<date class="text-muted nav-item" style=" line-height: 2; min-width: 170px;"><small>{{ \Carbon\Carbon::now()->toDayDateTimeString() }}</small></date>

							<a href="/" class="hidden-lg-up">
								<img src="/vendor/pub/files/SpringfieldShopper.png" alt="SpringfieldShopper" id="washington-examiner-logo" alt="SpringfieldShopper">
							</a>

				

				<nav class="mainnav navbar navbar-light navbar-toggleable-md mb-3 justify-content-between">
					<button class="navbar-toggler navbar-toggler-right ml-auto" type="button" data-toggle="collapse" data-target="#main-nav-items" aria-controls="main-nav-items" aria-expanded="false" aria-label="Toggle navigation" style="border:none; position: relative; right: initial;"><span class="navbar-toggler-icon"></span></button>
					
					
					<div class="collapse navbar-collapse" id="main-nav-items">
						<div class="Xnav navbar-nav nav-fill XXnav-justified navbar-light justify-content-between" style="flex-grow: 1;">
							<a href="/" class="hidden-md-down">
								<img class="washington-examiner-logo-text-only" id="washington-examiner-logo" src="/vendor/pub/files/SpringfieldShopper.png" alt="SpringfieldShopper">
							</a>
 							{!! show_area('main_navigation') !!}
						    <a class="nav-item text-muted  nav-link active" href="/author">Authors <span class="sr-only">(current)</span></a>
						    <a class="nav-item text-muted  nav-link" href="/search">Search</a>
						    <a class="nav-item text-muted  nav-link" href="/by-date/today">Today</a>
						    <a class="nav-item text-muted  nav-link" href="/tag/video">A.V Room</a>
						    <a class="nav-item text-muted  nav-link" href="#">Magazine</a>
						    <a class="nav-item text-danger nav-link " href="#">Sign Up</a>
						
						</div>
					</div>
					
				</nav>							
			</div>
		</div>
	</div>			
</header>
