// gather editor styles
var $themestyles = $('head').find('[rel="stylesheet"]').filter('[href*="compiled.css"], [href*="fonts.googleapis"]'); 
//var $themestyles = $('head').find('[rel="stylesheet"]');
var stylesheets = $themestyles.map(function(){
	return $(this).attr('href');
});


/*
$(document).on('click', '.createpageXXX', function(e){
	e.preventDefault();
	var newurl = prompt('Enter Path');
	var request = $.ajax({
		url: $(this).attr('href'),
		type: "PUT",
		data: {
			url : newurl,
			_token : _token,
		}
	});
	
	request.done(function( data ) {
		console.log(data);
		if(data.ok) return window.location.assign(newurl);
	});
	
	request.fail(function( data, jqXHR, textStatus ) {
		console.log(data);
		alert( "Request failed: " + textStatus );
	});	
});
*/


// $(document).on('click', '[type="submit"]', function(e){
// 	e.preventDefault();
// 	var thisname = $(this).attr('name');
// 	if($('[name="' + thisname + '"]').length > 1){
// 		if($('.remove_if_empty_on_save').length){
// 			$('.remove_if_empty_on_save').each(function(){
// 				if($(this).val().length === 0) $(this).remove();
// 			});
// 		}
// 	}
// 	$(this).parents('form').submit();
// });
