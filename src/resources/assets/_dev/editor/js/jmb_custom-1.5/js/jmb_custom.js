


/*================================

	This should be included in the default site setup. Uncomment any functions you would like to use. Delete when going to production to save file size.

================================*/

	
	
	
	
	/*================================
	
	This function provides vertical responsiveness to any element with the class "vertically_responsive"
		Specify the height that this should happen and configure the paramenters
	
	================================*/
	
	
	
	vertically_responsive ();
	
	function vertically_responsive () {
		var winheight = $(window).height();
		if (winheight<730){
			$("#main_content_box").css("background-image", "url('/images/home_public_bg_short.png')");
			$(".vertically_responsive").css('margin-top', '-100px');
			}
		else 
		{$("#main_content_box").css("background-image", "url('/images/home_public_bg4.png')");
		$(".vertically_responsive").css('margin-top', 'auto');
		}
	}
	
		$(document).ready(vertically_responsive);
	    $(window).resize(vertically_responsive);

//////////////============/////////////////
	    
/*  Add .btn class to buttons and submits 	     */

//$('button, input[type="submit"]').not('.nobtn').addClass('btn');



//////////////============/////////////////			 
	/*================================
	
	This function provides manually sets the proper margin and padding for the sticky footer as set in jmb_custom.css; overrides the set height of 50px via css
	Runs Automatically!!
	
	================================*/



function footerOffset(){
	$('#footer').css('margin-top', "");
	$('#main').css('padding-bottom', "");

	var h = $('#footer').outerHeight();
	$('#footer').css('margin-top', -1 * h);
	$('#main').css('padding-bottom', h + 40);
}

$(window).load(function(){
	footerOffset();
});

$(window).resize(function(){
	footerOffset();
});

$(document).on('shown.bs.tab refire', function () {
	footerOffset();
});


//////////////============/////////////////			 
			 
 function autosize(){
 	$('[data-autosize="true"]').each(function(){
	 	$(this).css({
			'height': 300,
			'width': 300,	
	 	});
	 	var divisor = $(this).attr('data-divisor');
	 	if (typeof divisor == 'undefined'){ var divisor = 1; }
		 //var h = $(element).parent('[class*="span"]').height();
		 var w = $(this).parents('[class*="span"]').width();
		 
		 //Check to see if Bootstrap is displaying set widths for .span elements, under 767px it becomes 100%
		 if($(window).width() > 769){
		 $(this).css({
			 'height': w * divisor,
			 'width': w * divisor,
		 });
		 }
		 else {
			 $(this).css({
				 'height': "auto",
				 'width': "auto",
			 });
		 }
	 });
 }
 
 //$(document).ready(autosize);
 $(window).load(autosize);
 $(window).resize(autosize);
			 
			 
//////////////============/////////////////			 
			 
 function autoheight(){
 	$('[data-autoheight="true"]').each(function(){
		 var h = $(this).next('[data-autoheighttarget="true"]').outerHeight(true);
		 //$('.absolute-center').css('height', $(this).outerHeight(true));
		 //Check to see if Bootstrap is displaying set widths for .span elements, under 767px it becomes 100%
		 $(this).css({
			 'height': h,
		 });
		 
	 });
 }
// $(window).load(autoheight);
 autoheight();
 // Use like: $(window).load(autoheight2('.items')).resize(autoheight2('.items'));
 // Will make all .items the same height as .items[data-autoheighttarget]
 function autoheight2(selector){
	 	if($(selector + '[data-autoheighttarget]').length > 0 && $(selector + '[data-autoheight]').length > 0){
	 	$(selector + '[data-autoheight]').each(function(){
			 var h = $(selector + '[data-autoheighttarget]').outerHeight(true);
			 $(this).css({
				 'height': h,
			 });
		 });
	 }
 }
 
			 
//////////////============/////////////////			 

function c5LayoutOverride() {
 
 if ($(".ccm-layout-wrapper")[0]){
 	   $(".ccm-layout-wrapper").each(function(){
	 	   $(this).parent('.padded0_30').removeClass('padded0_30');
 	   });
 	   
	   $('.ccm-layout-wrapper .ccm-layout-row').not(".ccm-layout-wrapper .ccm-layout-wrapper .ccm-layout-row").addClass('row layout_row');
	   $(".ccm-layout-wrapper .ccm-layout-wrapper .ccm-layout-row").addClass('row-fluid layout_row');
	   $('.ccm-layout-wrapper .ccm-layout-cell').not(".ccm-layout-wrapper .ccm-layout-wrapper .ccm-layout-cell").each(function(){
		   var parent = $(this).parents('.row').width();
		   var width = $(this).width();
		   var newClass = "span" + Math.round(width/parent*12);
		   $(this).addClass(newClass).removeAttr('style');
	   });
	   
	   $('.ccm-layout-wrapper .ccm-layout-cell').not(".ccm-layout-wrapper .ccm-layout-wrapper .ccm-layout-cell").each(function(){
		   var parent = $(this).parents('.row').width();
		   var width = $(this).width();
		   var newClass = "span" + Math.round(width/parent*12);
		   $(this).addClass(newClass).removeAttr('style');
	   });
	   
		$('.ccm-layout-wrapper').parent('[class*="span"]').each(function(){
			$(this).css({
				'padding-left': 0,
				'padding-right': 0,
			}).addClass('zeropadded');
		});
		
	}
 
}


function c5Bootstrap3LayoutOverride() {
 
 if ($(".ccm-layout-wrapper")[0]){
 	   $(".ccm-layout-wrapper").each(function(){
	 	   $(this).parent('.padded0_30').removeClass('padded0_30');
 	   });
 	   
	   $('.ccm-layout-wrapper .ccm-layout-row').not(".ccm-layout-wrapper .ccm-layout-wrapper .ccm-layout-row").addClass('row layout_row');
	   $(".ccm-layout-wrapper .ccm-layout-wrapper .ccm-layout-row").addClass('row-fluid layout_row');
	   $('.ccm-layout-wrapper .ccm-layout-cell').not(".ccm-layout-wrapper .ccm-layout-wrapper .ccm-layout-cell").each(function(){
		   var parent = $(this).parents('.row').width();
		   var width = $(this).width();
		   var newClass = "col-md-" + Math.round(width/parent*12);
		   $(this).addClass(newClass).removeAttr('style');
	   });
	   
	}
 
}

// Call this from footer.php because it should not be executed if in editing mode.
//$(document).ready(c5Bootstrap3LayoutOverride);

//////////////============/////////////////			 

			
function drawQuadraticSVG(color, width, height, thick, element){
	var midpoint = width/2;
	var adjusted = height - thick;
	var qTwo = height - thick * 2;
	var svg = '<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="' + width + '" height="' + height + '" viewBox="0, 0, ' + width + ',' + height + '"><g id="Layer 1"><path d="M 0,' + adjusted + ' Q ' + midpoint + ', -' + qTwo + ' ' + width + ',' + adjusted + '" fill-opacity="0" stroke="' + color + '" stroke-width="' + thick + '"/></g><defs/></svg>';
	
	$(element).html('<div class="row"><div class="col-xs-12">' + svg + '</div></div>');
	
}

$(document).ready(function(){
	var w = $('.curve').parent().width(); 
	drawQuadraticSVG('#3070C6', w, 50, 5, '.curve');
});
$(window).resize(function(){
	var w = $('.curve').parent().width(); 
	drawQuadraticSVG('#3070C6', w, 50, 5, '.curve');
});

//////////////============/////////////////		

function evenHeight(myClass) {
	var array = new Array();
	$('.' + myClass).each(function(){
		array.push($(this).outerHeight());
	});
	var calcheight = Math.max.apply(null, array);
	if(calcheight > 20)
		$('.' + myClass).height(calcheight);
}
	
function sameHeight() {
	var elems = $('[data-sameheight]');
	var sims = new Array();
	elems.each(function(){
		sims.push($(this).attr('data-sameheight'));
	});
	sims = unique(sims);
	
	
	for (x in sims){
		var elems = $('[data-sameheight="' + sims[x] + '"]');
		var array = new Array();
		elems.each(function(){
			
			array.push($(this).outerHeight());
			
		});
		
		elems.height(Math.max.apply(null, array));
		
	}
	
}

$(document).ready(function(){
	//sameHeight();
});
function unique(list) {
  var result = [];
  $.each(list, function(i, e) {
    if ($.inArray(e, result) == -1) result.push(e);
  });
  return result;
}
	
//////////////============/////////////////		

//////////////==== Animate Poof Effect ======/////////////////		

//////////////============/////////////////		
	
function animatePoof() {
    var bgTop = 0,
        frame = 0,
        frames = 6,
        frameSize = 32,
        frameRate = 80,
        puff = $('#puff');
    var animate = function(){
        if(frame < frames){
            puff.css({
                backgroundPosition: "0 "+bgTop+"px"
            });
            bgTop = bgTop - frameSize;
            frame++;
            setTimeout(animate, frameRate);
        }
    };
    
    animate();
    setTimeout("$('#puff').hide()", frames * frameRate);
}
/*
$(function() {
    $('.obstacles span').click(function(e) {
        var xOffset = 24;
        var yOffset = 24;
        $(this).fadeOut('fast');
        $('#puff').css({
            left: e.pageX - xOffset + 'px',
            top: e.pageY - yOffset + 'px'
        }).show();
        animatePoof();
    });
});
*/

function poofout(e){
		var xOffset = 24;
        var yOffset = 24;
        $(this).fadeOut('fast');
        $('#puff').css({
            left: element.pageX - xOffset + 'px',
            top: element.pageY - yOffset + 'px'
        }).show();
        animatePoof();
}

//////////////============/////////////////		
/* ! Automatic Bootstrap handlers  */
//////////////============/////////////////		

$('[data-toggle="tooltip"], [rel="tooltip"]').tooltip();

/* $('[data-toggle="popover"], [rel="popover"]').popover(); */

//////////////============/////////////////		
/* ! Automatic Bootstrap Element producers  */
//////////////============/////////////////	

//Basic function to create a random id
function makeid()
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 5; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
} 

//Trigger these by adding the appropriate class to the containing div/block if C5

//Add class jmb_accordion
function makeAccordion(){
    if(!$('body').hasClass('in_edit_mode')){
	    var accordion = $('.jmb_accordion');
	    var pid = 'parent_' + makeid();
	    var panels = $('.jmb_accordion > h1, .jmb_accordion > h2, .jmb_accordion > h3, .jmb_accordion > h4, .jmb_accordion > h5, .jmb_accordion > h6');
	    accordion.attr('id',pid).addClass('panel-group');
	    panels.each(function(){
	    	var cid = 'child_' + makeid();
		    $(this).nextUntil('h1, h2, h3, h4, h5, h6').wrapAll('<div id="' + cid + '" class="panel-collapse collapse"><div class="panel-body"></div></div>');
		    $(this).addClass('panel-title').wrap('<div class="panel-heading" />')
		    .wrapInner('<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#' + pid +'" href="#' + cid + '" />');
		    
	    });
	    $('.jmb_accordion .panel-heading').each(function(){
		    $(this).nextUntil('.panel-heading').add(this).wrapAll('<div class="panel panel-default" />');
	    });
	    setTimeout(function(){$('#' + pid + ' .accordion-toggle:first').click()}, 1000);
    }
}

//Add class jmb_slider
function makeSlider(){
	var isok = true;
	if(typeof CCM_EDIT_MODE !== "undefined" && CCM_EDIT_MODE) isok = false;
	if(isok){
		var slider = $('.jmb_slider').not('.in_edit_mode .jmb_slider');
		if(!slider.length) return;
		
		var userOptions = slider.data('slideroptions');
		if(typeof userOptions != "object") userOptions = {};
		console.log(typeof userOptions);
		var defaultOptions = {'indicators': true, 'controls': true};
		var options = {};
		$.each(defaultOptions, function(key, value){
			if(typeof userOptions[key] !== "undefined") options[key] = userOptions[key];
			else options[key] = value;
		});
		console.log(options);
		slider.attr('data-ride', 'carousel');
		var pid = 'parent_' + makeid();
		var slides = $('.jmb_slider img');
		slider.attr('id',pid).addClass('carousel slide');
		slides.unwrap();
		slides.each(function(){
			var cid = 'child_' + makeid();
			$(this).nextUntil('img').add(this).wrapAll('<div id="' + cid + '" class="carousel-item carousel-item-' + pid + ' item"></div>');
			$(this).nextAll().wrapAll('<div class="carousel-caption" />');
			
		});
		$('.jmb_slider .carousel-item').wrapAll('<div class="carousel-inner" />');
		// controls
		if(options.controls){
			$('.jmb_slider .carousel-inner').after('<a class="left carousel-control" href="#' + pid + '" data-slide="prev"><span class="icon-prev"></span></a><a class="right carousel-control" href="#' + pid + '" data-slide="next"><span class="icon-next"></span></a>');
		}
		
		
		// indicators
		if(options.indicators){
			$('.jmb_slider .carousel-inner').before('<ol class="carousel-indicators" />');
			var count = $('.carousel-item').length;
			var i = 0;
			while (i<count){
				$('.jmb_slider .carousel-indicators').append('<li data-target="#' + pid + '" data-slide-to="' + i +'"></li>');
				i++;
			}
		}
				
		$('.jmb_slider .carousel-item:first, .jmb_slider .carousel-indicators li:first').addClass('active');
		
		slider.fadeIn();
		setTimeout(evenHeight('carousel-item-' + pid), 500);
	}

}

function unmakeSlider(jqobj){
	var output = "";
	jqobj.find('.item').each(function(){
		var img = $(this).find('img')[0];
		var cap = $(this).find('.carousel-caption')[0];
		output += img.outerHTML + cap.innerHTML;
	});

	return output;	
}

/*
makeAccordion();
makeSlider();
*/

$(document).ready(function(){
	if(!$('body').hasClass('edit_mode') && !$('body').hasClass('in_edit_mode')){
		makeAccordion();
		makeSlider();
		$.event.trigger({
			type: "refire",
			message: "",
			time: new Date()
		});				    				
	}
});	

$(window).load(function(){
	$('.jmb_slider, .jmb_accordion').fadeIn().css('visibility', 'visible');
});

//////////////============/////////////////		
/* ! jQuery .visible plugin by http://www.teamdf.com/  */
//////////////============/////////////////	


(function($){

    /**
     * Copyright 2012, Digital Fusion
     * Licensed under the MIT license.
     * http://teamdf.com/jquery-plugins/license/
     *
     * @author Sam Sehnert
     * @desc A small plugin that checks whether elements are within
     *       the user visible viewport of a web browser.
     *       only accounts for vertical position, not horizontal.
     */
    var $w = $(window);
    $.fn.visible = function(partial,hidden,direction){

        if (this.length < 1)
            return;

        var $t        = this.length > 1 ? this.eq(0) : this,
            t         = $t.get(0),
            vpWidth   = $w.width(),
            vpHeight  = $w.height(),
            direction = (direction) ? direction : 'both',
            clientSize = hidden === true ? t.offsetWidth * t.offsetHeight : true;

        if (typeof t.getBoundingClientRect === 'function'){

            // Use this native browser method, if available.
            var rec = t.getBoundingClientRect(),
                tViz = rec.top    >= 0 && rec.top    <  vpHeight,
                bViz = rec.bottom >  0 && rec.bottom <= vpHeight,
                lViz = rec.left   >= 0 && rec.left   <  vpWidth,
                rViz = rec.right  >  0 && rec.right  <= vpWidth,
                vVisible   = partial ? tViz || bViz : tViz && bViz,
                hVisible   = partial ? lViz || lViz : lViz && rViz;

            if(direction === 'both')
                return clientSize && vVisible && hVisible;
            else if(direction === 'vertical')
                return clientSize && vVisible;
            else if(direction === 'horizontal')
                return clientSize && hVisible;
        } else {

            var viewTop         = $w.scrollTop(),
                viewBottom      = viewTop + vpHeight,
                viewLeft        = $w.scrollLeft(),
                viewRight       = viewLeft + vpWidth,
                offset          = $t.offset(),
                _top            = offset.top,
                _bottom         = _top + $t.height(),
                _left           = offset.left,
                _right          = _left + $t.width(),
                compareTop      = partial === true ? _bottom : _top,
                compareBottom   = partial === true ? _top : _bottom,
                compareLeft     = partial === true ? _right : _left,
                compareRight    = partial === true ? _left : _right;

            if(direction === 'both')
                return !!clientSize && ((compareBottom <= viewBottom) && (compareTop >= viewTop)) && ((compareRight <= viewRight) && (compareLeft >= viewLeft));
            else if(direction === 'vertical')
                return !!clientSize && ((compareBottom <= viewBottom) && (compareTop >= viewTop));
            else if(direction === 'horizontal')
                return !!clientSize && ((compareRight <= viewRight) && (compareLeft >= viewLeft));
        }
    };

})(jQuery);

//////////////============/////////////////		
/* ! Back to Top Button JS  */
//////////////============/////////////////	


if(typeof $.easing['easeInOutExpo'] === "undefined"){


	/*
	 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
	 *
	*/
	jQuery.easing['jswing']=jQuery.easing['swing'];jQuery.extend(jQuery.easing,{def:'easeOutQuad',swing:function(x,t,b,c,d){return jQuery.easing[jQuery.easing.def](x,t,b,c,d);},easeInQuad:function(x,t,b,c,d){return c*(t/=d)*t+b;},easeOutQuad:function(x,t,b,c,d){return-c*(t/=d)*(t-2)+b;},easeInOutQuad:function(x,t,b,c,d){if((t/=d/2)<1)return c/2*t*t+b;return-c/2*((--t)*(t-2)-1)+b;},easeInCubic:function(x,t,b,c,d){return c*(t/=d)*t*t+b;},easeOutCubic:function(x,t,b,c,d){return c*((t=t/d-1)*t*t+1)+b;},easeInOutCubic:function(x,t,b,c,d){if((t/=d/2)<1)return c/2*t*t*t+b;return c/2*((t-=2)*t*t+2)+b;},easeInQuart:function(x,t,b,c,d){return c*(t/=d)*t*t*t+b;},easeOutQuart:function(x,t,b,c,d){return-c*((t=t/d-1)*t*t*t-1)+b;},easeInOutQuart:function(x,t,b,c,d){if((t/=d/2)<1)return c/2*t*t*t*t+b;return-c/2*((t-=2)*t*t*t-2)+b;},easeInQuint:function(x,t,b,c,d){return c*(t/=d)*t*t*t*t+b;},easeOutQuint:function(x,t,b,c,d){return c*((t=t/d-1)*t*t*t*t+1)+b;},easeInOutQuint:function(x,t,b,c,d){if((t/=d/2)<1)return c/2*t*t*t*t*t+b;return c/2*((t-=2)*t*t*t*t+2)+b;},easeInSine:function(x,t,b,c,d){return-c*Math.cos(t/d*(Math.PI/2))+c+b;},easeOutSine:function(x,t,b,c,d){return c*Math.sin(t/d*(Math.PI/2))+b;},easeInOutSine:function(x,t,b,c,d){return-c/2*(Math.cos(Math.PI*t/d)-1)+b;},easeInExpo:function(x,t,b,c,d){return(t==0)?b:c*Math.pow(2,10*(t/d-1))+b;},easeOutExpo:function(x,t,b,c,d){return(t==d)?b+c:c*(-Math.pow(2,-10*t/d)+1)+b;},easeInOutExpo:function(x,t,b,c,d){if(t==0)return b;if(t==d)return b+c;if((t/=d/2)<1)return c/2*Math.pow(2,10*(t-1))+b;return c/2*(-Math.pow(2,-10*--t)+2)+b;},easeInCirc:function(x,t,b,c,d){return-c*(Math.sqrt(1-(t/=d)*t)-1)+b;},easeOutCirc:function(x,t,b,c,d){return c*Math.sqrt(1-(t=t/d-1)*t)+b;},easeInOutCirc:function(x,t,b,c,d){if((t/=d/2)<1)return-c/2*(Math.sqrt(1-t*t)-1)+b;return c/2*(Math.sqrt(1-(t-=2)*t)+1)+b;},easeInElastic:function(x,t,b,c,d){var s=1.70158;var p=0;var a=c;if(t==0)return b;if((t/=d)==1)return b+c;if(!p)p=d*.3;if(a<Math.abs(c)){a=c;var s=p/4;}
	else var s=p/(2*Math.PI)*Math.asin(c/a);return-(a*Math.pow(2,10*(t-=1))*Math.sin((t*d-s)*(2*Math.PI)/p))+b;},easeOutElastic:function(x,t,b,c,d){var s=1.70158;var p=0;var a=c;if(t==0)return b;if((t/=d)==1)return b+c;if(!p)p=d*.3;if(a<Math.abs(c)){a=c;var s=p/4;}
	else var s=p/(2*Math.PI)*Math.asin(c/a);return a*Math.pow(2,-10*t)*Math.sin((t*d-s)*(2*Math.PI)/p)+c+b;},easeInOutElastic:function(x,t,b,c,d){var s=1.70158;var p=0;var a=c;if(t==0)return b;if((t/=d/2)==2)return b+c;if(!p)p=d*(.3*1.5);if(a<Math.abs(c)){a=c;var s=p/4;}
	else var s=p/(2*Math.PI)*Math.asin(c/a);if(t<1)return-.5*(a*Math.pow(2,10*(t-=1))*Math.sin((t*d-s)*(2*Math.PI)/p))+b;return a*Math.pow(2,-10*(t-=1))*Math.sin((t*d-s)*(2*Math.PI)/p)*.5+c+b;},easeInBack:function(x,t,b,c,d,s){if(s==undefined)s=1.70158;return c*(t/=d)*t*((s+1)*t-s)+b;},easeOutBack:function(x,t,b,c,d,s){if(s==undefined)s=1.70158;return c*((t=t/d-1)*t*((s+1)*t+s)+1)+b;},easeInOutBack:function(x,t,b,c,d,s){if(s==undefined)s=1.70158;if((t/=d/2)<1)return c/2*(t*t*(((s*=(1.525))+1)*t-s))+b;return c/2*((t-=2)*t*(((s*=(1.525))+1)*t+s)+2)+b;},easeInBounce:function(x,t,b,c,d){return c-jQuery.easing.easeOutBounce(x,d-t,0,c,d)+b;},easeOutBounce:function(x,t,b,c,d){if((t/=d)<(1/2.75)){return c*(7.5625*t*t)+b;}else if(t<(2/2.75)){return c*(7.5625*(t-=(1.5/2.75))*t+.75)+b;}else if(t<(2.5/2.75)){return c*(7.5625*(t-=(2.25/2.75))*t+.9375)+b;}else{return c*(7.5625*(t-=(2.625/2.75))*t+.984375)+b;}},easeInOutBounce:function(x,t,b,c,d){if(t<d/2)return jQuery.easing.easeInBounce(x,t*2,0,c,d)*.5+b;return jQuery.easing.easeOutBounce(x,t*2-d,0,c,d)*.5+c*.5+b;}});

	/*
	 * jQuery Easing Compatibility v1 - http://gsgd.co.uk/sandbox/jquery.easing.php
	 */
 	jQuery.extend(jQuery.easing,{easeIn:function(x,t,b,c,d){return jQuery.easing.easeInQuad(x,t,b,c,d);},easeOut:function(x,t,b,c,d){return jQuery.easing.easeOutQuad(x,t,b,c,d);},easeInOut:function(x,t,b,c,d){return jQuery.easing.easeInOutQuad(x,t,b,c,d);},expoin:function(x,t,b,c,d){return jQuery.easing.easeInExpo(x,t,b,c,d);},expoout:function(x,t,b,c,d){return jQuery.easing.easeOutExpo(x,t,b,c,d);},expoinout:function(x,t,b,c,d){return jQuery.easing.easeInOutExpo(x,t,b,c,d);},bouncein:function(x,t,b,c,d){return jQuery.easing.easeInBounce(x,t,b,c,d);},bounceout:function(x,t,b,c,d){return jQuery.easing.easeOutBounce(x,t,b,c,d);},bounceinout:function(x,t,b,c,d){return jQuery.easing.easeInOutBounce(x,t,b,c,d);},elasin:function(x,t,b,c,d){return jQuery.easing.easeInElastic(x,t,b,c,d);},elasout:function(x,t,b,c,d){return jQuery.easing.easeOutElastic(x,t,b,c,d);},elasinout:function(x,t,b,c,d){return jQuery.easing.easeInOutElastic(x,t,b,c,d);},backin:function(x,t,b,c,d){return jQuery.easing.easeInBack(x,t,b,c,d);},backout:function(x,t,b,c,d){return jQuery.easing.easeOutBack(x,t,b,c,d);},backinout:function(x,t,b,c,d){return jQuery.easing.easeInOutBack(x,t,b,c,d);}});

}

if(jQuery().smoothScroll){
	$(window).scroll(function(){
		var position = $('body').scrollTop();
		if (position > 500){
			$('.backtotop').fadeIn();
		}
		else {
			$('.backtotop').fadeOut();
		}
	});
	
	var bodyid = $('body').attr('id');
	if(typeof bodyid === "undefined"){
		 bodyid = 'body';
		 $('body').attr('id', bodyid);
	}
	
	if($('.backtotop').length === 0){
		// <a href="#" class="backtotop" style="display: none">^</a>
		var backtotopbutton = $('<a />');
		backtotopbutton.addClass('backtotop').css({
			'display': 'none',
			'padding': '5px 10px',
			'text-decoration': 'none',
		}).attr('href', '#' + bodyid).text('^');
		backtotopbutton.appendTo('body');
	}

	$('.backtotop').smoothScroll({
	  offset: 0,
	  easing: 'easeInOutExpo',
	  speed: 550,
	});
}



// Scrolltape Navigation

$(document).on('mousewheel', '.scrolltape', function(event, delta) {

	this.scrollLeft -= (delta * 30);
	
	event.preventDefault();

});					




helpers = {
	
	getArgs: function(func) {
	  // First match everything inside the function argument parens.
	  var args = func.toString().match(/function\s.*?\(([^)]*)\)/)[1];
	 
	  // Split the arguments string into an array comma delimited.
	  return args.split(',').map(function(arg) {
	    // Ensure no inline comments are parsed and trim the whitespace.
	    return arg.replace(/\/\*.*\*\//, '').trim();
	  }).filter(function(arg) {
	    // Ensure no undefined values are added.
	    return arg;
	  });
	},
		
	filter_int: function(num){
		if(typeof num !== "string") return num;
		else if(isNaN( parseInt(num) )) return num;
		else return parseInt(num);
	},
	
	str_contains: function(haystack, needle){
		return haystack.indexOf(needle) !== -1;
	},
	
	in_array: function(needle, haystack){
		return haystack.indexOf(needle) !== -1;
	},
	
	str_rand: function(length){
		length = !length ? 5 : length;
	    var text = "";
	    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	
	    for( var i=0; i < length; i++ )
	        text += possible.charAt(Math.floor(Math.random() * possible.length));
	
	    return text;
	},
	
	array_rand: function(arr){
		var len = arr.length;
		var i = Math.ceil((Math.random() * len)) - 1;
		return arr[i];
	},
	
	array_keys_by: function(key, array){
		var keys = [];
		array.forEach(function(item){
			keys.push(item[key]);
		});
		return keys;
	},
	
	array_diff: function(sharedkey, array1, array2){
		var array1keys = this.array_keys_by(sharedkey, array1);
		var array2keys = this.array_keys_by(sharedkey, array2);
		var resultingarray = [];
		array1.forEach(function(array1item){
			if(!this.in_array(array1item[sharedkey], array2keys)) resultingarray.push(array1item);
		});
		return resultingarray;
	},
	
	array_except: function(obj, excluded_keys){
		var newobj = {};
		for(key in obj){
			if(!helpers.in_array(key, excluded_keys)) newobj[key] = obj[key];
		}	
		return newobj;
	},
	
	array_only: function(obj, included_keys){
		var newobj = {};
		included_keys.forEach(function(item){
			newobj[item] = obj[item]
		});
		return newobj;
	},
	
	array_dot: function(obj){		
		var res = {};
		(function recurse(obj, current) {
		  for(var key in obj) {
		    var value = obj[key];
		    var newKey = (current ? current + "." + key : key);  // joined key with dot
		    if(value && typeof value === "object") {
		      recurse(value, newKey);  // it's a nested object, so do it again
		    } else {
		      res[newKey] = value;  // it's not an object, so set the property
		    }
		  }
		})(obj);
		return res; 		
	},
		
	ucwords: function(str) {
	    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
	        return $1.toUpperCase();
	    });
	},
	
	queryStringify: function(requestquery){
		var str = '?';
		for(key in requestquery) str += key + '=' + requestquery[key] + '&';
		if(str.substr(-1) == '&') str = str.slice(0, -1);
		return str;
	},
	
	merge_object: function(obj1,obj2){
	    var obj3 = {};
	    for (var attrname in obj1) { obj3[attrname] = obj1[attrname]; }
	    for (var attrname in obj2) { obj3[attrname] = obj2[attrname]; }
	    return obj3;
	},
	
	array_merge: function(obj1,obj2){
		return helpers.merge_object(obj1,obj2);
	},
	// next is similar to above, but returns the original object with the seconds objects properties applied to it.
	array_apply: function(dbobj, values){
		for(valuekey in values) dbobj[valuekey] = values[valuekey];
		return dbobj;
	},
	
	objectify: function(keyby, arr){
		var obj = {};
		arr.forEach(function(val){
			obj[val[keyby]] = val;
		});
		return obj;
	},
	
	getMethods: function(obj){
	    var res = [];
	    for(var m in obj) {
	        if(typeof obj[m] == "function") {
	            res.push(m)
	        }
	    }
	    return res;
	},
	
	dumpComputedStyles: function(elem) {
		var allstyles = {};
		var cs = window.getComputedStyle(elem,null);
		for (var i=0;i<cs.length;i++) {
			if(!helpers.starts_with(cs[i], '-'))
				allstyles[cs[i]] = cs.getPropertyValue(cs[i]);
		}	
		return allstyles;
	},

	starts_with: function(haystack, needle){
		return haystack.slice(0,1) == needle;
	},
		
}