@extends('layouts.main')

@section('judul', 'Masuk')

@section('content')
<div class="flex min-h-screen flex-col justify-center bg-indigo-50 py-12 px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <h1 class="text-center text-4xl font-extrabold text-indigo-700 mb-4">Simpelfas</h1>
      <h2 class="mt-2 text-center text-lg font-medium text-gray-700">
       Silakan masuk ke akun anda
      </h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-6 shadow-md rounded-lg sm:px-10">
        <form class="space-y-6" action="#" method="POST">
          <div>
            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
            <div class="mt-1">
              <input id="username" name="username" type="username" autocomplete="username" required
                placeholder="Masukkan username"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2 placeholder-gray-400 transition">
            </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <div class="mt-1 relative">
              <input id="password" name="password" type="password" autocomplete="current-password" required
                placeholder="Masukkan password"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2 placeholder-gray-400 transition">
              <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                <a href="#" class="text-xs text-indigo-600 hover:text-indigo-500 transition">Lupa Password?</a>
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
            <a href="{{ url('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition">
              Daftar di sini
            </a>
          </p>
      </div>
    </div>
  </div>


@endsection('content')
