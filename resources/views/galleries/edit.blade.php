@extends('layouts/app')

@section('content')
	<h1>Edit: {{$gallery->title}}</h1>

	<hr>

	@include('utils._errors')

	<!-- <form action="/gallery/{{ $gallery->slug }}" method="POST" class="form-horizontal" enctype="multipart/form-data"> -->
    {{ Form::model($gallery, ['route' => ['gallery.update', $gallery->slug], 'method' => 'patch']) }}
	   @include('galleries._form')
	{{ Form::close() }}
@stop