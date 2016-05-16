@if (session()->has('flash_message'))
	@section('javascript')
		<script type="text/javascript">
			swal({
				title: "{{ session('flash_message.title') }}",
				text: "{{ session('flash_message.message') }}",
				type: "{{ session('flash_message.type') }}",
				showConfirmButton: false,
				timer: 1700,
				allowOutsideClick: true
				//confirmButtonText: "Cool"
			});
		</script>
	@stop
@endif

@if (session()->has('flash_message_overlay'))
	@section('javascript')
		<script type="text/javascript">
			swal({
				title: "{{ session('flash_message_overlay.title') }}",
				text: "{{ session('flash_message_overlay.message') }}",
				type: "{{ session('flash_message_overlay.type') }}",
				confirmButtonText: "Okay!"
			});
		</script>
	@stop
@endif