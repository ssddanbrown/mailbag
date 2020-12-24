<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }} @stack('titles')</title>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('libs/alpine/alpine.js') }}" defer></script>

        @stack('head')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            @if(session()->has('success'))
                <div class="container pt-8 -mb-4">
                    <x-notification-success>{{ session()->get('success') }}</x-notification-success>
                </div>
            @endif
            @if(session()->has('error'))
                <div class="container pt-8 -mb-4">
                    <x-notification-success>{{ session()->get('error') }}</x-notification-success>
                </div>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
