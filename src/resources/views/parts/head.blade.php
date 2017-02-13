	<meta charset="utf-8">
	<!--[if IE]><![endif]-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<!-- 	<link href="https://fonts.googleapis.com/css?family=Roboto+Slab:300,400,700" rel="stylesheet"> -->
	<link href="https://fonts.googleapis.com/css?family=Roboto+Mono|Roboto+Slab:100,300,400,700|Roboto:300,400,400i,500,500i,700,700i&amp;subset=latin-ext" rel="stylesheet">	
	<link rel="shortcut icon" href="/wex-favicon.png" />

 	<title>{{$heading or ''}}</title> 
 	<meta name="description" content=""> 
 	<meta name="keywords" content="" /> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- !Bootstrap -->
	<link rel="stylesheet" href="/vendor/pub/dist/css/both-compiled.min.css">	
	<link rel="stylesheet" href="/vendor/pub/dist/css/public-compiled.min.css">
	<link rel="stylesheet" href="/vendor/pub/additional.css?v=1234">
	<link rel="stylesheet" href="/vendor/pub/js/includes/At.js-master/dist/css/jquery.atwho.min.css">
@if(Auth::check())
	<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,400i,700" rel="stylesheet">
	<link rel="stylesheet" href="/vendor/pub/dist/css/private-compiled.min.css">
@endif
	<link rel="stylesheet" href="/vendor/pub/_assets/js/vendor/ckeditor_4.6/plugins/prism/lib/prism/prism_patched.min.css">
	<script src="/vendor/pub/_assets/js/vendor/ckeditor_4.6/plugins/prism/lib/prism/prism_patched.min.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/modernizr/modernizr-2.8.3.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script>
		var article; // do this a better way. This is just to avoid errors for Vue.js
	</script>
	<style>
		body{
			font-family: 'Roboto', sans-serif;
		}
		h1,h2,h3,h4,h5,h6,.h1,.h2,.h3,.h4,.h5,.h6{
			font-family: 'Roboto Slab', serif;
		}
		pre, code{
			font-family: 'Roboto Mono', monospace;
		}
	</style>