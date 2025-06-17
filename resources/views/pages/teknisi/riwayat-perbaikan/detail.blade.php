@extends('layouts.main')
@section('judul', 'Detail Riwayat Perbaikan')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <div class="w-full flex justify-end items-center mb-4">
            <!-- Tombol kembali -->
            <div class="mt-6 flex justify-end">
                <a href="{{ route('riwayat-perbaikan') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
        @if($perbaikan)
            @livewire('riwayat-perbaikan-detail-view', ['id' => $perbaikan->perbaikan_id])
        @endif
    </div>
    @push('skrip')
        <script>
            function openImageModal(imageUrl, title) {
                const modalImage = document.getElementById('modal-image');
                const modalTitle = document.getElementById('modal-title');

                modalImage.src = imageUrl;
                modalTitle.textContent = title;

                image_modal.showModal();
            }
        </script>
    @endpush
@endsection
