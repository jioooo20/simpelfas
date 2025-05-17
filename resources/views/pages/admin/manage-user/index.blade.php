@extends('layouts.main')
@section('judul', 'Kelola Pengguna')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Pengelolaan Pengguna</h1>
            <div class="flex gap-3">
                <button class="bg-green-500 text-white btn btn-outline btn-sm flex items-center gap-2">
                    <i class="fas fa-file-excel"></i>Impor Data Pengguna
                </button>
                <button class="btn btn-primary text-white btn-sm flex items-center gap-2" onclick="modal_add_user.showModal()">
                    <i class="fas fa-user-plus"></i>Tambah Pengguna
                </button>

            </div>
        </div>

        {{-- kotak --}}
        <div class="bg-base-100 shadow-md border rounded-xl p-6">
            {{-- table --}}
            <div class="overflow-x-auto">
                <livewire:user-table />

        </div>
    </div>
    @include('pages.admin.manage-user.add')
@endsection




        <!-- Stats boxes -->
        {{-- <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-base-100 shadow-sm rounded-lg p-4 border">
                <div class="flex items-center gap-3">
                    <i class="bi bi-people text-xl"></i>
                    <div>Total admins</div>
                </div>
                <div class="text-3xl font-bold mt-2">841</div>
            </div>
            <div class="bg-base-100 shadow-sm rounded-lg p-4 border">
                <div class="flex items-center gap-3">
                    <i class="bi bi-building text-xl"></i>
                    <div>Organizations</div>
                </div>
                <div class="text-3xl font-bold mt-2">23</div>
            </div>
            <div class="bg-base-100 shadow-sm rounded-lg p-4 border">
                <div class="flex items-center gap-3">
                    <i class="bi bi-circle-fill text-emerald-600 text-sm"></i>
                    <div>Active</div>
                </div>
                <div class="text-3xl font-bold mt-2 text-emerald-600">782</div>
            </div>
            <div class="bg-base-100 shadow-sm rounded-lg p-4 border">
                <div class="flex items-center gap-3">
                    <i class="bi bi-circle-fill text-amber-500 text-sm"></i>
                    <div>Inactive</div>
                </div>
                <div class="text-3xl font-bold mt-2 text-amber-500">64</div>
            </div>
        </div> --}}
