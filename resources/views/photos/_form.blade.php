{{ csrf_field() }}

<div class="form-group">
	<label for="body">Caption: </label>
    {{ Form::textarea('caption', null, ['id' => 'Caption', 'class' => 'form-control', 'rows' => 10]) }}
</div>
<div class="checkbox">
    <label>
        {{ Form::hidden('is_active',0) }}
        {{ Form::checkbox('is_active') }} Approved
    </label>
</div>

<div class="form-group">
	<button type="submit" class="btn btn-primary">Submit</button>
</div>

@section('javascript')
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
        CKEDITOR.replace( 'Caption' );
    </script>
@endsection