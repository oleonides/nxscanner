<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>WebClientPrint for PHP</title>   

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    {{--  font-awesome  --}}
    <link rel="stylesheet" href="{{ asset('vendor/font-awesome/fontawesome.min.css') }}">
</head>
<body>
    
    <div class="container mt-4 mb-4">
        @yield('body')
    </div>
 
    {{-- <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script> --}}

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <script src="{{ asset('vendor/sweetalert/sweetalert2.all.js') }}"></script>

    {{--  font-awesome  --}}
    <script src="{{ asset('vendor/font-awesome/fontawesome.min.js') }}"></script>
     
    @yield('scripts')

    
</body>
</html>
