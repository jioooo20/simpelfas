@extends('layouts.main')
@section('judul', 'Perbaikan Fasilitas')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <div class="bg-base-100 shadow-md border rounded-xl mb-3">
            <div class="p-6">
                <div id="content-perbaikanFasilitas" class="tab-content block">
                    <livewire:perbaikanFasilitas-table />
                </div>
            </div>
        </div>
    </div>

    @push('skrip')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Static toast examples could be triggered with buttons
                const showSuccessToast = (message) => {
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
                        escapeMarkup: false,
                        style: {
                            padding: "12px 20px",
                            fontWeight: "500",
                            minWidth: "300px"
                        },
                        onClick: function() {}
                    }).showToast();
                };

                const showErrorToast = (message) => {
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
                        escapeMarkup: false,
                        style: {
                            padding: "12px 20px",
                            fontWeight: "500",
                            minWidth: "300px"
                        },
                        onClick: function() {}
                    }).showToast();
                };

                // Example trigger for demonstration purposes
                // showSuccessToast("Perbaikan berhasil disimpan!");
            });
        </script>
    @endpush
@endsection
