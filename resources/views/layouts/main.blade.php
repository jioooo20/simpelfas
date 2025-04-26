<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->setLocale('id')) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simpelfas | @yield('judul')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Montserrat:wght@500;700&display=swap" rel="stylesheet">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        .judul {
            font-family: "Cal Sans", sans-serif;
            font-weight: "extrabold";
        }

        body {
            font-family: 'Open Sans', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-200">
    @include('layouts.sidebar')
    @include('layouts.header')

    <div class="ml-64 p-3 transition-all duration-300" id="main-content">
        @yield('content')
    </div>

    @stack('skrip')

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const texts = document.querySelectorAll('.sidebar-text');
            const title = document.getElementById('sidebar-title');
            const mainContent = document.getElementById('main-content');
            const header = document.getElementById('header');

            sidebar.classList.toggle('w-64');
            sidebar.classList.toggle('w-20');

            mainContent.classList.toggle('ml-64');
            mainContent.classList.toggle('ml-20');

            header.classList.toggle('ml-64');
            header.classList.toggle('ml-20');

            texts.forEach(text => {
                text.classList.toggle('hidden');
            });
            title.classList.toggle('hidden');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>

</html>
