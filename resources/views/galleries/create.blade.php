@extends('layouts/app')

@section('content')
	<h1>Create your gallery</h1>

	<hr>

	@include('utils._errors')

	<form action="/gallery" method="POST" class="form-horizontal" enctype="multipart/form-data">
		@include('galleries._form')
	</form>
@stop