@extends('layouts.main')
@section('judul', 'Rekomendasi Biaya Perbaikan Fasilitas')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <div id="content-perbaikanFasilitas" class="tab-content block">
            <livewire:rekomendasi-biaya-perbaikan />
        </div>
    </div>
@endsection
