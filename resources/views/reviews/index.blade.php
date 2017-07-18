@extends('layouts/app')

@section('content')

    <a href="/reviews/create" class="pull-right btn btn-primary p10 mt10" data-toggle="modal" data-target="#newReview">Submit New Review</a>

    <!-- New Review -->
    <div class="modal fade" id="newReview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        {{ Form::open(['route' => 'reviews.store']) }}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">New Review</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name: </label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="rating">Rating: </label>
                        {{ Form::select('rating', \App\Review::$ratings, 5, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label for="body">Review: </label>
                        <textarea class="form-control" name="body" id="body" rows="10" required>{{ old('body') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Submit Review</button>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>

    <h1>Reviews</h1>

    <p>{{ $averageRating }} Rating</p>

    <hr>

    @foreach($reviews as $review)
        <div class="panel {{ $review->panelClass() }}">
            <div class="panel-heading">
                <div class="pull-right">
                    {{ $review->created_at->diffForHumans() }}
                </div>
                {!! $review->stars() !!}
            </div>
            <div class="panel-body">
                <blockquote>
                    <p>{{ $review->body }}</p>
                    <footer>{{ $review->name }} <cite title="Source Title">{{ $review->created_at->diffForHumans() }}</cite></footer>
                </blockquote>

                @if ($review->response)
                    <hr>
                    <blockquote class="blockquote-reverse">
                        <p>{{ $review->response }}</p>
                        <footer>{{ config('app.name') }}'s Offical Response</footer>
                    </blockquote>
                @endif
            </div>
            @if ($signedIn AND $user->isAdmin())
            <div class="panel-footer">
                <a href="#" class="btn btn-xs btn-info">Edit</a>
                <a href="#" class="btn btn-xs btn-danger">Delete</a>
                <a href="#" class="btn btn-xs btn-success">Approve</a>
            </div>
            @endif
        </div>
    @endforeach

    {{ $reviews->links() }}

@endsection