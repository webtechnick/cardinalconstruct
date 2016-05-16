@extends('layouts.app')

@section('content')
	<h1>Gallery</h1>

	<hr>


	@foreach( $galleries as $gallery)
	<div class="row">
		<div class="col-md-12">
			<a href="{{ $gallery->url() }}">{{ $gallery->title }}</a>
		</div>
	</div>
	@endforeach

@stop