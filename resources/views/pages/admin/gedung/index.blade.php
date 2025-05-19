@extends('layouts.main')
@section('judul', 'Pengelolaan Tata Ruang Gedung')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <div class="bg-base-100 shadow-md border rounded-xl mb-3">
            <div class="flex border-b">
                <button id="tab-gedung"
                    class="tab-btn px-6 py-4 font-semibold border-b-2 border-primary text-primary bg-gray-100 rounded-t-lg"
                    onclick="switchTab('gedung')">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-building"></i>
                        <span>Gedung</span>
                    </div>
                </button>
                <button id="tab-lantai" class="tab-btn px-6 py-4 font-semibold rounded-t-lg" onclick="switchTab('lantai')">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-layers"></i>
                        <span>Lantai</span>
                    </div>
                </button>
                <button id="tab-ruangan" class="tab-btn px-6 py-4 font-semibold rounded-t-lg"
                    onclick="switchTab('ruangan')">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-door-closed"></i>
                        <span>Ruangan</span>
                    </div>
                </button>
            </div>

            <div class="p-6">
                <div id="content-gedung" class="tab-content block">
                    <livewire:gedung-table />
                </div>

                <div id="content-lantai" class="tab-content hidden">
                    <livewire:lantai-table />
                </div>

                <div id="content-ruangan" class="tab-content hidden">
                    <livewire:ruang-table />
                </div>
            </div>
        </div>
    </div>
@endsection

@push('skrip')
    <script>
        function switchTab(tabName) {
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.add('hidden');
                content.classList.remove('block');
            });

            const selectedContent = document.getElementById('content-' + tabName);
            if (selectedContent) {
                setTimeout(() => {
                    selectedContent.classList.remove('hidden');
                    selectedContent.classList.add('block');
                }, 50);
            }

            const tabButtons = document.querySelectorAll('.tab-btn');
            tabButtons.forEach(btn => {
                btn.classList.remove('border-b-2', 'border-primary', 'text-primary', 'bg-gray-100', 'shadow-sm');
            });

            const activeTab = document.getElementById('tab-' + tabName);
            if (activeTab) {
                activeTab.classList.add('border-b-2', 'border-primary', 'text-primary', 'bg-gray-100', 'shadow-sm');
            }

            sessionStorage.setItem('activeTab', tabName);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const activeTab = sessionStorage.getItem('activeTab') || 'gedung';
            switchTab(activeTab);
        });
    </script>
@endpush

@push('skrip')
    <style>
        .tab-btn {
            transition: all 0.3s ease;
            position: relative;
        }

        .tab-btn:hover {
            background-color: #f9fafb;
        }

        .tab-content {
            transition: opacity 0.2s ease-in-out;
        }

        .tab-content.hidden {
            opacity: 0;
            display: none;
        }

        .tab-content.block {
            opacity: 1;
        }
    </style>
@endpush
