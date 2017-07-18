@extends('layouts.app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-9">
                    <h2>Reviews</h2>
                </div>
                <div class="col-md-3">

                </div>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-striped">
                <tr>
                    <th class="text-center">Rating</th>
                    <th class="text-left">Name</th>
                    <th class="hidden-sm hidden-xs text-left">Body</th>
                    <th class="text-right">Actions</th>
                </tr>
            @foreach($reviews as $review)
                <tr class="{{ $review->rowClass() }}">
                    <td class="text-center">{!! $review->stars() !!}</td>
                    <td class="text-left">{{ $review->name }}</td>
                    <td class="hidden-sm hidden-xs text-left">{{ $review->body }}</td>
                    <td class="col-md-2 text-right">
                        <div class="btn-group">
                            <a href="/admin/reviews/edit/{{ $review->id }}" class="btn btn-info"> Edit</a>
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="/admin/reviews/approve/{{ $review->id }}">Approve</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="/admin/reviews/delete/{{ $review->id }}" class="confirm" confirm-message="Are you sure you want to delete this item?">Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
            </table>
        </div>
    </div>

    <div class="text-center">{{ $reviews->links() }}</div>
@endsection