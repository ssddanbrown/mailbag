<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}{{ isset($title) ? " - {$title}" : '' }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            @if($title ?? false)
                <div class="container pt-8 pb-4 mt-8">
                    <h2 class="font-bold text-4xl text-gray-800 leading-tight">
                        {{ $title }}
                    </h2>
                </div>
            @endif

            <!-- Page Content -->
            <main class="py-3">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
