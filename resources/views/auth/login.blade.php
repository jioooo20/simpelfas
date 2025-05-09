<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->setLocale('id')) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk</title>
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/x-icon"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(120deg, #e0f2fe 0%, #f0e7fd 100%);
        }

        .input-icon {
            transition: color 0.2s ease;
        }

        .form-input {
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
            background-color: #f9fafb;
            padding-left: 2.75rem;
            padding-right: 0.75rem;
            padding-top: 0.6rem;
            padding-bottom: 0.6rem;
            line-height: 1.5;
        }

        .form-input:focus {
            border-color: #6366f1;
            background-color: #fff;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
            outline: none;
        }

        .form-input:focus+.input-icon {
            color: #6366f1;
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .btn-primary {
            background-color: #4f46e5;
            color: white;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #4338ca;
        }

        .btn-secondary {
            background-color: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #e5e7eb;
        }

        .btn-loading::after {
            content: '';
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid currentColor;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.6s linear infinite;
            margin-left: 8px;
            vertical-align: middle;
        }

        .btn-primary.btn-loading::after {
            border: 2px solid #fff;
            border-top-color: transparent;
        }


        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .error-message {
            display: none;
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .input-error {
            border-color: #ef4444 !important;
            /* Red border */
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f3f4f6;
            color: #6b7280;
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
        }

        .social-icon:hover {
            background-color: #e5e7eb;
            color: #374151;
        }
    </style>
</head>

<body>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div
            class="bg-white rounded-2xl shadow-xl overflow-hidden  bg-gradient-to-br from-blue-50 to-indigo-200 flex flex-col lg:flex-row max-w-5xl w-full my-8">

            <div class="hidden lg:flex lg:w-[55%] items-center justify-center p-12 relative flex-col">
                <div class="absolute top-8 left-8 flex items-center space-x-2">
                    {{-- <svg class="h-8 w-auto text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg> --}}
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="h-8 w-auto">
                    <span class="font-semibold text-xl text-indigo-700">Simpelfas</span>
                </div>
                <img src="{{ asset('landing.png') }}" alt="Welcome illustration"
                    class="max-w-md w-full h-auto object-contain">
            </div>

            <div class="w-full lg:w-[45%] flex flex-col justify-center py-12 px-8 sm:px-12">
                <div class="w-full max-w-md mx-auto">
                    <h2 class="text-3xl font-semibold text-gray-800 mb-2 text-center lg:text-left">
                        Selamat Datang
                    </h2>
                    <p class="text-sm text-gray-500 mb-8 text-center lg:text-left">
                        Ayo, masuk sekarang! Laporkan kerusakan fasilitas dengan mudah dan bantu kami menjaganya tetap
                        dalam kondisi terbaik.
                    </p>

                    <form class="space-y-5" action="{{ route('postlogin') }}" method="POST" id="loginForm" novalidate>
                        @csrf

                        <div>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 input-icon pointer-events-none">
                                    <i class="fas fa-user fa-sm"></i>
                                </span>
                                <input id="identitas" name="identitas" type="text" autocomplete="identitas" required
                                    placeholder="Identitas NIM / NIP"
                                    class="form-input block w-full rounded-lg shadow-sm sm:text-sm placeholder-gray-400"
                                    aria-describedby="identitas-error" value="{{ old('identitas') }}">
                            </div>
                            <p id="identitas-error" class="error-message">Identitas minimal 5 karakter!</p>
                        </div>

                        <div>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 input-icon pointer-events-none">
                                    <i class="fas fa-lock fa-sm"></i>
                                </span>
                                <input id="password" name="password" type="password" autocomplete="current-password"
                                    required placeholder="Password"
                                    class="form-input block w-full rounded-lg shadow-sm sm:text-sm pr-10 placeholder-gray-400"
                                    aria-describedby="password-error">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <button type="button" id="togglePassword"
                                        class="text-gray-400 hover:text-gray-600 focus:outline-none focus:text-indigo-500 rounded"
                                        aria-label="Toggle password visibility">
                                        <i class="fas fa-eye fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                            <p id="password-error" class="error-message">Password minimal 5 karakter!</p>
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <button type="submit" id="submitButton"
                                class="flex-1 flex items-center justify-center w-full rounded-lg px-4 py-2.5 text-sm font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition disabled:opacity-60 disabled:cursor-not-allowed btn-primary">
                                Masuk
                            </button>
                        </div>

                        <div class="text-center mt-6">
                            <p class="text-sm text-gray-600">
                                Lupa password? <a href="#"
                                    class="text-indigo-600 hover:text-indigo-800 font-medium transition">Hubungi
                                    admin</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if (session('login_error'))
                Toastify({
                    text: "{{ session('login_error') }}",
                    duration: 4000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #f87171, #ef4444)",
                        borderRadius: "8px",
                        boxShadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)"
                    },
                    stopOnFocus: true,
                }).showToast();
            @endif

            const form = document.querySelector("#loginForm");
            const identitasInput = document.querySelector("#identitas");
            const passwordInput = document.querySelector("#password");
            const submitButton = document.querySelector("#submitButton");
            const togglePassword = document.querySelector("#togglePassword");

            function validateInput(input, errorElement, minLength) {
                const errorMsg = errorElement;
                if (input.value.length > 0 && input.value.length < minLength) {
                    input.classList.add("input-error");
                    errorMsg.style.display = "block";
                    errorMsg.textContent =
                        `${input.name.charAt(0).toUpperCase() + input.name.slice(1)} minimal ${minLength} karakter!`;
                    return false;
                } else {
                    input.classList.remove("input-error");
                    errorMsg.style.display = "none";
                    return true;
                }
            }

            identitasInput.addEventListener("input", function() {
                validateInput(this, document.querySelector("#identitas-error"), 10);
            });

            passwordInput.addEventListener("input", function() {
                validateInput(this, document.querySelector("#password-error"), 5);
            });

            form.addEventListener("submit", function(event) {
                event.preventDefault();

                const isIdentitasValid = validateInput(identitasInput, document.querySelector(
                    "#identitas-error"), 5);
                const isPasswordValid = validateInput(passwordInput, document.querySelector(
                    "#password-error"), 5);

                let isFilled = true;
                if (!identitasInput.value) {
                    identitasInput.classList.add("input-error");
                    document.querySelector("#identitas-error").textContent = "Identitas wajib diisi!";
                    document.querySelector("#identitas-error").style.display = "block";
                    isFilled = false;
                }
                if (!passwordInput.value) {
                    passwordInput.classList.add("input-error");
                    document.querySelector("#password-error").textContent = "Password wajib diisi!";
                    document.querySelector("#password-error").style.display = "block";
                    isFilled = false;
                }

                if (isIdentitasValid && isPasswordValid && isFilled) {
                    submitButton.disabled = true;
                    if (submitButton.classList.contains('btn-primary')) {
                        submitButton.classList.add("btn-loading");
                    } else {
                        submitButton.classList.add("btn-loading");
                    }
                    submitButton.innerHTML = 'Logging in... <span class="btn-loading-spinner"></span>';

                    if (window.getComputedStyle(submitButton, '::after').getPropertyValue('content') !==
                        'none') {
                        submitButton.innerHTML = 'Logging in...';
                    }

                    form.submit();
                } else {

                    if (!isFilled && !identitasInput.value) {
                        identitasInput.focus();
                    } else if (!isIdentitasValid) {
                        identitasInput.focus();
                    } else if (!isFilled && !passwordInput.value) {
                        passwordInput.focus();
                    } else if (!isPasswordValid) {
                        passwordInput.focus();
                    }
                }
            });

            togglePassword.addEventListener("click", function() {
                const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
                passwordInput.setAttribute("type", type);
                const icon = this.querySelector("i");
                icon.classList.toggle("fa-eye");
                icon.classList.toggle("fa-eye-slash");
            });
        });
    </script>
</body>

</html>
