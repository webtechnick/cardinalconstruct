@extends('layouts/app')

@section('content')

	<h1>{{ $gallery->title }}</h1>

	<hr>

	<p>{!! nl2br($gallery->body) !!}</p>

	@foreach ($gallery->photos as $photo)
		<img src="/{{ $photo->path }}" alt="">
	@endforeach

	<hr>

	<h2>Add Photos</h2>
	<form id="addPhotosForm" action="{{ $gallery->url() }}/photos" method="POST" class="dropzone">
		{{ csrf_field() }}
	</form>
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