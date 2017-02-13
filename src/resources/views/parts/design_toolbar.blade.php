
<style>
	.editor-bootstrap .slidepanel .card{
		display: block;
		width: 90%;
/*
		flex-direction: column;
		flex-grow: 1;
		justify-content: space-around;
*/
	}
</style>
<div id="right_slidepanel" class="slidepanel editor-bootstrap">
<div class="slidepaneledge right">
	<div class="ridge"></div>
	<div class="ridge"></div>
	<div class="ridge"></div>
	<div class="ridge"></div>
	<div class="ridge"></div>
	<div class="ridge"></div>
	<div class="ridge"></div>
	<div class="ridge"></div>	
</div>
<div class="slidepanel-inner">
	<div class="slidepanelcontent">
		<div class="links btn-group btn-group-justified mb-half">
		<a style="cursor:pointer; text-decoration:none; " data-toggle="modal" href="#todo-modal" class="show_todos btn btn-info btn-sm"> <i class="fa fa-pencil"></i> Todo</a>
		<a style="cursor:pointer; text-decoration:none; " class="show_pointers btn btn-info btn-sm" tabindex> <i class="fa fa-comment-o"></i> </a>
		<span style="cursor:pointer; text-decoration:none;" class="Xshow_help btn btn-info btn-sm" data-toggle="modal" data-target="#multi_modal" data-size="modal-lg" data-name="Help"> <i class="fa fa-question"></i><div class="hidden">Documentation to Follow</div></span>
	</div>
	
			
	<div class="btn-group btn-group-justified mb-half">
		<a onclick="undoLastSave()" class="save btn btn-warning btn-sm" tabindex><i class="fa fa-undo"></i> Undo</a>
		<a onclick="redoLastSave()" class="save btn btn-warning btn-sm" tabindex><i class="fa fa-undo fa-flip-horizontal"></i> Revert - Last Save</a>
	</div>
	
	
	
	<a class="btn btn-sm btn-primary btn-block mb-half" onclick="openFilemanager()" tabindex><i class="fa fa-file-image-o"></i> Files</a>


<div class="panel-group" id="settings-accordion">
	
	<div class="card">
		<div class="card-header" data-toggle="collapse" data-target="#presets-well" data-parent="#settings-accordion">
			<i class="fa fa-columns"></i> Presets
		</div>
		
		<div class="collapse" id="presets-well">
			<div class="card-block">
				<label class="custom-control custom-radio">
					<input name="block_preset" type="radio" value="card" class="custom-control-input">
					<span class="custom-control-indicator"></span>
					<span class="custom-control-description">Card</span>
				</label>
				
				<label class="custom-control custom-radio">
					<input name="block_preset" type="radio" value="slideshow" class="custom-control-input">
					<span class="custom-control-indicator"></span>
					<span class="custom-control-description">Slideshow</span>
				</label>
				<button type="button" class="btn btn-sm btn-primary mt-half">Apply</button>
			</div>
		</div>
	</div>	
	

	<div class="card">
		<div class="card-header" data-toggle="collapse" data-target="#sizing-well" data-parent="#settings-accordion">
			<i class="fa fa-columns"></i> Sizing and Dividing
		</div>
		
		<div class="collapse" id="sizing-well">
			<div class="card-block">
					
				<h5>Resize</h5>
					<div class="form-group">
						<input class="value form-control col_width" type="text" readonly>
					</div>
					
					<div class="form-group">
						<input type="range" min="1" max="12" step="1" name="width" class="width form-control col_width" data-cssprop="col_width">
					</div>
					
				<h5>Offset</h5>
				
					<div class="form-group">
						<input class="value form-control col_offset" type="text" readonly>
					</div>
					
					<div class="form-group">
						<input type="range" min="0" max="11" step="1" name="offset" class="offset col_offset form-control" data-cssprop="col_offset">
					</div>
			</div>
		</div>
	</div>



<!-- End of settings group -->


	<div class="card">
		<div class="card-header" data-toggle="collapse" data-target="#border-well" data-parent="#settings-accordion">
			<i class="fa fa-square-o"></i> Border
		</div>
	
		<div class="collapse" id="border-well">
			<div class="card-block">
			
			
				<div class="property-group">
					<h5>Border Width</h5>
					
					<div class="form-group">
						<div class="input-group">
							<input type="text" class="value form-control" readonly>
							<span class="input-group-addon">px</span>
						</div>
					</div>
					
					<div class="form-group">
						<input type="range" min="0" max="50" name="border-width" class="form-control" data-cssprop="border-width" data-cssunit="px" data-csstemplate="">
					</div>
					
				</div>
				
				<div class="property-group">
					<h5>Border Style</h5>
					
					<div class="form-group">
						<select name="border-style" class="form-control" data-cssprop="border-style">
						  <option value=""> -- </option>
						  <option value="solid">solid</option>
						  <option value="dotted">dotted</option>
						  <option value="dashed">dashed</option>
						  <option value="inset">inset</option>
						  <option value="outset">outset</option>
						</select>
					</div>
					
				</div>
				
				<div class="property-group">
					<h5>Border Color</h5>
					
					<div class="form-group">
						<input class="value form-control" type="text" readonly>
					</div>
					
					<div class="form-group">
						<input type="color" name="border-color" class="form-control" data-cssprop="border-color" data-cssunit="" data-csstemplate="">
					</div>
					
				</div>
				
				<div class="property-group">
					<h5>Border Radius</h5>
					
					<div class="form-group">
						<div class="input-group">
							<input type="text" class="value form-control" readonly>
							<span class="input-group-addon">px</span>
						</div>
					</div>
					
					<div class="form-group">
						<input type="range" min="0" max="500" name="border-radius" class="form-control special" data-cssprop="border-radius" data-cssunit="" data-csstemplate="">
					</div>
					
				</div>
			
			</div>
		</div>
		
	</div>
		




<!-- End of settings group -->






	<div class="card">
		<div class="card-header" data-toggle="collapse" data-target="#shadows-well" data-parent="#settings-accordion">
			<i class="fa fa-moon-o"></i> Shadows
		</div>
	
		<div class="collapse" id="shadows-well">
			<div class="card-block">
			
			@verbatim
				<h5>Shadow</h5><input class="value form-control" type="text"><input type="range" min="0" max="100" name="box-shadow" class="form-control special" data-cssprop="box-shadow" data-cssunit="" data-csstemplate="{{x}} {{y}} {{blur}} {{spread}} {{color}}">
			@endverbatim
				<h5><small>Shadow Direction</small></h5>
				<div class="btn-group btn-group-justified shadow-type" data-toggle="buttons">
				  <label class="btn btn-default btn-sm active" data-value="all">
				    <input class="enabled special" type="radio"> All
				  </label>
				  <label class="btn btn-default btn-sm" data-value="bottom">
				    <input class="enabled special" type="radio"> Bottom
				  </label>
				  <label class="btn btn-default btn-sm" data-value="right">
				    <input class="enabled special" type="radio"> Right
				  </label>
				  <label class="btn btn-default btn-sm" data-value="left">
				    <input class="enabled special" type="radio"> Left
				  </label>
				</div>
			
			
			
			</div>
		</div>
		
	</div>
		




<!-- End of settings group -->





	<div class="card">
		<div class="card-header" data-toggle="collapse" data-target="#text-well" data-parent="#settings-accordion">
			<i class="fa fa-font"></i> Text
		</div>
	
		<div class="collapse" id="text-well">
			<div class="card-block">
			
				<div class="property-group">
					<h5>Color</h5>
					
					<div class="form-group">
							<input type="text" class="value form-control" readonly>
					</div>
					
					<div class="form-group">
						<input type="color" name="color" class="form-control" data-cssprop="color" data-cssunit="" data-csstemplate="">
					</div>
					
				</div>

			
				<div class="property-group">
					<h5>Line Height</h5>
					
					<div class="form-group">
						<div class="input-group">
							<input type="text" class="value form-control" readonly>
						</div>
					</div>
					
					<div class="form-group">
						<input type="range" min="0" max="10" step=".25" name="line-height" class="form-control" data-cssprop="line-height" data-cssunit="em" data-csstemplate="">
					</div>
					
				</div>

			
				<div class="property-group">
					<h5>Text Shadow</h5>
					
					<div class="form-group">
							<input type="text" class="value form-control" readonly>
					</div>
					
					<div class="form-group">
						<input type="checkbox" name="text-shadow" class="form-control" data-cssprop="text-shadow" value="2px 2px 5px black" data-cssunit="" data-csstemplate="">
					</div>
				</div>

			
<!-- <h5>color</h5><input class="value form-control" type="text"><input type="color" name="color" class="form-control" data-cssprop="color" data-cssunit="" data-csstemplate=""> -->
<!-- <h5>text shadow</h5><input class="value form-control" type="text"><input type="checkbox" value="2px 2px 5px black" name="text-shadow" class="form-control" data-cssprop="text-shadow" data-cssunit="" data-csstemplate=""> -->
						
			</div>
		</div>
		
	</div>
		



<!-- End of settings group -->




	<div class="card">
		<div class="card-header" data-toggle="collapse" data-target="#background-well" data-parent="#settings-accordion">
				<i class="fa fa-barcode"></i> Background
		</div>
	
		<div class="collapse" id="background-well">
			<div class="card-block">
			
				<div class="property-group">
					<h5>background-color</h5>
					
					<div class="form-group">
							<input type="text" class="value form-control" readonly>
					</div>
					
					<div class="form-group">
						<input type="color" name="background-color" class="form-control" data-cssprop="background-color" data-cssunit="" data-csstemplate="">
					</div>
					
				</div>
				

			
				<div class="property-group">
				
					<h5>background-image</h5>
					
					<div class="form-group">
						<a href="#textures" role="button" class="btn btn-default btn-sm btn-block" data-toggle="modal">Choose background images...</a>
					</div>
					
					<div class="form-group">
						<input type="text" name="background-image" class="background-image form-control" data-cssprop="background-image" data-cssunit="" data-csstemplate="">	
					</div>	
					
				</div>	
			
			</div>
		</div>
		
	</div>
		







<!-- End of settings group -->




	<div class="card">
		<div class="card-header" data-toggle="collapse" data-target="#padding-well" data-parent="#settings-accordion">
			<i class="fa fa-square-o"></i> Margin/Padding
		</div>
	
		<div class="collapse" id="padding-well">
			
			<div class="card-block">
			
				<h5>Padding <button type="button" data-toggle="collapse" class="btn btn-xs btn-default" data-target="#paddingextras">show</button></h5>
				
				<h6>Top</h6>
				<div class="form-group">
						<div class="input-group">
							<input type="text" class="value form-control" readonly>
							<span class="input-group-addon">px</span>
						</div>
				</div>
				
				<div class="form-group">
					<input type="range" min="0" max="7" name="padding" class="special padding form-control" data-cssprop="padding-top" data-cssunit="px" data-csstemplate="" data-classprefix="pt-">
				</div>	
				
				<h6>Right</h6>
				<div class="form-group">
						<div class="input-group">
							<input type="text" class="value form-control" readonly>
							<span class="input-group-addon">px</span>
						</div>
				</div>
				
				<div class="form-group">
					<input type="range" min="0" max="7" name="padding" class="special padding form-control" data-cssprop="padding-right" data-cssunit="px" data-csstemplate="" data-classprefix="pr-">
				</div>	
				
				<div id="paddingextras" class="collapse">
					<h6>Bottom</h6>
					<div class="form-group">
							<div class="input-group">
								<input type="text" class="value form-control" readonly>
								<span class="input-group-addon"></span>
							</div>
					</div>
					
					<div class="form-group">
						<input type="range" min="0" max="7" name="padding" class="special padding form-control" data-cssprop="padding-bottom" data-cssunit="px" data-csstemplate="" data-classprefix="pb-">
					</div>	
					
					<h6>Left</h6>
					<div class="form-group">
							<div class="input-group">
								<input type="text" class="value form-control" readonly>
								<span class="input-group-addon"></span>
							</div>
					</div>
					
					<div class="form-group">
						<input type="range" min="0" max="7" name="padding" class="special padding form-control" data-cssprop="padding-left" data-cssunit="px" data-csstemplate="" data-classprefix="pl-">
					</div>	
				</div>
						
				<h5>Margin  <button type="button" class="btn btn-xs btn-default" data-toggle="collapse" data-target="#marginextras">show</button></h5>
				
				<h6>Top</h6>
				<div class="form-group">
						<div class="input-group">
							<input type="text" class="value form-control" readonly>
							<span class="input-group-addon"></span>
						</div>
				</div>
				
				<div class="form-group">
					<input type="range" min="0" max="7" name="margin" class="special margin form-control" data-classprefix="mt-" data-cssprop="margin-top" data-cssunit="px" data-csstemplate="">
				</div>	
				
				<h6>Right</h6>
				<div class="form-group">
						<div class="input-group">
							<input type="text" class="value form-control" readonly>
							<span class="input-group-addon"></span>
						</div>
				</div>
				
				<div class="form-group">
					<input type="range" min="0" max="7" name="margin" class="special margin form-control" data-classprefix="mr-"  data-cssprop="margin-right" data-cssunit="px" data-csstemplate="">
				</div>	
				
				<div id="marginextras" class="collapse">
					<h6>Bottom</h6>
					<div class="form-group">
							<div class="input-group">
								<input type="text" class="value form-control" readonly>
								<span class="input-group-addon"></span>
							</div>
					</div>
					
					<div class="form-group">
						<input type="range" min="0" max="7" name="margin" class="special margin form-control" data-classprefix="mb-"  data-cssprop="margin-bottom" data-cssunit="px" data-csstemplate="">
					</div>	
					
					<h6>Left</h6>
					<div class="form-group">
							<div class="input-group">
								<input type="text" class="value form-control" readonly>
								<span class="input-group-addon"></span>
							</div>
					</div>
					
					<div class="form-group">
						<input type="range" min="0" max="7" name="margin" class="special margin form-control" data-cssprop="margin-left" data-cssunit="px" data-csstemplate="" data-classprefix="ml-" >
					</div>	
				</div>
			</div>
						
		</div>
		
	</div>
		
	<button class="btn btn-block save_style_editor btn-success margin-top20">Save</button><button class="btn btn-block cancel_style_editor btn-warning margin-top20">Cancel</button>
	<script >
/*

		$('.makeintoclass').click(function(){
			var defaultprompttext = "";
			
			if($('.editing').is('body, #footer, .navbar, #main')){
				var targetdiv = $('.editing');
				if(targetdiv.is('body')) defaultprompttext = 'body';
				if(targetdiv.is('#footer')) defaultprompttext = '#footer';
				if(targetdiv.is('.navbar')) defaultprompttext = '.navbar';
				if(targetdiv.is('#main')) defaultprompttext = '#main';
				
			}
			else{
				var targetdiv = $('.editing').children('div:first');
			}
			
			var classname = prompt('Name of Selector', defaultprompttext);
			if(classname){
				if(defaultprompttext == "") {
					// it has no dot
					if(classname.indexOf(".") == -1){
						targetdiv.addClass(classname);
						classname = "." + classname;
					} 
					// it HAS a dot
					else{
						var nodotname = classname.replace(".", "");
						targetdiv.addClass(nodotname);
					}
				}
				
				var styles = targetdiv.attr('style');
				styles = styles.replace(/;/g, ";\n\t");
				$('#custom_style_classes').prepend(classname + "{ \n\t" + styles + "\n }\n\n");
				targetdiv.removeAttr('style');
			}
		});
			$(document).on('click', '.slidepaneledge', function(){
				$(this).parent().toggleClass('open');
			});			
	
*/	

	</script>



<!-- End of settings group -->
</div> <!-- end of settings accordion group -->
 
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>



<script>

function divide(){
	var divs = $('.number_divs').val();
	divclass = 12/divs;
	var newspan = "\n\t<div class='col-md-" + divclass + "'>\n\t\t<div class='edit contenteditable'></div>\n\t</div>";
	clear_ckeditor();
	var existingcontent = $.trim($('.editing').html());
	//var styles = $('.editing').attr('style');
	var dupe_check = $('.editing').length;
	if (dupe_check > 1) alert('More than one area selected, choose only one');
	else if (dupe_check < 1) alert('You must select an area');
	else {
		for(var i = 0; i < divs; i++) {
			$('.editing').after(newspan);	
		}
		if(existingcontent != "") $('.editing').next().html(existingcontent);
		
		$('.editing').remove();
		stopedit();
// 		reset_ckeditor();
		$('.number_divs').val('');
	}
}	


$(document).on('click', '.save_style_editor', function(){
	var jqobj = $('.editing').first();
	var classes = jqobj.attr('class').trim();
	classes = classes.split(' ').filter(function(val){
		return val != 'editing' && val != 'blockeditwrapper';
	});
	console.log(jqobj.attr('style'));
	var styles = (jqobj.attr('style') || '').trim();
	styles = styles.split(';').map(function(val){
		return val.trim();
	}).filter(function(val){
		return val.length;
	});
	var data = {};
	data.config = {};
	data.config['config->blockclasses'] = classes.join(' ');
	data.config['config->styles'] = styles.join(';');
	data._token = _token;
	var request = $.ajax({
		url: '/block/' + jqobj.data('bid'),
		type: "PUT",
		data: data,
	});
	
	request.done(function( data ) {
		console.log(data);
//		alert(data.message);
	});
	
	console.log(classes);
	console.log(styles);
	
	$('.editor-out-of-focus').removeClass('editor-out-of-focus');
	$('#right_slidepanel').removeClass('open');
	
});

$(document).on('click', '.cancel_style_editor', function(){
	$('.editor-out-of-focus').removeClass('editor-out-of-focus');
	$('#right_slidepanel').removeClass('open');	
});
function stopedit(){
	$('.editing, .editing-hover').removeClass('editing editing-hover');
	$('body').removeClass('editing_header editing_footer editing_maincontent')
//	$('.editing-hover').removeClass('editing-hover');
//	activate();
}
	
	
function rgb2hex(rgb){
 rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
 return (rgb && rgb.length === 4) ? "#" +
  ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
  ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
  ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
}

var stylesArray = ['border-width', 'border-style', 'border-color', 'border-radius', 'box-shadow', 'color', 'font-family', 'background-image', 'background-color', 'padding-top', 'padding-bottom', 'padding-right', 'padding-left', 'margin-top', 'margin-right', 'margin-bottom', 'margin-left', 'line-height'];

var cssObject = {};

/*
$(document).on('change', '[data-cssprop]', function(){
	alert($(this).val());
});
*/

function selectArea(jqobj){
	if(typeof jqobj === "string") jqobj = $('[data-tmpid="'+jqobj+'"]');
	jqobj.removeClass('blockeditwrapper_focused').blur();
	console.log(jqobj);
	cssObject = {};
	$.each(stylesArray, function(key, value){
		var targetInput = $('[data-cssprop="' + value + '"]');

		cssObject[value] = {
			selector : targetInput,
			unit : targetInput.data('cssunit'),
			template : targetInput.data('csstemplate'),
			min : targetInput.attr('min'),
			max : targetInput.attr('max'),
			type : targetInput.attr('type'),
			value : targetInput.val()
		}
	});
	
	// if(typeof jqobj == "undefined") jqobj = this;
	$('.editing').not(jqobj).removeClass('editing');
	jqobj.addClass('editing');
	
	if(!$('.editing').length) $('body').removeClass('editing_header editing_footer editing_maincontent');

	$('#right_slidepanel').addClass('open');
//	$('#edit_popover').remove();
	$('*').not('.editing, .editing *')
		.not($('.editing').parents())
		.not($('#right_slidepanel').parents())
		.not('#right_slidepanel, #right_slidepanel *')
		.addClass('editor-out-of-focus')	


// 	if(jqobj.is('body, #footer, .navbar, #main')) {
// 		
// 		var innerObj = jqobj;
// 	}
// 	else var innerObj = jqobj.children('div:first');
	var innerObj = jqobj;	
	// console.log(innerObj);
	if(innerObj.length) var stylesObj = innerObj.css(stylesArray);
	else var stylesObj = [];
	
	// console.log(stylesObj);
	// iterate over object and make rgba to hex conversions
	
	$.each(stylesObj, function(key, value){
		var targetInput = $('[data-cssprop="' + key + '"]');	
		if(value.indexOf("rgb") > -1){
			stylesObj[key] = rgb2hex(value);
			// targetInput.css('background-color', value);
		}
		else if(value.indexOf("px") > -1){
			stylesObj[key] = value.replace('px', '');
		}
		targetInput.val(stylesObj[key]);
		targetInput.parent().prev().find('input').val(stylesObj[key]);
		// targetInput.after('<p>' + stylesObj[key] + '</p>');

	});
	// console.log(cssObject);
	
	var numColumns = jqobj.parents('.row').children('.blockeditwrapper').length;
//	alert(numColumns);
	$('.number_divs').val(numColumns);
	
	var colWidth = jqobj.attr('class');
	colWidth = colWidth.split(' ');
	var newColWidth = $.map(colWidth, function(value){
		if(value.indexOf("col-md-") > -1 && value.indexOf("col-md-offset") == -1){
			return parseInt(value.replace("col-md-", ""));
		}
	});
	
	$('.col_width').val(newColWidth);
	
	var colOffset = jqobj.attr('class');
	colOffset = colOffset.split(' ');
	var newColOffset = $.map(colOffset, function(value){
		if(value.indexOf("col-md-offset-") > -1){
			return parseInt(value.replace("col-md-offset-", ""));
		}
	});
	
	
	// console.log(newColOffset);
	
	$('.col_offset').val(newColOffset);
	
}	


/*
$(document).on('click', '.blockeditwrapper', function(event){
	event.stopPropagation();
	selectArea($(this));
});
*/


$(document).on('change input', '.slidepanelcontent input.number_divs', function(){
	var changeval = $(this).val();
	$(this).parent().prev().find('input').val(changeval);
	//$(this).prev('.value').val(changeval);
});


$(document).on('change input', '.slidepanelcontent input[data-cssprop], .slidepanelcontent select[data-cssprop]', function(){
	if(!$(this).hasClass('special')){				
		var changetype = $(this).attr('data-cssprop');
		var changeval = $(this).val();
		
		// $(this).prev('.value').val(changeval);
		$(this).parent().prev().find('input').val(changeval);
		
		if (changetype == 'col_width'){
			$('.editing').removeClass('col-md-12 col-md-11 col-md-10 col-md-9 col-md-8 col-md-7 col-md-6 col-md-5 col-md-4 col-md-3 col-md-2 col-md-1').removeClass('col-md-' + changeval).addClass('col-md-' + changeval);
		}
		else if (changetype == 'col_offset'){
			$('.editing').removeClass('offset-md-11 offset-md-10 offset-md-9 offset-md-8 offset-md-7 offset-md-6 offset-md-5 offset-md-4 offset-md-3 offset-md-2 offset-md-1').addClass('offset-md-' + changeval);
		}
		else {
			$('.editing').css(changetype, changeval);			
		}
	}
});

//these need px appended
$(document).on('change input', '.slidepanelcontent input[data-cssprop="border-radius"], .slidepanelcontent input[data-cssprop="border-width"]', function(){

	var changetype = $(this).attr('data-cssprop');
	var changeval = $(this).val() + 'px';
	$(this).parent().prev().find('input').val(changeval);
	$('.editing').css(changetype, changeval);
});

//these are done with utility classes
$(document).on('change input', '.slidepanelcontent input[data-cssprop*="padding"], .slidepanelcontent input[data-cssprop*="margin"]', function(){
	var classprefix = $(this).data('classprefix');
	var translatedval = $(this).val();
	if(translatedval == 1) translatedval = "half";
	else if(translatedval == 0) translatedval = 0;
	else translatedval = translatedval - 1;
	var utilityclass = classprefix + translatedval;
	$(this).parent().prev().find('input').val(utilityclass);
	$('.editing').removeClass (function (index, css) {
		console.log(index);
		console.log(css);
		var re = new RegExp(classprefix + '(.*)',"g");
		console.log(css.match(re));
	    return (css.match(re) || []).join(' ');
	});	
	$('.editing').addClass(utilityclass);
});


//$('.slidepanelcontent input[data-cssprop="box-shadow"]').change(function(){		


//this is box-shadow
$(document).on('change input', '.slidepanelcontent input[data-cssprop="box-shadow"]', function(){

	var type = $('.shadow-type .active').attr('data-value');
	var changetype = $(this).attr('data-cssprop');
	var changeval = $(this).val() + 'px';
	if (type == 'all'){
		changeval = '0px 0px ' + $(this).val() + 'px black';
	}
	else if (type == 'bottom'){
		thisvalue = $(this).val();
		changeval = '0 ' + thisvalue*1.2 + 'px ' + thisvalue + 'px -' + thisvalue + 'px black';
	}
	else if (type == 'right'){
		thisvalue = $(this).val();
		changeval = thisvalue + 'px ' + thisvalue + 'px ' + thisvalue*2 + 'px black';
	}
	else if (type == 'left'){
		thisvalue = $(this).val();
		changeval = '-' + thisvalue + 'px ' + thisvalue + 'px ' + thisvalue*2 + 'px black';
	}
	
	$(this).parent().prev().find('input').val(changeval);
	

	$('.editing').css(changetype, changeval);
	
	
});



$(document).on('click', 'button.split', function(){
	divide();
});

$(document).on('click', '.slidepaneledge', function(){
	$(this).parent('.slidepanel').toggleClass('open');
});

</script>