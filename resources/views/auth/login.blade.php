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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Montserrat:wght@500;700&display=swap"
        rel="stylesheet">
    <style>
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

<body>
    <div class="flex min-h-screen flex-col justify-center bg-indigo-50 py-12 px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h1 class="text-center text-4xl font-extrabold text-indigo-700 mb-4">Simpelfas</h1>
            <h2 class="mt-2 text-center text-lg font-medium text-gray-700">
                Silakan masuk ke akun anda
            </h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-6 shadow-md rounded-lg sm:px-10">
                <form class="space-y-6" action="{{ route('postlogin') }}" method="POST" id="loginForm">
                    @csrf
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <div class="mt-1">
                            <input id="username" name="username" type="text" autocomplete="username" required
                                placeholder="Masukkan username"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2 placeholder-gray-400 transition">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1 relative">
                            <input id="password" name="password" type="password" autocomplete="current-password"
                                required placeholder="Masukkan password"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2 placeholder-gray-400 transition">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <a href="#" class="text-xs text-indigo-600 hover:text-indigo-500 transition">Lupa
                                    Password?</a>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            Masuk
                        </button>
                    </div>
                </form>

                <p class="mt-6 text-center text-sm text-gray-600">
                    Belum punya akun?
                    <a href="{{ url('register') }}"
                        class="font-medium text-indigo-600 hover:text-indigo-500 transition">
                        Daftar di sini
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.querySelector("form").addEventListener("submit", function(event) {
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;

            if (username.length < 5) {
                event.preventDefault();
                showUsernameAlert();
                return;
            }

            if (password.length < 5) {
                event.preventDefault();
                showPasswordAlert();
                return;
            }
        });
    </script>
    <script>
        function showUsernameAlert() {
            Toastify({
                text: "Username minimal 5 karakter!",
                duration: 3000, // 3 detik
                close: true,
                gravity: "top", // top or bottom
                position: "right", // left, center, or right
                backgroundColor: "#f56565", // warna background
                stopOnFocus: true,
            }).showToast();
        }

        function showPasswordAlert() {
            Toastify({
                text: "Password minimal 5 karakter!",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#4299e1",
                stopOnFocus: true,
            }).showToast();
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>

</html>
