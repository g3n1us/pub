@if(auth()->check())
@include('pub::handlebars_templates.private')
	<script>
		var usernames = {!! G3n1us\Pub\Models\User::pluck('username') !!};
	</script>

@endif
	<!-- JS -->
	<script src="/vendor/pub/dist/js/public-compiled.js"></script>
	<script>
		var handlebars_templates = {};
		var raw_handlebars_templates = [];
		$('script[type*="template"]').each(function(){
			var thisid = $(this).attr('id');
			thisid = thisid.replace(/-/g, "_");
			var source  = $(this).html();
			if(typeof $(this).data('default') === "undefined") var usedata = false;
			else usedata = $(this).data('default');
			raw_handlebars_templates.push({ "id": thisid, "source": source, "datasource": usedata});
			handlebars_templates[thisid] = Handlebars.compile(source);
		});
		
	</script>	
	
	<script src="/vendor/pub/js/includes/leprechaun.js"></script>	
	<script type="text/javascript" src="/vendor/pub/js/includes/Caret.js-master/dist/jquery.caret.min.js"></script>
	<script type="text/javascript" src="/vendor/pub/js/includes/At.js-master/dist/js/jquery.atwho.min.js"></script>
	
	<script>
if(typeof usernames !== "undefined"){
	$('#inputor').atwho({
	    at: "@",
	    data:usernames
	});	
}		
		
		
		$('[style="width:720px;"]').remove();
		$('[style="display:table;padding:0 8px 0 0;font-size:11px;color:dimgray;margin-bottom:8px;"] > blockquote').unwrap();
		
		function twitterTweeter($elem, child){
			if($elem.hasClass('tweeter-handler-attached')) return;
			
			var tweettext = child ? $elem.find(child).text() : $elem.text();
			var intentRegex = /twitter\.com\/intent\/(\w+)/,
				windowOptions = 'scrollbars=yes,resizable=yes,toolbar=no,location=yes',
				width = 550,
				height = 420,
				winHeight = screen.height,
				winWidth = screen.width;	
                var left = Math.round((winWidth / 2) - (width / 2));
                var top = 0;
		if (winHeight > height) {
			top = Math.round((winHeight / 2) - (height / 2));
		}			
	        var tweettext = 'https://twitter.com/intent/tweet?text=' + encodeURIComponent(tweettext.trim()) + '&via=dcexaminer&url=' + window.location.href	
	        $elem.addClass('tweeter-handler-attached').css('cursor', 'pointer').click(function(){
				window.open(tweettext, 'intent', windowOptions + ',width=' + width + ',height=' + height + ',left=' + left + ',top=' + top);	
	        });	
		}
		
		setTimeout(function(){
			$('.twitter-tweeter').each(function(){
				twitterTweeter($(this));
			});
			
		}, 2000);
		
		
		var t;
		function gText(e) {
//			console.log(e);
			if(!$(e.target).parents('.article').length) return;
		    t = (document.all) ? document.selection.createRange().text : document.getSelection();
		
		    if(t && t.toString().length) {
			    var pelem = t.anchorNode.parentElement;
			    if($(pelem).find('mark').length) return;
			    if($(pelem).is('mark')) return;
			    var tweettext = t.toString();
			    pelem.innerHTML = pelem.innerHTML.replace(t, '<mark class="bg-twitter" tabindex="1" style="outline:none">'+t+'</mark>');
			    $mark = $(pelem).find('mark');
			    var $content = $("<div />");
			    $content.html('<a class="btn btn-primary" href="https://twitter.com/intent/tweet?text=' + encodeURIComponent(tweettext) + '&via=dcexaminer&url=' + window.location.href +'">' + tweettext.length + ' <i class="fa fa-twitter"></i></a>');

			    $mark.popover({
				    html: true,
					content: $content,
					container: 'body',
					trigger: 'focus',
					offset: '50% -20',
					template: '<div class="popover-twitter" role="tooltip"><div class="popover-arrow"></div><h3 class="popover-title"></h3><div class="popover-content p-0"></div></div>',
					title: '',
				});
				$mark.focus();
//				$mark.popover('show');
			    console.log(t);
			    console.log(pelem);
			    
			    $mark.on('hidden.bs.popover', function(){
				    $mark.popover('dispose');
				    $mark.before($mark.text()).remove();
			    });
		    }
		}
		
		$(document).on('mouseup', '.article p', gText);
//		document.onmouseup = gText;	
		$(document).on('click', 'mark', function(e){
// 			$(this).popover().popover('show');
// 			$(this).popover('show');
// 			alert($(this).data('text'))
		});
		
		$(document).on('click', '.article-date, [data-date]', function(e){
			e.preventDefault();
		});
		
		$('.article-date, [data-date]').each(function(){
			date_linker($(this));					
		});
		
		function date_linker($this){
			if(!$this.is('.article-date, [data-date]'))
				$this = $this.find('.article-date, [data-date]');
			if(!$this.length) return;
			if($this.data('date_linked')) return;
			
			var link = $this.attr('href');
			var datestring = $this.data('date') || $this.text();
//			alert(datestring);
			var d = new Date(datestring);
			// console.log($(this).text());
			var month = d.toLocaleString("en-us", { month: "long" });
			var content = '<date>'+$this.text() + '</date><br><a class="btn btn-sm btn-primary has_tooltip" title="Articles from '+month+' '+d.getFullYear()+'" href="'+link+'/month">'+month+'</a> ' +
			'<a title="Articles from '+month+' '+d.getDate()+' '+d.getFullYear()+'" class="btn btn-sm btn-primary has_tooltip" href="'+link+'">'+d.getDate()+'</a> ' +
			'<a title="Articles from '+d.getFullYear()+'" class="btn btn-sm btn-primary has_tooltip" href="'+link+'/year">'+d.getFullYear()+'</a> ';
			$this.popover({
				html: true,
				content: content,
				title: 'View Articles by Date',
				trigger: 'focus',
				offset: '48% 0',				
				//delay: 1000,
			});	
			$this.on('shown.bs.popover', function(e){
				$('.has_tooltip').tooltip({ placement: 'bottom' });
			});
			$this.data('date_linked', true);
		}
		
		
		$(document).on('submit', 'form', function(){
			$(this).find('[type="submit"]').html('<i class="fa fa-spin fa-cog"></i>').addClass('disabled');
		});
		
		$(document).on('change', '[name="metadata->lead_photo"]', function(e){
			$('[name="metadata->lead_photo"]').not(this).attr('checked', false);
		});
		
		
		var adsizes = [
			'720x300',
			'720x90',			
			'300x250',
			'300x600',
			'300x400',
			'300x300',
			'250x250',			
			'234x60'
		];
		
		$('.pub--ad').each(function(){
			var size = $(this).data('size') || '728x90';
			var wh = size.split('x');			
			var targetwidth = $(this).parent().width();
			var targetheight = parseInt(wh[1]);
			if(parseInt(wh[0]) > targetwidth){
				var autofound = false;
				adsizes.forEach(function(s){
					if(!autofound){
						var s2 = s.split('x');
						if(parseInt(s2[0]) < targetwidth && parseInt(s2[1]) < targetheight){
							autofound = true;
							size = s;
							wh = s.split('x');
							console.log(size);
						}						
					}
				});
			}
			var $ad = $('<img />');
			$ad.attr('data-src', 'holder.js/'+size+'?text='+size+' Advertisement - '+targetwidth+'&random=yes').css({
				display: 'block',
				margin: 'auto',
			});
			$(this).css({
				width: parseInt(wh[0]) + 24,
				height: parseInt(wh[1]) + 24,
				// margin: 'auto',
			})
			.removeClass('mdc--ad')
			.addClass('ad mb-1')
			.html($ad);
		});
		var app = {};
		app.click = 'click';
		app.sidebarTogglersSelectors = '.sidebar-toggle, .subpage-sidebar-clicker';
		app.sidebarTogglers = $(app.sidebarTogglersSelectors);
		app.sidebarElementsSelectors = '.subpage-sidebar, .subpage-sidebar-clicker, .sidebar-toggle';
		app.sidebarElements = $(app.sidebarElementsSelectors);
		
		$(document).on(app.click, app.sidebarTogglersSelectors, function(e){
			e.stopPropagation();
			app.sidebarElements.toggleClass('in');
			$('body').toggleClass('overflow-hidden');
		});
		
		$(document).on('touchmove', '.subpage-sidebar-clicker.in', function(e){
			e.preventDefault();
		// 	alert('s');
		// 	return false;
		});
		
		$('.area_loader').each(function(){
			var edit_mode = '@if(edit_mode())?edit_mode=1 @endif';
			$(this).load($(this).data('loadurl')+edit_mode);
		});
		
var application_windows = {};
		
function popupwindow(url, title, w, h) {
	if(!title) var title = "Window";
	if(!w) var w = 800;
	if(!h) var h = 600;
	var left = (screen.width/2)-(w/2);
	var top = (screen.height/2)-(h/2);
	if(url in application_windows && typeof application_windows[url] == "window")
		application_windows[url].focus();
	else
		application_windows[url] = window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		
	return application_windows[url];
}	

function is_url(t){
	var expression = /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi;
	var regex = new RegExp(expression);
	return t.match(regex);	
}

function parse_url(url){
	var parser = document.createElement('a');
	parser.href = url;
	return {
		protocol: parser.protocol,
		hostname: parser.hostname,
		port:     parser.port,   
		pathname: parser.pathname,
		search:   parser.search,
		hash:     parser.hash, 
		host:     parser.host,   	
	};
}

$(document).ready(function(){
	$('[data-oembed_url]').each(function(){
		var url = $(this).data('oembed_url');
		var parsed_url = parse_url(url);
		var id = parsed_url.pathname.split('/');
		id = id[id.length - 1];
		var $this = $(this);
		$.get('/ajax/article/'+id+'?html=1&template=models.article.standard', function(data){ 
			$this.html(data);
			date_linker($this);
		});
	});
	
});
	
	
var www;		
$(document).on('click', '.js-window', function(e){
	e.preventDefault();
	var w = 990, h = 600;
	if($(this).data('window_size')){
		var sizes = $(this).data('window_size').split('x');
		w = sizes[0];
		h = sizes[1];
	}
	www = popupwindow($(this).attr('href'), $(this).attr('href'), w, h);
});


$(document).on('click', '[data-filepicker]', function(e){
	e.preventDefault();
	
	// $(this).off('focus');
	$input = $($(this).data('filepicker'));
	window.targetinput = $input;	
	window.pickerwindow = popupwindow('/filemanager', 'Filemanager', 900, 600);
	$(pickerwindow).on('load focus', function(){
		pickerwindow.mode = 'article_id';
		window.targetinput = $input;
	});		
});	

$(document).on('click', '.pub--onajax', function(e){
	e.preventDefault();
	
	var url = $(this).attr('href') || $(this).data('href');

	var request = $.ajax({
		url: url,
		type: "POST",
		data: {
			_token: _token,
		}
	});
	
	request.done(function( data ) {
		console.log(data);
		alert(data.message);
	});
	
	request.fail(function( data, jqXHR, textStatus ) {
		console.log(data);
		alert( "Request failed: " + textStatus );
	});	

});
	</script>
