
$(function() {
    FastClick.attach(document.body);
});


function popupwindow(url, title, w, h) {
	var left = (screen.width/2)-(w/2);
	var top = (screen.height/2)-(h/2);
	return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
}	


var app = {
	'click' : 'click',
	
	'setfile' : true,
	
	'openerClasses' : '.filepicker, .filepickeropener',
	
	'modal' : null,
	
	'modalDom' : '<div class="modal fade" id="filepicker-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-close"></i></span></button><h4 class="modal-title" id="myModalLabel">Files</h4></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal" data-target="#myModal">Close</button></div></div></div></div>',
	
	'makeModal': function(){
		$(app.modalDom).appendTo('body');
		app.modal = $('#filepicker-modal');
		app.modal.find('.modal-body').html('<p class="text-center margin-top100"><i class="fa fa-cog fa-3x fa-spin fa-rotate"></i></p>');
	},
	
	
	'handleFocus' : function(){
		$(document).on('focus click', app.openerClasses, function(e){
			if($(this).is('.filepickeropener')) app.setfile = false;
			var spreadsheeturl;
			$.get('/ajax/files', function(data){
				app.modal.find('.modal-body').html('<div class="container-fluid"><div class="row"></div></div>');
				$.each(data, function(key, value){
					if(value.filetype == "divider"){
						app.modal.find('.modal-body .row').append('<div class="clearfix margin-top50 margin-bottom20"><hr><h4 class="text-center">Dynamically Generated Files</h4></div>');
						return;
					}
					var img = $('<div class="col-xs-6 col-md-3 filecardparent"><div class="card filepickeritem"><img class="card-img-top"><div class="card-block" style="overflow:hidden"><p class="card-text filename">Card title</p><p class="card-text text-right"><button type="button" class="btn btn-danger btn-xs deletelink"><i class="fa fa-trash"></i> Delete File</button></p></div></div></div>');
					img.find('img').attr('src', value.thumb);
					img.find('.card-text.filename').text(value.filename).css({'white-space': 'pre-wrap', 'line-height': 1});
					img.find('.deletelink').attr('data-filename', value.filename).attr('data-filetype', value.filetype);
					if(value.filetype == 'model') {
						spreadsheeturl = value.google_sheet_url;
						//	img.find('.deletelink').remove();
					}
					img.find('.filepickeritem').data('value', value.filename).data('preview', value.image).data('target', e.target).css('cursor', 'pointer');
					app.modal.find('.modal-body .row').append(img);
				});
				app.modal.find('.modal-body').prepend('<p class="text-center"><a href="' + spreadsheeturl + '" target="_blank">Edit in Google Sheets</a></p>');
			});
			app.modal.modal('show');
			console.log(e);
		});
		
		$(document).on('click', '.deletelink', function(e){
			e.stopPropagation();
			if(!confirm('Are you sure?')) return false;
			var filename = $(this).data('filename');
			var filecard = $(this).parents('.filecardparent');
			var filetype = $(this).data('filetype');
			$.ajax({
			  type: "DELETE",
			  url: '/ajax/files/',
			  data: {_token: _token, filename: filename, filetype: filetype},
			  success: function(data){
				  console.log(data);
				  if(data == 1)
					  filecard.fadeOut().remove();
				  else
					  alert('An error occurred and the file could not be deleted');
			  },
			});			
		});
	},
	
	'handleClick' : function(){
		
		$(document).on(app.click, '.filepickeritem', function(e){
			if(app.setfile){
				$($(this).data('target')).val($(this).data('value'));
				app.modal.modal('hide');
			}
			else{
				popupwindow($(this).data('preview'), $(this).data('value'), 1000, 700);
			}
		});			
	
	},
	
	'handleClose' : function(){
		$(document).on('hide.bs.modal', app.modal, function(e){
			app.modal.find('.modal-body').html('');
		});
	},
	
	'init' : function(){
		app.makeModal();
		console.log(app.modal);
		app.handleFocus();
		app.handleClick();
		app.handleClose();
	}
}

app.init();

var warnclose = false;

// $('body').append('<span id="cktest" />');

$(document).on('change', '.keyshortcut :input', function(){
	warnclose = true;
});

if( typeof CKEDITOR !== "undefined"){
	
	CKEDITOR.on('instanceCreated', function (e) {
		var initialval = e.editor.getData();
	    e.editor.on('change', function (ev) {
		    var newvalue = e.editor.getData();
		    if(initialval != newvalue)
		        warnclose = true;
	        $('#cktest').append(warnclose + ", ");
	    });
	});
	
}


window.addEventListener("beforeunload", function (e) {
    var confirmationMessage = 'It looks like you have been editing something. '
                            + 'If you leave before saving, your changes will be lost.';
	if(warnclose){
	    (e || window.event).returnValue = confirmationMessage; //Gecko + IE
	    return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.		
	}
});

Mousetrap.bind('mod+s', function(e){
	warnclose = false;
	e.preventDefault();
	$('form.keyshortcut').submit();
});

$(document).on('submit', 'form', function(){
	var allgood = true;
	$(':input').not(':hidden').not(':button').each(function(){
		var field = $(this)[0];
		
		if (typeof field.willValidate !== "undefined") {
	
			if(!field.checkValidity()) {
				allgood = false;
				$(this).parents('fieldset').addClass('has-danger');
				$(this).focus();
			}
	
		}
		else {
	
			// native validation not available
	
		}
		
	});
// 	return false;
if(allgood)	warnclose = false;
else return false;

});

function returnContents(url){
	alert(_token);
	$.post('/returnContents', {url: url, _token: _token}, function(data){
		console.log(data);
	});
}

var oktosort = true;

$(document).on('click', '.brand-sort-list .sorter', function(e){
	e.preventDefault();
	e.stopPropagation();
	if(!oktosort) {
		alert('Please wait until the previous sort is saved');
		return;
	}
	
	oktosort = false;
	var el = $(this).parents('a.dashboard-pagelink');
	var list = $(this).parents('.brand-sort-list');
	if($(this).data('sort') == 'up'){
		if(el.prev().length)
			el.insertBefore(el.prev());
		//else alert('**');
	}
	else{
		if(el.next().length)
			el.insertAfter(el.next());
	}
	
	var ord = 0;
	list.find('.dashboard-pagelink').each(function(){
		$(this).data('order', ord);
		$(this).attr('data-order', ord);
		ord = ord + 100;
	});
	var successes = 0;
	list.find('.dashboard-pagelink').each(function(){
		var order = $(this).data('order');
		var pageid = $(this).data('pageid');
		$.post('/setPageOrder', {_token: _token, order: order, pageid: pageid}, function(data){
			successes = successes + parseInt(data);
			if(successes == list.find('.dashboard-pagelink').length) oktosort = true;
		});
	});
	
});

var ch = [];
var egt;
var fulldocheight;

$(document).on(app.click, '.triggerPrint', function(e){
// 	e.preventDefault();
	
	if(experimental){
		var svg = makeSvg();
			
		$('head').append('<style>@media print{button, .hidden-print{ display:none;}}</style>');
		$('body').html(svg).css({backgroundColor: 'rgba(128, 128, 128, 0.49)', margin: 0});
		$('.printsvg').css({margin: 'auto', display: 'block'});
		$('[rel="stylesheet"]').remove();
		$('svg').fadeIn('slow');
		
		var downloadbutton = $('<button type="button" />');
		downloadbutton.addClass('btn btn-black hidden-print').css({position: "fixed", top: 10, right: 10}).text('Download').appendTo('body');
		downloadbutton.click(function(){
			var blob = new Blob([svg], {type: "image/svg+xml;charset=utf-8"});
			saveAs(blob, $('title').text() + ".svg");		
		});
		
		
		var startoverbutton = $('<button type="button" />');
		startoverbutton.addClass('btn btn-black hidden-print').css({position: "fixed", top: 50, right: 10}).text('Go Back').appendTo('body');
		startoverbutton.click(function(){
			window.location.reload();		
		});
		
	}
// 	else window.print();

});





function makeSvg(string){
	if(typeof string === "undefined") var string = false;
	var ch = getElements();
	var svg = $('<svg version="1.1" class="printsvg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" width="850" height="' + fulldocheight + '" viewBox="0, 0, 850, ' + fulldocheight + '"><g id="Background"><rect x="0" y="0" width="850" height="' + fulldocheight + '" fill="' + $('body').css('background-color') + '"/></g><g id="Layer_1"/></svg>');
// 	var background = $('<g><rect x="0" y="0" width="100" height="100" fill="red"></rect></g>');
// 	var background = $('<svg><g><image xlink:href="http://mediadc.development.jmbdc.com/files/medium/politically-active-index.svg" x="0" y="0" height="275px" width="521px"></image></g></svg>');
// 	background.appendTo(svg);
	
	$.each(ch, function(k, v){
	
		if(v.text.length){
			svg.append(addTextToSvg(v));
		}
		else if(v.elem.src){
			
			if(v.elem.src.indexOf('svg') !== -1){
// 				addSvgToSvg(v)
				svg.append(addSvgToSvg(v));
			}
			else{
				//svg.append(addSvgToSvg(v));
				svg.append(addRasterToSvg(v));
			}
				
		}
	});
	
	
	
	// resolve the svg and output
	var cont = $('<div id="svg" />');
	cont.html(svg);
	svg = cont.html();
	cont.remove();
	
	return svg;
}


function getElements(findwithin_selector){
	if(typeof findwithin_selector === "undefined") findwithin_selector = $('.printcontainer');
	else findwithin_selector = $(findwithin_selector);
	if(typeof ch === "undefined") var ch = [];
	

	var print_outer = $('.print_outer');

	print_outer.removeAttr('id');
	print_outer.attr('style', '');
// 	print_outer.css({'height':1100, 'width': 850});
	print_outer.css({'width': 810, 'padding': 20}).attr('class', 'print_outer');
	print_outer.insertBefore($('#wrap'));
	$('body > *').not(print_outer).remove();
	fulldocheight = print_outer.height() > 1100 ? print_outer.height() : 1100;
	var printcontainers = $('.printcontainer');
	var boundingbox = print_outer[0];
	
	findwithin_selector.find('*').each(function(){
		var t = $(this)[0];
		if($(this).first().parents('svg').length) return;
// 		console.log(test.length);
// 		console.log($(this).first().parents());
		var me = {};
		if(!t.children.length || t.childNodes[0].nodeName == '#text' || 1==1){
		egt = t;
			me.offset = boundingbox.getBoundingClientRect();
			me.position = t.getBoundingClientRect();
			me.elem = t;
			me.styles = dumpComputedStyles(t);
			me.text = $(this).text();
			
			ch.push(me);
		}
	});
	$('[name="viewport"]').removeAttr('content');	
	return ch;
}


var svgPrint = {
	
	print_outer_selector: '.print_outer',
	
	print_outer: $('.print_outer'),
	
	print_outer_offset: null,
	
	fulldocheight: null,
	
	getElements: function(findwithin_selector){
		if(typeof findwithin_selector === "undefined") findwithin_selector = $('.printcontainer');
		
		else findwithin_selector = $(findwithin_selector);
		if(typeof ch === "undefined") var ch = [];
			
		svgPrint.print_outer.removeAttr('id');
		svgPrint.print_outer.attr('style', '');
	// 	print_outer.css({'height':1100, 'width': 850});
		svgPrint.print_outer.css({'width': 850}).attr('class', 'print_outer');
// 		svgPrint.print_outer.css({'width': 850});
// 		svgPrint.print_outer.css({'width': 850}).attr('class', 'print_outer');
		svgPrint.print_outer.insertBefore($('#wrap'));
		$('[name="viewport"]').removeAttr('content');
		svgPrint.fulldocheight = svgPrint.print_outer.height() > 1100 ? svgPrint.print_outer.height() : 1100;
		
		var printcontainers = $('.printcontainer');
		
		var boundingbox = svgPrint.print_outer[0];
		svgPrint.print_outer_offset = boundingbox.getBoundingClientRect();
		
		findwithin_selector.find('*').each(function(){
			var t = $(this)[0];
			if($(this).first().parents('svg').length) return;
			var me = {};
			if(!t.children.length || t.childNodes[0].nodeName == '#text' || 1==1){
			egt = t;
				me.offset = boundingbox.getBoundingClientRect();
				me.position = t.getBoundingClientRect();
				me.elem = t;
				me.styles = dumpComputedStyles(t);
				me.text = $(this).text();
				
				ch.push(me);
			}
		});
// 		svgPrint.print_outer.insertBefore($('#wrap'));
// 		$('[name="viewport"]').removeAttr('content');	
		return ch;
	},
	
	print: function(){
		$('[data-originalurl]').each(function(){
			$(this).before('<img style="visibility:visible" src="' + $(this).data('originalurl') + '">').remove();
		});
		$('[name="viewport"]').removeAttr('content');
		$('.hidden-print').remove();
		
		svgPrint.print_outer.css({'width': 850})
			.attr('class', 'print_outer');
// 			.insertBefore($('#wrap'));
		
		svgPrint.fulldocheight = svgPrint.print_outer.height() > 1100 ? svgPrint.print_outer.height() : 1100;
		
		var numpages = Math.ceil( svgPrint.fulldocheight / 1100 );
		
			var svg = $('<svg version="1.1" class="Xprintsvg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" width="850" height="' + svgPrint.fulldocheight + '" viewBox="0, 0, 850, ' + svgPrint.fulldocheight + '"><g id="Background"><rect x="0" y="0" width="850" height="' + svgPrint.fulldocheight + '" fill="' + $('body').css('background-color') + '"/></g></svg>');
			var i = 0;
			while(i < numpages + 1){
				i++;
				svg.append('<g id="Layer_' + i + '" transform="translate(15,' + ((i - 1) * 1100 + 15) + ')" height="1070" width="820"><rect x="0" y="0" width="820" height="1070" fill="white" /> />');
			}
			
			
			
			var layer1effectiveHeight = 0, layer2effectiveHeight = 0;
			
			$('[class*="col-"]').each(function(){
				if($(this).find('[class*="col-"]').length) return;
				
				var offsetdifference = 0;
				var position = $(this)[0].getBoundingClientRect();
				var offset = svgPrint.print_outer[0].getBoundingClientRect();
				$(this).find('*').each(function(){
					$(this).css(dumpComputedStyles($(this)[0]));
				});
// 				$(this).css(dumpComputedStyles($(this)[0])).removeAttr('class');
								
				var foreignObj = $('<foreignObject />');

				
				foreignObj.attr('width', (position.width + 30)).attr('height', position.height).attr('x', 0).attr('y', 0);
		// 		foreignObj.attr('style', $(this).attr('style'));
				
		//		foreignObj.html($(this).wrap('<div />').parent().html());
				foreignObj.html($(this).html());
				var appendtostring = '#Layer_1';
				if(position.top + position.height > 1100) {
					appendtostring = '#Layer_3';
					offsetdifference = 1100 - (1100 - position.top);
					layer1effectiveHeight = offsetdifference > layer1effectiveHeight ? offsetdifference : layer1effectiveHeight;
				}
				else if(position.top + position.height > (2200 - layer1effectiveHeight)) {
					appendtostring = '#Layer_2';
					offsetdifference = 2200 - (2200 - position.top); 
					layer2effectiveHeight = offsetdifference > layer2effectiveHeight ? offsetdifference : layer2effectiveHeight;
				}
				else if(position.top + position.height > (3300 - layer2effectiveHeight)) {
					appendtostring = '#Layer_2';
				}
				
				var topposition = position.top - offsetdifference + 15;
				foreignObj.appendTo(svg.find(appendtostring))
				.wrap('<body xmlns="http://www.w3.org/1999/xhtml" />')
				.wrap('<g transform="translate(' + (position.left + 15) + ',' + topposition + ')" />');
			});
// 			alert(svg.find('foreignObject').first().css('top'));
			numpages++;
			svg.attr('height', numpages * 1100).attr('viewbox', '0, 0, 850, ' + numpages * 1100);
			
// 			svg.find('#Layer_1').attr('transform', 'translate(30,30)');
			// resolve the svg and output
			var cont = $('<div id="svg" />');
			console.log($(svg));
			cont.html(svg);
			svg = cont.html();
			cont.remove();
/*
			document.write(svg);
			var b = document.getElementsByTagName('body')[0];
			b.setAttribute('style', 'padding: 0; margin: 0');
		console.log(document.Body);
*/
			$('#body').html(svg);
	}
}




function testAddStyles(){
	var elems = getElements('.print_outer');
	console.log(elems);
/*
	$('body').append('<div class="print_outer" id="print_outer_2" />');
	$('body').append('<div class="print_outer" id="print_outer_3" />');
*/
	$('.print_outer').css({
		width: 850,
		height: 1100,
// 		overflow: 'hidden',
		'page-break-after': 'always',
		'border': 'solid 1px black',
	});
	$.each(elems, function(k,v){
		$.each(v.styles, function(kk,vv){
			v.styles[kk.replace('_', '-')] = vv;
// 			delete v.styles[kk];
		});
		var thisv = $(v.elem);
		
		if(!thisv.find('[class*="col-"]').length && thisv.parent().is('[class*="col-"]')){
			// means this is the direct descendent of a col-* item and has no other nested grid items.
			//console.log(v.elem);
			thisv.parent().css('outline', 'dotted 1px red');
			if(v.position.top + v.position.height > 1100) {
				thisv.parent().css('page-break-after', 'always');
				// thisv.parent('[class*="col-"]').prependTo('#print_outer_2');
			}
		}
		
		thisv.css(v.styles);
		
		
		
		
/*
		$(v.elem).css({
			top: v.position.top,
			left: v.position.left,
			position: 'fixed',
		});
*/
	});
	var pagebreakelements = $('img, .card, p, .card-icon-top, svg');
	pagebreakelements.each(function(){
		if(!$(this).find('[class*="col-"]').length && $(this).parent().is('[class*="col-"]')){
			
		}
	});
	$('[class*="col-"]').each(function(){
		var position = $(this)[0].getBoundingClientRect();
		$(this).css({
			top: position.top,
			left: position.left,
// 			position: 'absolute',
		});
	});
// 	$('[class*="col-"]').unwrap();
	$('.hidden-print').remove();
// 	$('[rel="stylesheet"]').remove();
	$('#wrap').remove();
}


function addSvgToSvg(v){
	console.log(v);
// 	var imgholder = $('<g />');
	var left = v.position.left - v.offset.left;
	var top = v.position.top - v.offset.top;
	var img = '<svg><g transform="translate(' + left + ',' + top + ')"><image xlink:href="' + v.elem.src + '" x="0" y="0" height="' + v.styles.height + '" width="' + v.styles.width + '"></image></g></svg>';	

	return img;
	
}


function addRasterToSvg(v){
// make a holder for it
/*
	var imgholder = new Path.Rectangle({
	    point: [v.position.left - v.offset.left, v.position.top - v.offset.top],
	    size: [v.position.width, v.position.height],
	});
	var raster = new Raster(v.elem);
	raster.width = v.position.width * 5;
	raster.height = v.position.height * 5;
	raster.fitBounds(imgholder.bounds);
*/
}


function addTextToSvg(v){
	var left = v.position.left - v.offset.left;
	var top = v.position.top - v.offset.top + parseInt(v.styles.lineheight.replace('px', ''));
	var cleantext = v.text.replace(/\&nbsp\;/g, '');
	var segments = cleantext.split("\n");
	
	var outer = $('<svg><g transform="translate(' + left + ',' + top + ')"><text x="0" y="0" alignment-baseline="hanging" text-anchor="start" font-size="' + v.styles.fontsize + '" height="' + v.styles.height + '" width="' + v.styles.width + '"></text></g></svg>');	
	outer.find('text')
		.attr('font-weight', v.styles.fontweight)
		.attr('font-size', v.styles.fontsize)
		.attr('fill', v.styles.color)
		.attr('font-family', v.styles.fontfamily);
	$.each(segments, function(segInd, segment){
		segment = $.trim(segment);
		var lines = getInt(v.styles.height)/getInt(v.styles.lineheight);
		var i = 0;
		while(i < lines){
			var segtspan = $('<tspan x="0">' + segment + '</tspan>');
			if(i > 0) segtspan.attr('dy', v.styles.lineheight);
			segtspan.appendTo(outer.find('text'));	
			i++;
		}
		
	});
	return outer;
	
	

}

function getInt(int){
	return parseInt($.trim(int.replace(/\D/g,'')));
}


// $('body').append(makeSvg());
// $('body').html($('body').html());

function dumpComputedStyles(elem) {
  var allstyles = {};
  var cs = window.getComputedStyle(elem,null);
  var len = cs.length;

   for (var i=0;i<len;i++) {

    var style = cs[i];
    allstyles[style.replace(/\-/g, '_')] = cs.getPropertyValue(style);

	}
	$.each(allstyles, function(kk,vv){
		allstyles[kk.replace('_', '-')] = vv;
	});

	return allstyles;
}

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

$('.nullify').attr('title', 'Clear Input').addClass('text-danger').tooltip();

$(document).on('click', '.nullify', function(e){
	e.preventDefault();
	e.stopPropagation();
	var input = $(this).parents('.form-group').find(':input').not(':button');
	input.val('');
});

$(document).on('change', '.dynmaker :input', function(){
	var one = $('.dynmaker1').val();
	var two = $('.dynmaker2').val();
	var three = $('.dynmaker3').val();
	var four = $('.dynmaker4').val();
	four = four.length ? "&title=" + four : "";
	var cachebust = one + two + three + four;
	var url = "/svg/" + one + "/gtable-" + two + "/" + three + "?v=1" + four;
	$('.dynmakerlink').remove();
	var link = $("<a target='_blank' class='dynmakerlink' />");
	link.attr('href', url).text(url);
	link.insertAfter('.dynmaker');
});

var maxHeight = 25;
$(window).load(function(){
	$('.height_calc').each(function(){
		maxHeight += $(this).height();
	});
	$('.height_calc_target').css('max-height',  $(window).height() - maxHeight);
	console.log('maxheight = ' + ($(window).height() - maxHeight));
});

	
