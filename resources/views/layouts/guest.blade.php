<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<title>Bind Together</title>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Bind Together') }}</title>

    <!-- Styles -->
    @vite('resources/sass/app.scss')
</head>

<body>
    <main>
        @include('layouts.guest-topbar')
        @include('sweetalert::alert')
        <section class="mt-lg-5 bg-soft d-flex align-items-center">
            @yield('content')
        </section>
    </main>
</body>

</html>
