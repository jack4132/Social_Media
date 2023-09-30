@extends('layouts.app')


@section('content')
<div class="container">
	<!-- Show -->
	
	<div class="row">
		<div class="col-8">
 			<img src="/storage/{{ $post->image }}" alt="" class="w-100"/>
		</div>
		<div class="col-4">
			<div>
				<div class="d-flex align-items-center">
					<div class="pe-3">
						<img src="{{$post->user->profile->profileImage()}}" alt="" class="w-100 rounded-circle" style="max-width:50px"/>
					</div>
					<div>
						<div class="fw-bold">
							<a href="/profile/{{$post->user->id}}" style="text-decoration:none;"><span class="text-dark" >{{$post->user->username}}</span></a>
							<a href="#" style="text-decoration:none;" class="ps-3">Follow</a>
					</div>
					</div>
				</div>
				<hr>
				<p><span class="fw-bold"><a href="/profile/{{$post->user->id}}" style="text-decoration:none;"><span class="text-dark" >{{$post->user->username}}</span></a></span> {{$post->caption}}</p>
			</div>
		</div>
	</div>
</div>

@endsection
