@extends("pub::layouts.editor")

@push('body_html_classes')
logged_in
@endpush

@section('body')
<div class="container pt-3" style="background-color: rgba(255, 255, 255, 0.85)">
	
    <div class="row">
	    <div class="col-md-12">
		    <a class="btn btn-primary btn-lg" href="/dashboard/users">&larr; Return to Users</a> 
	    </div>
    </div>
    <div class="row mt-1">
	    
	    <div class="col-md-4 offset-md-4">
		    <div class="card">
			<form  method="post">
				<img class="card-img-top" src="{{$moduser->social_account->first()->metadata->get('avatar_original')}}" alt="">
				<div class="card-block">
					<h3> {{$moduser->name}}</h3>
					@foreach($moduser->social_account as $account)
					Login: <span class="tag tag-default">{{studly_case($account->provider)}}</span>
					@endforeach
					<fieldset class="form-group">
						<label class="form-control-label" for="name">Name</label>
						<input class="form-control" type="text" name="name" id="name" value="{{$moduser->name}}">
					</fieldset>
	
					<fieldset class="form-group">
						<label class="form-control-label" for="email">Email</label>
						<input class="form-control" type="email" name="email" id="email" value="{{$moduser->email}}">
					</fieldset>
	
					<select class="form-control mb-1" id="author_selector" oninput="$(this).after($('#newgrouptemplate').html()).next().val($(this).val()).wrap('<div class=\'input_token selected_author h3 mb-1\'><span class=\'tag tag-primary\'><a class=\'close\'>&times;</a><span class=\'input_token_text\'>'+$(this).find('option:selected').text()+'</span></span></div>');$(this).val('none')">
						<option value="none" selected>-- Choose Groups --</option>
					@foreach(config('groups') as $groupkey => $group)
						<option value="{{$groupkey}}">{{$group['name']}}</option>
					@endforeach
					</select>

					@foreach($moduser->groups as $group)
					<div class="input_token selected_author h3 mb-1"><span class="tag tag-primary"><a class="close">&times;<input type="hidden" name="groups[]" value="{{$group->group}}"></a><span class="input_token_text">{{$group->name}}</span></span></div>						
					
{{--
					
					<span class="tag tag-default">{{studly_case($group->group)}}</span>
--}}
					@endforeach
					<fieldset class="form-group text-right">
						{!! csrf_field() !!}
						<button type="submit" class="btn btn-success button-lg">Save</button>
					</fieldset>
					
				</div>
			</form>	
			<form method="post">
				<fieldset class="form-group">
					{!! csrf_field() !!}
					{!! method_field('DELETE') !!}
					<button type="button" class="btn btn-danger btn-sm" data-toggle="collapse" data-target="#delete_user_final"><i class="fa fa-trash"></i></button>
					<button type="submit" class="btn btn-danger btn-sm collapse" id="delete_user_final"><i class="fa fa-trash"></i> Confirm!!</button>
				</fieldset>
			</form>
		    </div>
	    </div>
    </div>
    
    
</div>


@endsection