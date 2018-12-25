@extends('layouts/app')

@section('content')
	<h1>Edit Photo</h1>

    <img src="/{{ $photo->thumbnail_path}}" class="img-rounded">

	<hr>
	@include('utils._errors')

    {{ Form::model($photo, ['route' => ['photos.update', $photo->id], 'method' => 'patch']) }}
	   @include('photos._form')
	{{ Form::close() }}
@stop