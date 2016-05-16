@extends('layouts/app')

@section('content')

	<h1>{{ $gallery->title }}</h1>

	<hr>

	<p>{!! nl2br($gallery->body) !!}</p>
@stop