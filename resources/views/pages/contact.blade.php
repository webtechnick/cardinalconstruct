@extends('layouts.app')

@section('content')

	<h1>Contact Us</h1>

	<hr>

    @include('utils._errors')

    <form action="/contact" method="POST">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Name: </label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="{{ old('name') }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">Email: </label>
                    <input type="text" class="form-control" name="email" id="email" placeholder="Email" value="{{ old('email') }}" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="phone">Phone: </label>
                    <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone" value="{{ old('phone') }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="type">Inquery Type: </label>
                    <select name="type" id="type" class="form-control" required>
                        <option selected value="">Select One</option>
                        <option value="general">General Question</option>
                        <option value="quote">Quote Request</option>
                        <option value="billing">Billing Question</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="body">Information: </label>
                    <textarea class="form-control" name="body" id="body" rows="10" required>{{ old('body') }}</textarea>
                </div>
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg">Submit</button>
        </div>
    </form>

@stop