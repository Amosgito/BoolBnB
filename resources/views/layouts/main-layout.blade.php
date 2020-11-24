<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>BoolBnB</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    {{-- header script --}}
    <script src="{{ asset('/js/partials/header.js')}}"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('import')
    

    <title>BoolBnB</title>
</head>

<body>

    <div class="app" id="app">

        @include('partials.header')
        @yield('content')
        @include('partials.footer')

    </div>

     
</body>
</html>
