@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 py-4">
    <h1 class="text-3xl font-bold mb-8">Edit Profil</h1>
    <div class="bg-base-100 shadow-md border rounded-xl p-6">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Foto Profil --}}
            <div class="flex flex-col items-center mb-8">
                @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" class="h-24 w-24 rounded-full object-cover border mb-3" alt="Foto Profil">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&background=4338ca&color=fff" class="h-24 w-24 rounded-full object-cover border mb-3" alt="{{ $user->nama }}">
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
                    <input type="text" id="role" value="{{ $user->role->nama ?? $user->role_id }}" readonly
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
                {{-- Password (editable) --}}
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

            <div class="flex justify-end gap-2 mt-8">
                <a href="{{ route('profile') }}"
                    class="px-6 py-2 rounded bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-8 py-2 rounded bg-primary text-white font-bold hover:bg-primary-focus transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection