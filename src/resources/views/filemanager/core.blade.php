<!DOCTYPE HTML>
<html class="editor-bootstrap">
	<head>
		<title>Filemanager | @yield('filemanager_page')</title>
	@include('pub::parts.head')
		<script>
			if(opener && opener.filemanagerwindow)
				setInterval(function(){ opener.filemanagerwindow = window; }, 500); 
		</script>
		<style>
		[data-path]{
			cursor: pointer;
		}
			
		.folderlink{
			padding: 10px 20px;
			border-radius: 10px;
			text-align: center;
			color: white;
			float: left;
/* 			margin-right: 20px; */
			cursor: pointer;
		}
		.folderlink.active{
			border: solid 2px red;
		}
		
		.imagehider{
			width: 100%; 
			height: 100px; 
			overflow: hidden; 
		}
		
		.nav-tabs>li>a{
			color: white;
		}
		.nav-tabs>li.active>a, .nav-tabs>li.active>a:hover, .nav-tabs>li.active>a:focus, .nav-tabs>li>a:hover{
			background-color: #241F1F;
			color: white;
		}
		
	#file-manager .card-columns{
		column-count: 2;
	}
	
	#file-manager .card-block .editimageform{
		display: none;
	}
	.card{
-webkit-column-break-inside: avoid;
          page-break-inside: avoid;
               break-inside: avoid;		
	}
	.sortable-ghost{
		opacity: .3;
	}
		
		
		@media(min-width:768px){
		
			.imagehider{
				width: 100%; 
				height: 170px; 
				overflow: hidden; 
			}
			
		}
		
	#editorbar, #right_slidepanel{
		display: none;
	}
		</style>
		
	</head>
	<body style="background-image: url(http://d11685ghly9409.cloudfront.net/black-Linen.png)">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					
					@yield('body')

				</div>
			</div>
		</div>
		
		<script>
	$(document).on('click', '[data-article_id]', function(e){
		e.preventDefault();
		opener.targetinput.val($(this).data('article_id'));
		window.close();
	});
			
		</script>
	</body>
</html>