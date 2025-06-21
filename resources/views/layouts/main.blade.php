<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->setLocale(locale: 'id')) }}" data-theme="winter">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('judul') | Simpelfas</title>
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/x-icon"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Montserrat:wght@500;700&display=swap"
        rel="stylesheet">
    <style>
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            /* overflow-y: scroll; */
        }

        body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Open Sans', sans-serif;
        }

        .judul {
            font-family: "Cal Sans", sans-serif;
            font-weight: 800;
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
    @stack('css')
</head>

<body class="bg-base-100">
<div class="flex h-screen overflow-hidden">
    @include('layouts.sidebar')

    <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden lg:ml-64">
        @include('layouts.header')

        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>

        <main>
            <div class="pt-2 px-2">
                @yield('content')
            </div>
        </main>

        @include('layouts.notification')
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const notifPanel = document.getElementById('notif-panel');
        const notifOverlay = document.getElementById('notif-overlay');
        const profileDropdown = document.getElementById('profileDropdown');
        const notifDropdown = document.getElementById('notifDropdown');

        function closeAllPopups() {
            sidebar?.classList.add('-translate-x-full');
            sidebarOverlay?.classList.add('hidden');
            notifPanel?.classList.add('translate-x-full');
            notifOverlay?.classList.add('hidden');
            profileDropdown?.classList.add('hidden');
            notifDropdown?.classList.add('hidden');
        }

        window.toggleSidebar = function () {
            const isHidden = sidebar.classList.contains('-translate-x-full');
            closeAllPopups(); // Tutup semua yang lain dulu
            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
            }
        };

        window.toggleNotifPanel = function () {
            const isHidden = notifPanel.classList.contains('translate-x-full');
            closeAllPopups();
            if (isHidden) {
                notifPanel.classList.remove('translate-x-full');
                notifOverlay.classList.remove('hidden');
            }
        };

        window.toggleProfileDropdown = function () {
            const isHidden = profileDropdown.classList.contains('hidden');
            closeAllPopups();
            if (isHidden) {
                profileDropdown.classList.remove('hidden');
            }
        };

        window.toggleNotifDropdown = function () {
            const isHidden = notifDropdown.classList.contains('hidden');
            closeAllPopups();
            if (isHidden) {
                notifDropdown.classList.remove('hidden');
            }
        };

        const hamburgerBtn = document.getElementById('hamburger-btn');
        if (hamburgerBtn) {
            hamburgerBtn.addEventListener('click', window.toggleSidebar);
        }

        function updateClock() {
            fetch('/realtime-clock')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('realtime-clock').innerText = data;
                });
        }

        updateClock();
        setInterval(updateClock, 1000);
    });
</script>

@stack('skrip')
@livewireScripts
</body>

</html>
