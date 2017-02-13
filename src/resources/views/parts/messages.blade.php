

			<div id="messagearea" style="position: fixed; top: 0; left: 0; right: 0; width: 100%; max-width: 800px; z-index: 999999999; margin: auto;">
@if(session('errors') || session('error'))
				<div class="alert alert-danger fade in">
				    <a class="close" data-dismiss="alert"><i class="fa fa-close"></i></a>					
				    <ul style="list-style-type: none; margin-bottom: 0;">

				        @foreach(session('errors', []) as $error)
				            <li>{$error}</li>
				        @endforeach

				        @foreach($errors->all() as $error)
				            <li>{{$error}}</li>
				        @endforeach

				        @if(session('error'))<li>{{session('error')}}</li>@endif
				    </ul>
				</div>
				<script>
					_form_errors = {{$errors->toJson()}};
					$(document).ready(function(){
						$.each(_form_errors, function(k,v){
							$input = $('[name="'+k+'"]');
							$label = $('[for="'+k+'"]');
							$input.addClass('form-control-danger').after('<div class="form-control-feedback">'+v+'</div>');
							$input.parents('fieldset').addClass('has-danger');
						})						
					});
				</script>
@endif
@if(session('status'))
				<div class="alert alert-success fade in" style="position: fixed; width: 90%; z-index: 9999; left: 5%; ">
				    {{ Request::session()->pull('status') }}
				    <a class="close" data-dismiss="alert"><i class="fa fa-close"></i></a>
				</div>
@endif
@if(session('message'))
				<div class="alert alert-info fade in" style="position: fixed; width: 90%; z-index: 9999; left: 5%; ">
				    {{ Request::session()->pull('message') }}
				    <a class="close" data-dismiss="alert"><i class="fa fa-close"></i></a>
				</div>
@endif
			</div>
			
				<script>
					$(document).ready(function(){
						if($('#messagearea .alert').length){
							setTimeout(function(){ $('#messagearea .alert').fadeOut('fast'); }, 3000);
						}
					});
				</script>
			