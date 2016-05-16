{{ csrf_field() }}
<div class="form-group">
	<label for="title">Title: </label>
	<input type="text" name="title" class="form-control" id="title" placeholder="Title" value="{{ old('title') }}">
</div>

<div class="form-group">
	<label for="body">Description: </label>
	<textarea class="form-control" name="body" id="body" rows="10">{{ old('body') }}</textarea>
</div>

<!-- <div class="form-group">
	<label for="file">Photos: </label>
	<input type="file" class="form-control" id="file" value="{{ old('file') }}">
</div> -->

<div class="form-group">
	<button type="submit" class="btn btn-primary">Submit</button>
</div>