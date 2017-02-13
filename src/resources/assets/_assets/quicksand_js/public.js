//auto add csrf protection
var csrftokenfield = $('<input />');   
csrftokenfield.attr('type', 'hidden').attr('name', '_token').val($('meta[name="csrf-token"]').attr('content'));

if($('meta[name="csrf-token"]').length){
	$('form').each(function(){
		if(!$(this).find('[name="_token"]').length){
			$(csrftokenfield).appendTo($(this));
		}
	});				
}



if(window.navigator.standalone){
	$( document ).on("click", "a, [onclick]", function( event ){
        event.preventDefault();
        event.stopPropagation();
 
        var targethref = $(this).attr( "href" );
        if(typeof targethref !== "undefined"){
	        location.href = targethref;
        }
 
    });	
}


$('[data-video]').each(function(){
	var lgsrc = $(this).data('src-lg');
	var smsrc = $(this).data('src-sm');
	var regsrc = $(this).data('video');
	if(typeof lgsrc !== "undefined" && $(window).width() > 1025){
		$(this).find('video').attr('src', lgsrc);
	}
	else if(typeof smsrc !== "undefined" && $(window).width() <= 1025){
		$(this).find('video').attr('src', smsrc);
	}
	else if(typeof regsrc !== "undefined"){
		$(this).find('video').attr('src', regsrc);
	}
});


$('video,audio').not('video.noauto, audio.noauto').mediaelementplayer({
	plugins: ['flash'],
	pluginPath: '/themefile/editor/css/',
	flashName: 'flashmediaelement.swf',
});



if(typeof SITE_IS_REMOTE === "undefined" || !SITE_IS_REMOTE){
	if($('.autonav').length){
		$('.autonav .autogen').remove();
		// set autonav
		$('.autonav').append(templates.autonavItems(projectData.pages));
		// set page settings
		$('.autonav li a[href="' + location.pathname + '"]').parent().addClass('active');
		$('.autonav').fadeIn(100);
		var autonavhtml = $('.autonav').html();
		autonavhtml = autonavhtml.replace(/^\s*[\r\n]/gm, "");
		$('.autonav').html(autonavhtml);
	}
}
else $('.autonav .autogen').remove();



// Add loading indicators to template injectors
$('[data-output]').each(function(){
	if($(this).hasClass('smloader')){
		$(this).html('<i class="fa fa-spin fa-cog"></i>');
	}
	else{
		$(this).html('<h1 class="margin-top100 text-center"><i class="fa fa-spin fa-2x fa-cog"></i></h1>');
	}
});

var alldata;
function data_template_init(){
	var alldatarequest = $.getJSON("/ajax/alldata", function(data){
		alldata = data;
		console.log(alldata);
		// localStorage.alldata = JSON.stringify(alldata);

		outputAllTemplates(alldata);
	
		$.event.trigger({
			type: "dataLoaded",
			message: "Hello World!",
			time: new Date()
		});			
	});
	
	alldatarequest.complete(function(){
	
	});
	console.log(alldata);
	
}

//! INIT get started...
if(typeof SITE_IS_REMOTE === "undefined" || !SITE_IS_REMOTE){
	data_template_init();
}


$(document).on("dataLoaded", function(){
	// This is to enable access to DOM created during template creations
	// console.log('dataLoaded event fired');
	// Add items here.

});




/*
// Usage: Div with content. Hide content you only want visible in modal using class, 'hide'. Give the clickable button (or div) data-name="Title for Modal Header". All content from clicked div, etc is put in modal and unhidden
$(document).on('show.bs.modal', '#multi_modal', function (event) {
	var button = $(event.relatedTarget); 
	var content = button.html();
	var name = button.data('name');
	var modal = $(this);
	modal.find('.modal-title').text(name);
	modal.find('.modal-body').html(content);
	modal.find('.hide').removeClass('hide');
})
*/



$(window).load(function(){
	$('.autonav').css('opacity', '1').fadeIn(100);
	call_content_generators();
});

function call_content_generators(){
	// affix	
	$(".leprechaun-affix").each(function(){
		var thisoffset = $(this).offset();
		$(this).affix({
		  offset: {top: thisoffset.top}
		});
	});
	
	// Google Map
	$('.amw_gmap').not('.amw_gmap.has_been_loaded').each(function(){
		amw_gmap_initialize($(this));
	});
	
	// Twitter Feeds
	load_twitter_feeds();
	
	lep_script_embedder();
	
}

$(document).on('widget_modal_populated', function(){
	call_content_generators();
});



/**
 * Overwrites obj1's values with obj2's and adds obj2's if non existent in obj1
 * @param obj1
 * @param obj2
 * @returns obj3 a new object based on obj1 and obj2
 */
function merge_options(obj1,obj2){
    var obj3 = {};
    for (var attrname in obj1) { obj3[attrname] = obj1[attrname]; }
    for (var attrname in obj2) { obj3[attrname] = obj2[attrname]; }
    return obj3;
}


//! GENERAL PURPOSE FUNCTIONS
//! Get JSON from Spreadsheet Proxy
function get_spreadsheet_json(url){
	$.getJSON(url, function(data){
		cleandata = {};
		var i = 1;
		
		$.each(data, function(key, value){
			var thisentry = {};
			thisentry.index = i;
		
			$.each(value, function(key, value){
				if(key.indexOf("gsx$") !== -1){
					thisentry[key.replace("gsx$", "")] = value.$t
				}
			});
		//	cleandata.push(thisentry);
			cleandata['row' + i] = thisentry;
			i++;
		});
		console.log(cleandata);
		
	});
}


//! Cleanse Spreadsheet Proxy data into a regular array (similar to above)
function clean_spreadsheet_proxy_json(data){
	cleandata = [];
	var i = 1;
	
	$.each(data, function(key, value){
		var thisentry = {};
		thisentry.index = i;
	
		$.each(value, function(key, value){
			if(key.indexOf("gsx$") !== -1){
				thisentry[key.replace("gsx$", "")] = $.trim(value.$t);
			}
		});
		cleandata.push(thisentry);
		i++;
	});
	return cleandata;
}



//! Map Code


var amw_gmap;
var infowindow;
var amw_gmap_styles = [];
var amw_gmap_options;

function amw_gmap_initialize($jqobj) {
	amw_gmap_options = {
		zoom: 12,
		lat: 38.9095555,
		lon: -77.0177302,
// 		icon: '/favicon.ico',
		types: 'restaurant,cafe',
		mastertype: 'single', // options: search, spreadsheet, geoJSON
	};
	amw_gmap_options = merge_options(amw_gmap_options, humps.camelizeKeys($jqobj.data()));
	var map_center = new google.maps.LatLng(amw_gmap_options.lat, amw_gmap_options.lon);
	var mapOptions = {
		center: map_center,
		zoom: amw_gmap_options.zoom,
		styles: amw_gmap_styles
	}		
	
	amw_gmap = new google.maps.Map($jqobj[0], mapOptions);
	
	
	var amw_gmap_request = {
		location: map_center,
		radius: 10000,
		types: amw_gmap_options.types.split(","),
		//keyword: 'drycleaning'
	};
	infowindow = new google.maps.InfoWindow();
	if(amw_gmap_options.mastertype == 'search'){
		var amw_gmap_service = new google.maps.places.PlacesService(amw_gmap);
		amw_gmap_service.radarSearch(amw_gmap_request, amw_gmap_callback);		
	}
	else if(amw_gmap_options.mastertype == 'spreadsheet'){
		// get_spreadsheet_json(amw_gmap_options.sourceurl);
		$.getJSON(amw_gmap_options.sourceurl, function(data){
			console.log(data);
			$.each(data, function(key, value){
				var place = {};
				var lat = $.trim(value.gsx$lat.$t);
				
				var randlastdig = Math.floor((Math.random()*100)+1);
				lat = parseFloat(lat + randlastdig); 
				var lon = $.trim(value.gsx$lon.$t);
				lon = parseFloat(lon + randlastdig);
				place.geometry = {location: new google.maps.LatLng(lat,lon)};
				if(typeof value.gsx$popupcontent !== "undefined") place.popupcontent = value.gsx$popupcontent.$t;
				// if(typeof value.gsx$type !== "undefined") place.type = value.gsx$type.$t;
				if(typeof value.gsx$icon !== "undefined") place.icon = value.gsx$icon.$t;
				createMarker(place);
			});
		});
	}
	else{
		console.log(amw_gmap_options);
		createMarker({
			geometry: {
				location: map_center,
			},
			popupcontent: amw_gmap_options.popupcontent || null,
		});
	}
	$jqobj.addClass('has_been_loaded');
	if($jqobj.css('position') == 'static') $jqobj.css('position', 'relative');
	$jqobj.append('<div class="amw_gmap_clickcover" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: gray; z-index: 9999; opacity: .1; cursor: pointer; " onclick="$(this).remove()" />');
}

function amw_gmap_callback(results, status, pagination) {
	if (status == google.maps.places.PlacesServiceStatus.OK) {
		for (var i = 0; i < results.length; i++) {
			createMarker(results[i]);
		}
	}
}

function createMarker(place) {
	var placeLoc = place.geometry.location;
	if(typeof place.icon !== "undefined" && place.icon !== "") var icon = place.icon;
	else var icon = amw_gmap_options.icon;
	var marker = new google.maps.Marker({
		map: amw_gmap,
		position: place.geometry.location,
		icon: icon
	});

	if(place.id || place.popupcontent){
		google.maps.event.addListener(marker, 'click', function() {
			if(typeof place.popupcontent !== "undefined"){
				infowindow.setContent(place.popupcontent);
			}
			else{
				infowindow.setContent(place.id);	
			}
			
			infowindow.open(amw_gmap, this);
		});		
	}
}






//! Script/Object Embedded - You want to embed something from a site. Use this method.


function lep_script_embedder(){
	
	var holders = $('.lep_script_embedder').not('.has_been_loaded');
	var increment = 0;
// 	.lep_script_embedder should be a textarea.
	holders.each(function(){
		var embedscript = $(this).val();
		// remove first in the event that the embedded script throws errors
		$(this).after('<div id="lep_script_embedder_container_' + increment + '" />');
		$(this).remove();		
		$('#lep_script_embedder_container_' + increment).html(embedscript);
		increment++;
	});
	holders.addClass('has_been_loaded');
}


/*

<script type="text/javascript" src="http://admin.brightcove.com/js/BrightcoveExperiences.js"></script>
<object id="myExperience4262979134001" class="BrightcoveExperience">
<param name="bgcolor" value="#FFFFFF" />
<param name="width" value="620" />
<param name="height" value="350" />
<param name="playerID" value="1775581290001" />
<param name="playerKey" value="AQ~~,AAABg16tKLE~,EXj2L-M6q85sWlCpZyPTweuygITf9KFf" />
<param name="isVid" value="true" />
<param name="isUI" value="true" />
<param name="dynamicStreaming" value="true" />
<param name="@videoPlayer" value="4262979134001" />
</object>
<script type="text/javascript">brightcove.createExperiences();</script>
<p><a href="http://washingtonexaminer.com/video/bc-4262979134001" target="_blank"><strong>Byron &amp; Barone: GOP field grows as Hillary hones her coalition</strong></a></p>
*/




//! Twitter Fetcher

/*********************************************************************
*  #### Twitter Post Fetcher v13.1 ####
*  Coded by Jason Mayes 2015. A present to all the developers out there.
*  www.jasonmayes.com
*  Please keep this disclaimer with my code if you use it. Thanks. :-)
*  Got feedback or questions, ask here:
*  http://www.jasonmayes.com/projects/twitterApi/
*  Github: https://github.com/jasonmayes/Twitter-Post-Fetcher
*  Updates will be posted to this site.
*********************************************************************/
(function(w,p){"function"===typeof define&&define.amd?define([],p):"object"===typeof exports?module.exports=p():p()})(this,function(){function w(a){return a.replace(/<b[^>]*>(.*?)<\/b>/gi,function(a,g){return g}).replace(/class=".*?"|data-query-source=".*?"|dir=".*?"|rel=".*?"/gi,"")}function p(a){a=a.getElementsByTagName("a");for(var c=a.length-1;0<=c;c--)a[c].setAttribute("target","_blank")}function n(a,c){for(var g=[],f=new RegExp("(^| )"+c+"( |$)"),h=a.getElementsByTagName("*"),b=0,k=h.length;b<
k;b++)f.test(h[b].className)&&g.push(h[b]);return g}var B="",k=20,C=!0,u=[],x=!1,v=!0,q=!0,y=null,z=!0,D=!0,A=null,E=!0,F=!1,r=!0,G=!0,m=null,H={fetch:function(a){void 0===a.maxTweets&&(a.maxTweets=20);void 0===a.enableLinks&&(a.enableLinks=!0);void 0===a.showUser&&(a.showUser=!0);void 0===a.showTime&&(a.showTime=!0);void 0===a.dateFunction&&(a.dateFunction="default");void 0===a.showRetweet&&(a.showRetweet=!0);void 0===a.customCallback&&(a.customCallback=null);void 0===a.showInteraction&&(a.showInteraction=
!0);void 0===a.showImages&&(a.showImages=!1);void 0===a.linksInNewWindow&&(a.linksInNewWindow=!0);void 0===a.showPermalinks&&(a.showPermalinks=!0);if(x)u.push(a);else{x=!0;B=a.domId;k=a.maxTweets;C=a.enableLinks;q=a.showUser;v=a.showTime;D=a.showRetweet;y=a.dateFunction;A=a.customCallback;E=a.showInteraction;F=a.showImages;r=a.linksInNewWindow;G=a.showPermalinks;var c=document.getElementsByTagName("head")[0];null!==m&&c.removeChild(m);m=document.createElement("script");m.type="text/javascript";m.src=
"https://cdn.syndication.twimg.com/widgets/timelines/"+a.id+"?&lang="+(a.lang||"en")+"&callback=twitterFetcher.callback&suppress_response_codes=true&rnd="+Math.random();c.appendChild(m)}},callback:function(a){var c=document.createElement("div");c.innerHTML=a.body;"undefined"===typeof c.getElementsByClassName&&(z=!1);a=[];var g=[],f=[],h=[],b=[],m=[],t=[],e=0;if(z)for(c=c.getElementsByClassName("tweet");e<c.length;){0<c[e].getElementsByClassName("retweet-credit").length?b.push(!0):b.push(!1);if(!b[e]||
b[e]&&D)a.push(c[e].getElementsByClassName("e-entry-title")[0]),m.push(c[e].getAttribute("data-tweet-id")),g.push(c[e].getElementsByClassName("p-author")[0]),f.push(c[e].getElementsByClassName("dt-updated")[0]),t.push(c[e].getElementsByClassName("permalink")[0]),void 0!==c[e].getElementsByClassName("inline-media")[0]?h.push(c[e].getElementsByClassName("inline-media")[0]):h.push(void 0);e++}else for(c=n(c,"tweet");e<c.length;)a.push(n(c[e],"e-entry-title")[0]),m.push(c[e].getAttribute("data-tweet-id")),
g.push(n(c[e],"p-author")[0]),f.push(n(c[e],"dt-updated")[0]),t.push(n(c[e],"permalink")[0]),void 0!==n(c[e],"inline-media")[0]?h.push(n(c[e],"inline-media")[0]):h.push(void 0),0<n(c[e],"retweet-credit").length?b.push(!0):b.push(!1),e++;a.length>k&&(a.splice(k,a.length-k),g.splice(k,g.length-k),f.splice(k,f.length-k),b.splice(k,b.length-k),h.splice(k,h.length-k),t.splice(k,t.length-k));c=[];e=a.length;for(b=0;b<e;){if("string"!==typeof y){var d=f[b].getAttribute("datetime"),l=new Date(f[b].getAttribute("datetime").replace(/-/g,
"/").replace("T"," ").split("+")[0]),d=y(l,d);f[b].setAttribute("aria-label",d);if(a[b].innerText)if(z)f[b].innerText=d;else{var l=document.createElement("p"),I=document.createTextNode(d);l.appendChild(I);l.setAttribute("aria-label",d);f[b]=l}else f[b].textContent=d}d="";C?(r&&(p(a[b]),q&&p(g[b])),q&&(d+='<div class="user">'+w(g[b].innerHTML)+"</div>"),d+='<p class="tweet">'+w(a[b].innerHTML)+"</p>",v&&(d=G?d+('<p class="timePosted"><a href="'+t[b]+'">'+f[b].getAttribute("aria-label")+"</a></p>"):
d+('<p class="timePosted">'+f[b].getAttribute("aria-label")+"</p>"))):a[b].innerText?(q&&(d+='<p class="user">'+g[b].innerText+"</p>"),d+='<p class="tweet">'+a[b].innerText+"</p>",v&&(d+='<p class="timePosted">'+f[b].innerText+"</p>")):(q&&(d+='<p class="user">'+g[b].textContent+"</p>"),d+='<p class="tweet">'+a[b].textContent+"</p>",v&&(d+='<p class="timePosted">'+f[b].textContent+"</p>"));E&&(d+='<p class="interact"><a href="https://twitter.com/intent/tweet?in_reply_to='+m[b]+'" class="twitter_reply_icon"'+
(r?' target="_blank">':">")+'Reply</a><a href="https://twitter.com/intent/retweet?tweet_id='+m[b]+'" class="twitter_retweet_icon"'+(r?' target="_blank">':">")+'Retweet</a><a href="https://twitter.com/intent/favorite?tweet_id='+m[b]+'" class="twitter_fav_icon"'+(r?' target="_blank">':">")+"Favorite</a></p>");F&&void 0!==h[b]&&(l=h[b],void 0!==l?(l=l.innerHTML.match(/data-srcset="([A-z0-9%_\.-]+)/i)[0],l=decodeURIComponent(l).split('"')[1]):l=void 0,d+='<div class="media"><img src="'+l+'" alt="Image from tweet" /></div>');
c.push(d);b++}if(null===A){a=c.length;g=0;f=document.getElementById(B);for(h="<ul>";g<a;)h+="<li>"+c[g]+"</li>",g++;f.innerHTML=h+"</ul>"}else A(c);x=!1;0<u.length&&(H.fetch(u[0]),u.splice(0,1))}};return window.twitterFetcher=H});

function dateFormatter(date) {
 // return date.toTimeString();
  return date.toLocaleString();
  
}

function load_twitter_feeds(){
	var feed_elements = $('.lep_twitter_feed').not('has_been_loaded');
	var default_config = {
		"maxTweets": 10,
		"enableLinks": true,
		"showUser": true,
		"showTime": true,
		"lang": 'en',
		"showImages": true,
		"dateFunction": dateFormatter,
		"showRetweet": true,
		"showInteraction": true,
	};
	var id_inc = 1;
	feed_elements.each(function(){
		if('widget_id' in $(this).data()){
			$(this).attr('id', 'twitter_feed_' + id_inc);
			var this_config = {
				"id": $(this).data('widget_id'),
				'domId': 'twitter_feed_' + id_inc,
			}
			var final_options = merge_options(default_config, this_config);
			// Data-Attributes are converted to lowercase by ckeditor. So write them as snake_case and convert to camelCase using Humps.js.
			var data_attributes = humps.camelizeKeys($(this).data());
			// console.log(data_attributes);
			final_options = merge_options(final_options, data_attributes);
			
			twitterFetcher.fetch(final_options);
		}
		else $(this).html('<div class="alert alert-danger">You are missing the data-attribute, <code>data-widget_id</code>. Create a Twitter Widget on the Twitter site (under Settings) and find the widget id that is included in the embed url you are provided. It is also in the current page url after saving. Add it to the element like: <code>&lt;div class=&quot;lep_twitter_feed&quot; data-widget_id=&quot;1234567891135&quot;&gt;&lt;/div&gt;</code>');
		id_inc++;		
	});
	feed_elements.addClass('has_been_loaded');
}

// svg is jQuery object
function loadSvg(svg, responsive){
	console.log(svg);
	// svg.tooltip('dispose');
	svg.wrap('<div class="tmpholder" style="height: ' + svg.height() + 'px" />');
	var originalsrc = svg.attr('src');
	console.log(originalsrc);
	$.get('/returnContents', {url: originalsrc}, function(data, textStatus, jqXHR){
		
		var newsvg = $(data);
		newsvg.attr('data-originalurl', originalsrc);
		newsvg.attr('class', svg.attr('class'));
		newsvg.insertBefore(svg);
		
		if(newsvg.attr('class') == 'svgTable' && typeof responsive !== "undefined"){
			newsvg.wrap('<div class="table-responsive" />');
		}
		svg.unwrap().remove();
	});
}		

// $('[src*=".svg"]').each(function(){
	$('.autoinlinesvg').each(function(){
// 	alert('s')
	loadSvg($(this));
});

$(document).ready(function(){
});

/*
var amw_stats = {
	get_relative_coords: function(click){
		// document.write(JSON.stringify(click.target));
		var elem = $(click.target);
		var parent = elem.parent();
		console.log(elem);
		console.log(parent);
	}
}

$(document).on('click', function(event){
	amw_stats.get_relative_coords(event)
});
*/
//document.addEventListener('click', amw_stats.get_relative_coords(event));