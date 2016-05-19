@extends('layouts.app')

@section('content')

	@if ($signedIn && $user->isAdmin())
		<a href="/gallery/create" class="pull-right btn btn-primary p10 mt10">Create Gallery</a>
	@endif

	@foreach ($galleries as $gallery)
		@include('galleries._show')
	@endforeach

@stop