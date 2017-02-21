@extends('layouts.app')

<!-- Main Content -->
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-default">
                <div class="card-block text-center">
					<a href="/">
				        <img class="card-img-top" src="/files/small/mediadc-logo.svg" style="max-width: 320px;" alt="media_dc_logo" id="Xpublic_logo">
			        </a>	            

					<h3>Reset Password</h3>
                </div>	            

                <div class="card-block">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                        {{ csrf_field() }}
						<div class="row form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 col-lg-3 col-form-label">E-Mail Address</label>
							<div class="col-md-8">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
							</div>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
						</div>
						<div class="row form-group">
	                        <div class="offset-md-4 col-md-8 form-group">
                                <button type="submit" class="btn btn-primary">
                                    Send Password Reset Link
                                </button>
	                        </div>
						</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
