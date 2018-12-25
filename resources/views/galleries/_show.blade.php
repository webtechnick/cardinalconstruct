<div class="row">
	<div class="col-md-4">
		<h2>
			@include('galleries._admindropdown')
			<a href="{{ $gallery->url() }}">{{ $gallery->title }}</a>
		</h2>
		<hr>
		<p>{!! $gallery->body !!}</p>
	</div>
	<div class="col-md-8">
		@foreach ($gallery->photos->chunk(3) as $set)
			<div class="row">
				@foreach ($set as $photo)
					<div class="col-md-4 mb20 photo-bucket">

						{{-- <div class="thumbnail"> --}}
							@include('photos._admindropdown')

							<img class="{{$photo->disabled()}} hand img-rounded"
								 src="/{{ $photo->thumbnail_path }}"
								 alt=""
								 data-jslghtbx-caption="{!!$photo->caption!!}"
								 data-jslghtbx="/{{ $photo->path }}"
								 data-jslghtbx-group="gallery{{ $gallery->id }}">

							{{-- <div class="caption">{!! $photo->caption !!}</div> --}}

						{{-- </div> --}}
					</div>
				@endforeach
			</div>
		@endforeach

		@if ($signedIn && $user->ownsOrIsAdminOrWorker($gallery))
			<form id="addPhotosForm{{ $gallery->id }}" action="{{ route('photos.store', [$gallery->slug]) }}" method="POST" class="bluedashed dropzone">
				{{ csrf_field() }}
			</form>

			@section('javascript')
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
			@endsection
		@endif
	</div>
</div>
<hr>