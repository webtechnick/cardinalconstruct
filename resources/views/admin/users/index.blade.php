@extends('layouts.app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-9">
                    <h2>Users</h2>
                </div>
                <div class="col-md-3">

                </div>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-striped">
                <tr>
                    <th class="text-center">Role</th>
                    <th class="text-left">Name</th>
                    <th class="hidden-sm hidden-xs text-left">Email</th>
                    <th class="text-right">Actions</th>
                </tr>
            @foreach($users as $user)
                <tr>
                    <td class="text-center">{{ $user->role }}</td>
                    <td class="text-left">{{ $user->name }}</td>
                    <td class="hidden-sm hidden-xs text-left">{{ $user->email }}</td>
                    <td class="col-md-2 text-right">
                        <div class="btn-group">
                            <a href="/admin/users/edit/{{ $user->id }}" class="btn btn-info"> Edit</a>
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="/admin/users/delete/{{ $user->id }}" class="confirm" confirm-message="Are you sure you want to delete this users?">Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
            </table>
        </div>
    </div>

    <div class="text-center">{{ $users->links() }}</div>
@endsection