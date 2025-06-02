@extends('layouts.main')
@section('judul', 'Riwayat Perbaikan Fasilitas')
@section('content')
<div class="container mx-auto px-4 py-4">
        <div class="bg-base-100 shadow-md border rounded-xl mb-3">
            <div class="p-6">
                <div id="content-riwayatPerbaikan" class="tab-content block">
                    <livewire:riwayatPerbaikan-table />
                </div>
            </div>
        </div>
    </div>
    @push('skrip')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Filter berdasarkan status
                const filterItems = document.querySelectorAll('.dropdown-content a');
                filterItems.forEach(item => {
                    item.addEventListener('click', function() {
                        const status = this.getAttribute('data-status');
                        // Implementasi filter bisa ditambahkan di sini
                        console.log('Filter by:', status);
                    });
                });

                // Pencarian
                const searchInput = document.querySelector('.input-group input');
                const searchButton = document.querySelector('.input-group button');

                searchButton.addEventListener('click', function() {
                    const keyword = searchInput.value.trim();
                    if (keyword) {
                        // Implementasi pencarian bisa ditambahkan di sini
                        console.log('Search for:', keyword);
                    }
                });

                // Pencarian dengan Enter
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        searchButton.click();
                    }
                });
            });
        </script>
    @endpush
@endsection
