@extends('layouts.main')
@section('judul', 'Hak Akses')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="text-3xl font-bold text-base-content text-center md:text-left">Pengelolaan Hak Akses</h1>
            <button onclick="add_role_modal.showModal()"
                class="btn bg-primary text-white hover:bg-blue-600 border-primary hover:border-blue-600 btn-sm rounded-lg shadow-md hover:shadow-lg transition-all duration-300 inline-flex items-center gap-2 w-full md:w-auto">
                <i class="fas fa-fas fa-plus"></i>
                <span>Tambah Hak Akses</span>
            </button>
        </div>

        <div class="bg-base-100 shadow-md border rounded-xl p-6">
                <livewire:role-table />
        </div>
    </div>

    {{-- add --}}
    @include('pages.admin.manage-role.add')
@endsection
