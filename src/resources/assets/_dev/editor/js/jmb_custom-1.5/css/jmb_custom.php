<?php  
	// NOTES
// 	Added .row-inline-block and more padding and margin classes
	
	ob_start() ?>
/*Sticky Footer*/

* { margin:0; padding:0; } 

html, body, #wrap, #fullcontent { height: 100%; }

body > #wrap, body > #fullcontent > #wrap {height: auto; min-height: 100%;}

#main { padding-bottom: 100px; }  /* This offsets the footer and adds a mandatory 50px pad to the bottom */

#footer { 
    position: relative;
	margin-top: -50px; /* negative value of footer height */
	height: auto; /* this is set via javascript */
	clear:both;
} 
		
#footer li {
	padding: 10px 15px 10px;
}
/* CLEAR FIX*/
.clearfix:after {
	content: ".";
	display: block;
	height: 0;
	clear: both;
	visibility: hidden;
}
.clearfix {display: inline-block;}
/* Hides from IE-mac \*/
* html .clearfix { height: 1%;}
.clearfix {display: block;}
/* End hide from IE-mac */

/*End of Sticky Footer*/

/* apply a natural box layout model to all elements */
* { -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; }
/* Except for carousel controls! */
.carousel-control {-moz-box-sizing: content-box; -webkit-box-sizing: content-box; box-sizing: content-box;}


/* PRESENTATIONAL CLASSES */
.unspan {
	float: none;
	margin-left: 0;
} /* This resets the span properties in bootstrap except for width. Use it when you only want to specify a dynamic width using these classes. */

.centered {
  margin-left: auto;
  margin-right: auto;
  display: block;
  position: relative; 
}

.margin10under, .margin-bottom10 {
	margin-bottom: 10px;
}

.margin20under, .margin-bottom20 {
	margin-bottom: 20px;
}

<?php 
	$x = 0;
	while($x <= 500){ ?>
.margin-top<?php echo $x ?>{margin-top: <?php echo $x ?>px}	
	<?php $x = $x + 5; } ?>

<?php 
	$x = 0;
	while($x <= 500){ ?>
.margin-bottom<?php echo $x ?>{margin-bottom: <?php echo $x ?>px}	
	<?php $x = $x + 5; } ?>

<?php 
	$x = 0;
	while($x <= 500){ ?>
.margin-top-neg<?php echo $x ?>{margin-top: -<?php echo $x ?>px}	
	<?php $x = $x + 5; } ?>

<?php 
	$x = 0;
	while($x <= 500){ ?>
.margin-bottom-neg<?php echo $x ?>{margin-bottom: -<?php echo $x ?>px}	
	<?php $x = $x + 5; } ?>

<?php 
	$x = 0;
	while($x <= 500){ ?>
.padding-top<?php echo $x ?>{padding-top: <?php echo $x ?>px}	
	<?php $x = $x + 5; } ?>


<?php 
	$x = 0;
	while($x <= 500){ ?>
.padding-bottom<?php echo $x ?>{padding-bottom: <?php echo $x ?>px}	
	<?php $x = $x + 5; } ?>

<?php 
	$x = 0;
	while($x <= 500){ ?>
.padding-right<?php echo $x ?>{padding-right: <?php echo $x ?>px}	
	<?php $x = $x + 5; } ?>

<?php 
	$x = 0;
	while($x <= 500){ ?>
.padding-left<?php echo $x ?>{padding-left: <?php echo $x ?>px}	
	<?php $x = $x + 5; } ?>

<?php 
	$x = 0;
	while($x <= 500){ ?>
.padding<?php echo $x ?>{padding: -<?php echo $x ?>px}	
	<?php $x = $x + 5; } ?>



.padded10 {
	padding: 10px;
}

.padded0_10 {
	padding: 0 10px;
}

.padded10_0 {
	padding: 10px 0px;
}

.padded20 {
	padding: 20px;
}

.padded20_0 {
	padding: 20px 0;
}

.padded0_20 {
	padding: 0 20px;
}

.padded0_30 {
	padding:0 30px;
}

.padded30 {
	padding: 30px;
}

.padded30_0 {
	padding: 30px 0;
}

.padded40 {
	padding: 40px;
}

.padded40_0 {
	padding: 40px 0;
}

.padded0_40 {
	padding:0 40px;
}

.padded50 {
	padding: 50px;
}

.padded50_0 {
	padding: 50px 0;
}

.padded0_50 {
	padding:0 50px;
}

.spread-shadow, .spread_shadow {
	-webkit-box-shadow: 0px 0px 20px black;
	-moz-box-shadow: 0px 0px 20px black;
	-o-box-shadow: 0px 0px 20px black;
	box-shadow: 0px 0px 20px black;
}


/* Force anchors to show cursor:pointer */
 a {cursor: pointer; }
 
 
/*  Clean up style and add hover functionality to Bootstrap dropdowns  */


ul.nav li.dropdown > ul.dropdown-menu{
	-webkit-transition: all 1s linear;
	-moz-transition: all 1s linear;
	transition: all 1s linear;
}

/*
ul.nav li.dropdown:hover > ul.dropdown-menu{
    display: block !important;    
}
*/

/* Bootstrap Hacks and Tweaks */
	/* Fix ckeditor p-wrapping addition within carousels */
.carousel .item > p{margin: 0}

/*  Add back default responsive images - same as .img-responsive */
img{
	max-width: 100%;
	height: auto;
	display: inline-block;
}

/* End BS Hacks etc. */

ul.nav li.dropdown:hover ul.dropdown-menu{ display: block; }

.nav .dropdown-toggle .caret { display:none; }

.dropdown-menu {margin: 0}

.navbar .nav > li > .dropdown-menu:before, .navbar .nav > li > .dropdown-menu:after  {display: none}

/* For animated poof effect function in jmb_custom.js; empty element #puff must be on page */
#puff {
    cursor:pointer;
    display:none;
    position:absolute;
    height:32px;
    width:32px;
    background: url(images/poofcloud.png) no-repeat;
    z-index: 999999;
}

/* iOS style toggles for input checkbox */

.uiswitch {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  -webkit-appearance: none;
  -moz-appearance: none;
  -ms-appearance: none;
  -o-appearance: none;
  -webkit-appearance: none;
  width: 51px;
  height: 31px;
  position: relative;
  border-radius: 16px;
  cursor: pointer;
  outline: 0;
  z-index: 0;
  margin: 0;
  padding: 0;
  border: none;
  background-color: #e5e5e5;
  -webkit-transition-duration: 600ms;
  -moz-transition-duration: 600ms;
  transition-duration: 600ms;
  -webkit-transition-timing-function: ease-in-out;
  -moz-transition-timing-function: ease-in-out;
  transition-timing-function: ease-in-out;
}
.uiswitch::before {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  width: 47px;
  height: 27px;
  content: ' ';
  position: absolute;
  left: 2px;
  top: 2px;
  background-color: #ffffff;
  border-radius: 16px;
  z-index: 1;
  -webkit-transition-duration: 300ms;
  -moz-transition-duration: 300ms;
  transition-duration: 300ms;
  -webkit-transform: scale(1);
  -moz-transform: scale(1);
  -ms-transform: scale(1);
  -o-transform: scale(1);
  transform: scale(1);
}
.uiswitch::after {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  width: 27px;
  height: 27px;
  content: ' ';
  position: absolute;
  border-radius: 27px;
  background: #ffffff;
  z-index: 2;
  top: 2px;
  left: 2px;
  box-shadow: 0px 0px 1px 0px rgba(0, 0, 0, 0.25), 0px 4px 11px 0px rgba(0, 0, 0, 0.08), -1px 3px 3px 0px rgba(0, 0, 0, 0.14);
  -webkit-transition: transform 300ms, width 280ms;
  -moz-transition: transform 300ms, width 280ms;
  transition: transform 300ms, width 280ms;
  -webkit-transform: translate3d(0, 0, 0);
  -moz-transform: translate3d(0, 0, 0);
  -ms-transform: translate3d(0, 0, 0);
  -o-transform: translate3d(0, 0, 0);
  transform: translate3d(0, 0, 0);
  -webkit-transition-timing-function: cubic-bezier(0.42, 0.8, 0.58, 1.2);
  -moz-transition-timing-function: cubic-bezier(0.42, 0.8, 0.58, 1.2);
  transition-timing-function: cubic-bezier(0.42, 0.8, 0.58, 1.2);
}
.uiswitch:checked {
  background-color: #4CD964;
  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #4CD964), color-stop(100%, #4dd865));
  background-image: -webkit-linear-gradient(-90deg, #4CD964 0%, #4dd865 100%);
  background-image: linear-gradient(-180deg,#4CD964 0%, #4dd865 100%);
}
.uiswitch:checked::after {
  -webkit-transform: translate3d(16px, 0, 0);
  -moz-transform: translate3d(16px, 0, 0);
  -ms-transform: translate3d(16px, 0, 0);
  -o-transform: translate3d(16px, 0, 0);
  transform: translate3d(16px, 0, 0);
  right: 18px;
  left: inherit;
}
.uiswitch:active::after {
  width: 35px;
}
.uiswitch:checked::before, .uiswitch:active::before {
  -webkit-transform: scale(0);
  -moz-transform: scale(0);
  -ms-transform: scale(0);
  -o-transform: scale(0);
  transform: scale(0);
}
.uiswitch:disabled {
  opacity: 0.5;
  cursor: default;
  -webkit-transition: none;
  -moz-transition: none;
  transition: none;
}
.uiswitch:disabled:active::before, .uiswitch:disabled:active::after, .uiswitch:disabled:checked:active::before, .uiswitch:disabled:checked::before {
  width: 27px;
  -webkit-transition: none;
  -moz-transition: none;
  transition: none;
}
.uiswitch:disabled:active::before {
  width: 41px;
  height: 27px;
  -webkit-transform: translate3d(6px, 0, 0);
  -moz-transform: translate3d(6px, 0, 0);
  -ms-transform: translate3d(6px, 0, 0);
  -o-transform: translate3d(6px, 0, 0);
  transform: translate3d(6px, 0, 0);
}
.uiswitch:disabled:checked:active::before {
  width: 27px;
  height: 27px;
  -webkit-transform: scale(0);
  -moz-transform: scale(0);
  -ms-transform: scale(0);
  -o-transform: scale(0);
  transform: scale(0);
}

.uiswitch {
  background-color: #e5e5e5;
}
.uiswitch::before {
  background-color: #ffffff;
}
.uiswitch::after {
  background: #ffffff;
}
.uiswitch:checked {
  background-color: #4CD964;
  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #4CD964), color-stop(100%, #4dd865));
  background-image: -webkit-linear-gradient(-90deg, #4CD964 0%, #4dd865 100%);
  background-image: linear-gradient(-180deg,#4CD964 0%, #4dd865 100%);
}


/* Back to Top link CSS */
.backtotop {
	position: fixed;
	bottom: 50%;
	right: 5px;
	background: rgba(0, 0, 0, 0) -webkit-linear-gradient(rgba(255, 255, 255, 0.6) 0%, #FFF 70.2%, rgba(255, 255, 255, 0.51) 100%);
	background: transparent -moz-linear-gradient(rgba(255,255,255,0.6) 0%, #ffffff 70.2%, rgba(255,255,255,0.51) 100%);
	background: transparent -o-linear-gradient(rgba(255,255,255,0.6) 0%, #ffffff 70.2%, rgba(255,255,255,0.51) 100%);
	background: transparent -ms-linear-gradient(rgba(255,255,255,0.6) 0%, #ffffff 70.2%, rgba(255,255,255,0.51) 100%);
	background: rgba(0, 0, 0, 0) linear-gradient(rgba(255, 255, 255, 0.6) 0%, #FFF 70.2%, rgba(255, 255, 255, 0.51) 100%);
	border-radius: 5px 5px;
	box-shadow: inset 0px 0px 5px #7B7B7B;
	text-decoration: none;
	font-size: 40px;
	display: none;
	-webkit-transition: all 200ms cubic-bezier(0.250, 1, 0.850, 1);
	-webkit-transition: all 200ms cubic-bezier(0.250, 1.650, 0.850, 1.005);
	-moz-transition: all 200ms cubic-bezier(0.250, 1.650, 0.850, 1.005);
	-ms-transition: all 200ms cubic-bezier(0.250, 1.650, 0.850, 1.005);
	-o-transition: all 200ms cubic-bezier(0.250, 1.650, 0.850, 1.005);
	transition: all 200ms cubic-bezier(0.250, 1.650, 0.850, 1.005);
	opacity: .3;
	height: 40px;
	line-height: 50px;
	text-decoration: none;
}

.backtotop:hover {
	box-shadow: inset 0px 0px 1px #7B7B7B;
	text-decoration: none;
	opacity: 1;
}

/*! Absolute centering vertical and horizontal. Caveat: parent must be relative
!important .absolute-center elements get css size added via js in jmb_custom.js based on innerHeight. Add class .no-autosize */
.center-container {
  position: relative;
}

.absolute-center {
/*
	width: 50%;
	height: 50%;
*/
	margin: auto;
	position: absolute;
	overflow: auto;
	top: 0; left: 0; bottom: 0; right: 0;
}

.absolute-center.is-left {
  right: auto; left: 20px;
  text-align: left;
}

.absolute-center.is-right {
  left: auto; right: 20px;
  text-align: right;
}

.absolute-center.is-image {
  height: auto;
}

.absolute-center.is-image img { 
  width: 100%;
  height: auto;
}

.absolute-center.is-responsive {
	width: 60%;
	height: 60%;
	min-width: 200px;
	max-width: 400px;
	padding: 40px;
}

/* CSS to go along with jmb_custom.js functions */

.jmb_slider{
	visibility: hidden; /* This fades in the slider after js completes */
}

.in_edit_mode .jmb_slider, .edit_mode .jmb_slider{
	display: block; /* This fades in the slider after js completes */
	visibility: visible;
}


/* Scroll-tape Responsive Nav
	- apply this to a nav-tabs or nav-pills. Makes it scrollable sideways.	
*/
.scrolltape, .nav.scrolltape {
	overflow-y: auto;
	-webkit-overflow-scrolling: touch;
}
.scrolltape.forced{
	-webkit-box-shadow: inset 7px 0px 10px -7px #000000, inset -7px 0px 10px -7px #000000;
	box-shadow: inset 7px 0px 10px -7px #000000, inset -7px 0px 10px -7px #000000;
}

.scrolltape > li, nav.scrolltape > li{
	float: none;
	display: table-cell;
	vertical-align: bottom;
	width: 1%;
}

@media(max-width: 768px){
	.scrolltape, .nav.scrolltape{
		-webkit-box-shadow: inset 7px 0px 10px -7px #000000, inset -7px 0px 10px -7px #000000;
		box-shadow: inset 7px 0px 10px -7px #000000, inset -7px 0px 10px -7px #000000;
	}
}

.row-inline-block [class*="col-"] {
	display: inline-block;
	float: none;
	text-align: left;
	vertical-align: top;
	margin-right: -0.125em;
	margin-right: -.4rem;
}



/* DEFAULT BOOTSTRAP 2 RESPONSIVE BREAKPOINTS */

@media (min-width: 1200px) {
	
}

@media (max-width: 767px) {
	[class*="margin-top-neg"] {
		margin-top: 0px;
	}
}

@media (max-width: 480px) {
	body {
		padding-right: 0px;
		padding-left: 0px;
	}
}

<?php
$code = ob_get_clean();	
/*
$code = str_replace("\r\n", "\n", $code);
$code = str_replace("\n", "", $code);
$code = str_replace("\t", "", $code);
*/
var_dump(file_put_contents(dirname(__FILE__) . "/jmb_custom.css", $code));
// echo($code);
?>