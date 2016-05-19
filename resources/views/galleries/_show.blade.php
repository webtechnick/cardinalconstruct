<h1>
	<a href="{{ $gallery->url() }}">{{ $gallery->title }}</a>
	@include('galleries._admindropdown')
</h1>

<hr>

<div class="row">
	<div class="col-md-3">
		<p>{!! nl2br($gallery->body) !!}</p>
	</div>
	<div class="col-md-9">
		@foreach ($gallery->photos->chunk(4) as $set)
			<div class="row">
				@foreach ($set as $photo)
					<div class="col-md-3 mb20 photo-bucket">

						@include('photos._admindropdown')

						@if ($photo->isActive() || $signedIn && $user->isAdmin())
							<img class="{{$photo->disabled()}} hand img-rounded"
								 src="/{{ $photo->thumbnail_path }}"
								 alt=""
								 data-jslghtbx="/{{ $photo->path }}"
								 data-jslghtbx-group="gallery{{ $gallery->id }}">
						@endif
					</div>
				@endforeach
			</div>
		@endforeach

		@if ($signedIn && $user->ownsOrIsAdmin($gallery))
			<hr>
			<form id="addPhotosForm{{ $gallery->id }}" action="{{ route('store_photo_path', [$gallery->slug]) }}" method="POST" class="bluedashed dropzone">
				{{ csrf_field() }}
			</form>

			@section('javascript')
				@parent
				<script>
					Dropzone.options.addPhotosForm{{ $gallery->id }} = {
						init: function () {
							this.on("queuecomplete", function(file) {
								location.reload();
							});
						},
						paramName: 'photo',
						maxFileSize: 3,
						acceptedFiles: '.jpg, .jpeg, .png, .gif'
					}
				</script>
			@stop
		@endif
	</div>
</div>
<hr>