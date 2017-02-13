<div class="media py-2"  style="border:1px solid #9c9a9a">
	<a href="/dashboard/users/{{$user->id}}">
	<img class="d-flex mr-3 align-self-center" src="{{$user->avatar}}" alt="{{$user->name}}">
	</a>
	<div class="media-body">
		<a href="/dashboard/users/{{$user->id}}" style="color:black">
		<h5 class="mt-0">{{$user->name}}</h5>
		<h6 class="">{{$user->username}}</h6>
		<h6 class="">{{$user->email}}</h6>
		</a>
		@foreach($user->groups as $group)
		<span class="tag tag-default">{{studly_case($group->group)}}</span>
		@endforeach
	</div>
</div>