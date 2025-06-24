@extends('layouts.main')
@section('judul', 'Detail Riwayat Perbaikan')
@section('content')
    <div class="container mx-auto px-4 py-4">
        @if($perbaikan)
            @livewire('riwayat-perbaikan-detail-view', ['id' => $perbaikan->perbaikan_id])
        @endif
    </div>
@endsection
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
