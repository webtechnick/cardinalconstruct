{{ csrf_field() }}
<div class="form-group">
	<label for="title">Title: </label>
	<!-- <input type="text" name="title" class="form-control" id="title" placeholder="Title" value="{{ old('title') }}"> -->
    {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Title']) }}
</div>

<div class="form-group">
	<label for="body">Description: </label>
    {{ Form::textarea('body', null, ['id' => 'Body', 'class' => 'form-control', 'rows' => 10]) }}
	<!-- <textarea class="form-control" name="body" id="body" rows="10">{{ old('body') }}</textarea> -->
</div>

<div class="form-group">
	<button type="submit" class="btn btn-primary">Submit</button>
</div>

@section('javascript')
    @parent
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
        CKEDITOR.replace( 'Body' );
    </script>
@stop