<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cardinal Construction</title>

    @include('layouts._styles')
    @yield('style')

</head>
<body id="app-layout">

    @include('layouts._nav')

    @include('layouts._flash')

    <div class="container">
        @yield('content')
    </div>


    @include('layouts._scripts')
    @yield('javascript')
</body>
</html>
