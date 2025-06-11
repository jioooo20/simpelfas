@extends('layouts.main')
@section('judul', 'Detail Perbaikan Fasilitas')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <!-- Tombol kembali -->
        <div class="mt-6 flex justify-between">
            <h1 class="text-2xl font-bold mb-4">{{ $perbaikan->perbaikan_kode ?? '-' }}</h1>
            <a href="{{ route('teknisi') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>Kembali
            </a>
        </div>

        <!-- Tampilkan komponen Livewire untuk detail perbaikan -->
        @livewire('perbaikan-detail-view', ['perbaikanId' => $perbaikan->perbaikan_id])
    </div>
    @include('pages.teknisi.perbaikan.view-image')
@endsection

@push('skrip')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('showSuccessToast', (message) => {
                Toastify({
                    text: `<div class="flex items-center gap-3">
                          <i class="bi bi-check-circle-fill text-xl"></i>
                          <span>${message}</span>
                       </div>`,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                    className: "rounded-lg shadow-md",
                    stopOnFocus: true,
                    // close: true,
                    escapeMarkup: false,
                    style: {
                        padding: "12px 20px",
                        fontWeight: "500",
                        minWidth: "300px"
                    },
                    onClick: function() {}
                }).showToast();
            });

            Livewire.on('showErrorToast', (message) => {
                Toastify({
                    text: `<div class="flex items-center gap-3">
                          <i class="bi bi-exclamation-circle-fill text-xl"></i>
                          <span>${message}</span>
                       </div>`,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                    className: "rounded-lg shadow-md",
                    stopOnFocus: true,
                    // close: true,
                    escapeMarkup: false,
                    style: {
                        padding: "12px 20px",
                        fontWeight: "500",
                        minWidth: "300px"
                    },
                    onClick: function() {}
                }).showToast();
            });
        });
    </script>
@endpush
