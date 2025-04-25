<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simpelfas | @yield('judul')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- daisy ui via cdn --}}
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Mont.serif:wght@500;700&display=swap" rel="stylesheet">
    <style>
    a.judul {
        font-family: "Cal Sans", sans-serif;
        font-weight: 400;
    }
    body {
        font-family: 'Open Sans', sans-serif;
    }
    h1, h2, h3, h4, h5, h6 {
        font-family: 'Montserrat', sans-serif;
    }
</style>

</head>

<body class="bg-white">
    @include('layouts.header')
    @include('layouts.sidebar')
    @yield('content')

    @stack('skrip')
</body>

</html>
