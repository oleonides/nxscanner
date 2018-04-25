<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>WebClientPrint for PHP</title>   

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    
    <div class="container mt-4">
        @yield('body')
    </div>
 
    {{-- <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script> --}}

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
     
    @yield('scripts')
</body>
</html>
