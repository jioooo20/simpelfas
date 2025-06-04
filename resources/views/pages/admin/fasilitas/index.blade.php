@extends('layouts.main')
@section('judul', 'Pengelolaan Tata Ruang Gedung')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <div class="bg-base-100 shadow-md border rounded-xl p-6">
            <div class="overflow-x-auto">
                <livewire:fasilitas-table />

            </div>
        </div>
    </div>

@endsection
