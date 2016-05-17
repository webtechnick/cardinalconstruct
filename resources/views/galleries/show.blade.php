@extends('layouts/app')

@section('content')

	<h1>{{ $gallery->title }}</h1>

	<hr>

	<div class="row">
		<div class="col-md-3">
			<p>{!! nl2br($gallery->body) !!}</p>
		</div>
		<div class="col-md-9">
			@foreach ($gallery->photos->chunk(4) as $set)
				<div class="row">
					@foreach ($set as $photo)
						<div class="col-md-3 mb20">
							<a href="/{{ $photo->path }}"><img src="/{{ $photo->thumbnail_path }}" alt=""></a>
						</div>
					@endforeach
				</div>
			@endforeach

			@if ($user && $user->owns($gallery))
				<hr>
				<form id="addPhotosForm" action="{{ route('store_photo_path', [$gallery->slug]) }}" method="POST" class="bluedashed dropzone">
					{{ csrf_field() }}
				</form>
			@endif
		</div>
	</div>

@stop

@section('javascript')
	<script>
		Dropzone.options.addPhotosForm = {
			paramName: 'photo',
			maxFileSize: 3,
			acceptedFiles: '.jpg, .jpeg, .png, .gif'
		}
	</script>
@stop