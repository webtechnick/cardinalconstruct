@extends('layouts/app')

@section('content')

    <img src="/images/logo_fb.jpg" class="img-rounded img-responsive">

    <div class="row">

        <div class="col-md-4 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading text-right">
                    Contact Us
                </div>
                <div class="panel-body text-right">
                    <p>Phone: {{ config('app.phone') }}</p>

                    <p>Email: <a href="mailto:{{ config('app.email') }}">{{ config('app.email') }}</a></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Connect With Us
                </div>
                <div class="panel-body text-left">
                    <p>Facebook: <a href="https://www.facebook.com/cardinalconstructioninc/">Cardinal Construction Inc</a></p>
                    <p>Yelp: <a href="https://www.yelp.com/biz/cardinal-construction-albuquerque">Cardinal Construction Inc</a></p>
                </div>
            </div>
        </div>
    </div>
@stop