@extends('layouts.app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-9">
                    <h2>Edit User</h2>
                </div>
                <div class="col-md-3">

                </div>
            </div>
        </div>
        <div class="panel-body">
            {{ Form::model($user, ['route' => ['admin.users.update', $user], 'method' => 'PATCH']) }}

                <div class="form-group">
                    <label for="name">Name: </label>
                    {{ Form::text('name', null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group">
                    <label for="role">Role: </label>
                    {{ Form::select('role', \App\User::$roles, null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group">
                    <label for="body">Email: </label>
                    {{ Form::text('email', null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save User</button>
                </div>

            {{ Form::close() }}
        </div>
    </div>

@endsection