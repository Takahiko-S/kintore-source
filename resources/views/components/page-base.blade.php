<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Bootstrap CSS -->
        <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{asset('jquery-ui-1.13.2/jquery-ui.min.css')}}" rel="stylesheet">
        <link href="{{asset('css/lightbox.min.css')}}" rel="stylesheet">
        <link href="{{asset('css/custom.css')}}" rel="stylesheet">
        
        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="{{asset('js/jquery-3.6.3.min.js')}}"></script>
        <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('jquery-ui-1.13.2/jquery-ui.min.js')}}"></script>
        <script src="{{asset('js/lightbox.min.js')}}"></script>
    </head>
    <body>
    @include('layouts.navigation_guest')
    
      {{ $main }}
      
      @include('layouts.footer')
     
      {{ $script }}
    </body>
</html>
