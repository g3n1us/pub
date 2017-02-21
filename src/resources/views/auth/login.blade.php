@extends('pub::layouts.editor')


@section('head')
	<link rel="stylesheet" href="/vendor/pub/dist/css/both-compiled.min.css">
	<link rel="stylesheet" href="/vendor/pub/dist/css/private-compiled.min.css">
	<style>

		body{
		    background-image: url(/vendor/pub/images/login_bg.jpg);
		    background-size: cover;
		}
	</style>
@endsection

@section('body')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">

                <div class="card-block text-center">
					<a href="/">
				        <img class="card-img-top" src="{{ $brand->logo }}" style="max-width: 320px;" alt="media_dc_logo" id="Xpublic_logo">
			        </a>	            
	                
	                <h3>Login</h3>
                </div>
				<div class="card-block">   
					<form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {!! csrf_field() !!}
						<div class="row">
						<div class="col-md-12">
							<p class="text-center">
							<a class="btn btn-lg btn-danger" href="/oauth/google"><i class="fa fa-google fa-3x"></i> Login with Google</a>
							</p>
							<h4 class="text-center">or</h4>
						</div>
						
						<div class="col-md-6">
                        <fieldset class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="control-label">E-Mail Address</label>

                            
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            
                        </fieldset>
                        </div>

						<div class="col-md-6">
                        <fieldset class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="control-label">Password</label>

                            
                                <input type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            
                        </fieldset>
                        </div>

						<div class="col-md-6 offset-md-4">
	                        <fieldset class="form-group">
	                            <div class="checkbox">
	                                <label>
	                                    <input type="checkbox" name="remember"> Remember Me
	                                </label>
	                            </div>
	                        </fieldset>
                        </div>

						<div class="col-md-6 offset-md-4">
	                        <fieldset class="form-group">
	                                <button type="submit" class="btn btn-primary">
	                                    <i class="fa fa-btn fa-sign-in"></i>Login
	                                </button>
	
	                                <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>	                            
	                        </fieldset>
                        </div>
</div>                        
                    </form>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
