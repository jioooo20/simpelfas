@extends('layouts.main')
@section('judul', 'Detail Perbaikan Fasilitas')
@section('content')
    <div class="container mx-auto px-4 py-4">
        @livewire('perbaikan-detail-view', ['perbaikanId' => $perbaikan->perbaikan_id])
    </div>
    @include('pages.teknisi.perbaikan.view-image')
@endsection

@push('skrip')
    <script>
        function showResponsiveToast(message, type = 'success') {
            const settings = {
                success: {
                    icon: 'bi-check-circle-fill',
                    background: 'linear-gradient(to right, #00b09b, #96c93d)',
                },
                error: {
                    icon: 'bi-exclamation-circle-fill',
                    background: 'linear-gradient(to right, #ff5f6d, #ffc371)',
                }
            };

            const currentSetting = settings[type];

            let options = {
                text: `<div class="flex items-center gap-3">
                      <i class="bi ${currentSetting.icon} text-xl"></i>
                      <span>${message}</span>
                   </div>`,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: currentSetting.background,
                className: "rounded-lg shadow-md",
                stopOnFocus: true,
                escapeMarkup: false,
                style: {
                    padding: "12px 20px",
                    fontWeight: "500",
                    minWidth: "320px"
                },
                onClick: function () {
                }
            };

            if (window.innerWidth < 768) {
                options.position = 'center';
                options.style.width = '90%';
                options.style.minWidth = '0';
                options.style.textAlign = 'center';
            }
            Toastify(options).showToast();
        }

        document.addEventListener('DOMContentLoaded', function () {
            Livewire.on('showSuccessToast', (message) => {
                showResponsiveToast(message, 'success');
            });

            Livewire.on('showErrorToast', (message) => {
                showResponsiveToast(message, 'error');
            });

            @if (session('error'))
            showResponsiveToast("{{ session('error') }}", 'error');
            @endif

            @if (session('success'))
            showResponsiveToast("{{ session('success') }}", 'success');
            @endif
        });
    </script>
@endpush
