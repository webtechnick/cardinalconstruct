@extends('layouts.app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-9">
                    <h2>Edit Review</h2>
                </div>
                <div class="col-md-3">

                </div>
            </div>
        </div>
        <div class="panel-body">
            {{ Form::model($review, ['route' => ['admin.reviews.update', $review], 'method' => 'PATCH']) }}

                <div class="form-group">
                    <label for="name">Name: </label>
                    {{ Form::text('name', null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group">
                    <label for="rating">Rating: </label>
                    {{ Form::select('rating', \App\Review::$ratings, null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group">
                    <label for="body">Review: </label>
                    {{ Form::textarea('body', null, ['class' => 'form-control', 'rows' => '5']) }}
                </div>

                <div class="form-group">
                    <label for="response">Response: </label>
                    {{ Form::textarea('response', null, ['class' => 'form-control', 'rows' => '5']) }}
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save Review</button>
                </div>

            {{ Form::close() }}
        </div>
    </div>

@endsection