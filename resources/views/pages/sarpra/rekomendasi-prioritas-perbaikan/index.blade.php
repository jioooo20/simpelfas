@extends('layouts.main')
@section('judul', 'Rekomendasi Prioritas Perbaikan')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <div class="bg-base-100 shadow-lg border border-base-content rounded-xl p-6 mb-6">
                <livewire:rekomendasi-prioritas-perbaikan />
        </div>
    </div>
@endsection
