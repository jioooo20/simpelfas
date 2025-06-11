@extends('layouts.main')
@section('judul', 'Profil')
@section('content')
<div class="container mx-auto px-4 py-4">
    <div class="bg-base-100 shadow-md border rounded-xl mb-3">
        <div class="flex border-b">
            <div class="px-6 py-4 font-semibold border-b-2 border-primary text-primary bg-gray-100 rounded-t-lg flex items-center gap-2">
                <i class="bi bi-person-circle"></i>
                <span>Edit Profil</span>
            </div>
        </div>
        <div class="p-6">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="flex flex-col items-center mb-6">
                    @if($user->profile_image)
                        <img id="avatarPreview" src="{{ asset('storage/' . $user->profile_image) }}" class="h-24 w-24 rounded-full object-cover border mb-3" alt="Foto Profil">
                    @else
                        <img id="avatarPreview" src="https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&background=4338ca&color=fff" class="h-24 w-24 rounded-full object-cover border mb-3" alt="{{ $user->nama }}">
                    @endif

                    <input
                        type="file"
                        name="profile_image"
                        accept="image/*"
                        class="mt-2 text-sm file:bg-gray-200 file:hover:bg-gray-400 file:border file:border-gray-300 file:text-gray-700 file:rounded file:px-6 file:py-2 file:cursor-pointer"
                    >
                    @error('profile_image')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Identitas (readonly) --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1" for="identitas">Identitas</label>
                        <input type="text" id="identitas" value="{{ $user->identitas }}" readonly
                            class="w-full px-4 py-2 border rounded bg-gray-100 text-gray-500 cursor-not-allowed">
                    </div>
                    {{-- Email (readonly) --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1" for="email">Email</label>
                        <input type="email" id="email" value="{{ $user->email }}" readonly
                            class="w-full px-4 py-2 border rounded bg-gray-100 text-gray-500 cursor-not-allowed">
                    </div>
                    {{-- Role (readonly) --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1" for="role">Role</label>
                        <input type="text" id="role" value="{{ $user->role->role_nama ?? '-' }}" readonly
                            class="w-full px-4 py-2 border rounded bg-gray-100 text-gray-500 cursor-not-allowed">
                    </div>
                    {{-- Nama (editable) --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1" for="nama">Nama</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $user->nama) }}" required
                            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-primary">
                        @error('nama')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- Password Lama --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold mb-1" for="current_password">Password Lama</label>
                        <input type="password" name="current_password" id="current_password"
                            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-primary"
                            autocomplete="current-password">
                        @error('current_password')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- Password Baru --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold mb-1" for="password">Password Baru</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-primary"
                            autocomplete="new-password">
                        <span class="text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password</span>
                        @error('password')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col md:flex-row justify-end gap-2 mt-8">
                    <a href="{{ route('profile') }}"
                        class="px-6 py-2 rounded bg-white border border-gray-300 hover:bg-gray-100 text-gray-800 font-semibold transition text-center">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-8 py-2 rounded bg-primary text-white font-bold hover:bg-primary-focus transition text-center">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('skrip')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.querySelector('input[name="profile_image"]');
        const preview = document.getElementById('avatarPreview');

        input.addEventListener('change', function () {
            const file = this.files[0];
            if (file && preview) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush