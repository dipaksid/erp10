<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ERP10') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!-- OLD Project CSS -->
    <link href="{{ asset('css/resp_comman.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    @yield('style')
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

</head>
<body id="page-top">
    <div id="app">
        @guest
            @include('partials/guest-header')
            @yield('content')
        @else
            <div id="wrapper">
                @include('layouts/nav')
                @include('partials/admin-header')
                <main class="py-4">
                    @yield('content')
                </main>
            </div>
        @endguest
    </div>
    <!--- scripts ----->
    <script src="{{ asset('js/admin.min.js') }}"></script>
    <script src="{{ asset('js/pages/layout-public.js') }}"></script>
    @can('OUTSTANDING JOB NOTIFICATION')
        check_outstanding_noti();
    @endcan
    @yield('script')
</body>
</html>
