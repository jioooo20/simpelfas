@extends('layouts.main')
@section('judul', 'Riwayat Perbaikan Fasilitas')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Riwayat Perbaikan Fasilitas</h1>
            <div class="flex space-x-2">
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="btn btn-outline">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </label>
                    <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                        <li><a class="active:bg-primary active:text-white" data-status="semua">Semua Status</a></li>
                        <li><a class="active:bg-primary active:text-white" data-status="selesai">Selesai</a></li>
                        <li><a class="active:bg-primary active:text-white" data-status="dibatalkan">Dibatalkan</a></li>
                    </ul>
                </div>
                <div class="form-control">
                    <div class="input-group">
                        <input type="text" placeholder="Cari..." class="input input-bordered" />
                        <button class="btn btn-square">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Riwayat Perbaikan -->
        <div class="overflow-x-auto bg-base-100 rounded-lg shadow-md">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th class="bg-base-200">No.</th>
                        <th class="bg-base-200">Kode Perbaikan</th>
                        <th class="bg-base-200">Fasilitas</th>
                        <th class="bg-base-200">Lokasi</th>
                        <th class="bg-base-200">Tanggal Selesai</th>
                        <th class="bg-base-200">Status</th>
                        <th class="bg-base-200 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Baris 1 -->
                    <tr class="hover">
                        <td>1</td>
                        <td>PBR-001</td>
                        <td>Proyektor Sony VPL-EX455</td>
                        <td>Gedung Informatika Lt. 2</td>
                        <td>21/05/2025</td>
                        <td><span class="badge badge-success">Selesai</span></td>
                        <td class="text-center">
                            <a href="{{ route('riwayat-perbaikan-detail') }}" class="btn btn-sm btn-circle btn-ghost text-blue-500">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn btn-sm btn-circle btn-ghost text-green-500" onclick="cetak_laporan_modal.showModal()">
                                <i class="fas fa-print"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Baris 2 -->
                    <tr class="hover">
                        <td>2</td>
                        <td>PBR-002</td>
                        <td>AC Panasonic 1.5PK</td>
                        <td>Gedung Rektorat Lt. 1</td>
                        <td>18/05/2025</td>
                        <td><span class="badge badge-success">Selesai</span></td>
                        <td class="text-center">
                            <a href="{{ route('teknisi') }}" class="btn btn-sm btn-circle btn-ghost text-blue-500">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn btn-sm btn-circle btn-ghost text-green-500" onclick="cetak_laporan_modal.showModal()">
                                <i class="fas fa-print"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Baris 3 -->
                    <tr class="hover">
                        <td>3</td>
                        <td>PBR-003</td>
                        <td>Kursi Lipat</td>
                        <td>Gedung Perpustakaan Lt. 3</td>
                        <td>15/05/2025</td>
                        <td><span class="badge badge-error">Dibatalkan</span></td>
                        <td class="text-center">
                            <a href="{{ route('teknisi') }}" class="btn btn-sm btn-circle btn-ghost text-blue-500">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn btn-sm btn-circle btn-ghost text-green-500" onclick="cetak_laporan_modal.showModal()">
                                <i class="fas fa-print"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Baris 4 -->
                    <tr class="hover">
                        <td>4</td>
                        <td>PBR-004</td>
                        <td>Smart TV Samsung 55"</td>
                        <td>Gedung Lab Komputer Lt. 1</td>
                        <td>10/05/2025</td>
                        <td><span class="badge badge-success">Selesai</span></td>
                        <td class="text-center">
                            <a href="{{ route('teknisi') }}" class="btn btn-sm btn-circle btn-ghost text-blue-500">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn btn-sm btn-circle btn-ghost text-green-500" onclick="cetak_laporan_modal.showModal()">
                                <i class="fas fa-print"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Baris 5 -->
                    <tr class="hover">
                        <td>5</td>
                        <td>PBR-005</td>
                        <td>Printer HP LaserJet Pro</td>
                        <td>Gedung Akademik Lt. 2</td>
                        <td>05/05/2025</td>
                        <td><span class="badge badge-success">Selesai</span></td>
                        <td class="text-center">
                            <a href="{{ route('teknisi') }}" class="btn btn-sm btn-circle btn-ghost text-blue-500">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn btn-sm btn-circle btn-ghost text-green-500" onclick="cetak_laporan_modal.showModal()">
                                <i class="fas fa-print"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-6">
            <div class="btn-group">
                <button class="btn btn-sm">«</button>
                <button class="btn btn-sm btn-active">1</button>
                <button class="btn btn-sm">2</button>
                <button class="btn btn-sm">3</button>
                <button class="btn btn-sm">»</button>
            </div>
        </div>
    </div>

    <!-- Modal Cetak Laporan -->
    <dialog id="cetak_laporan_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Cetak Laporan Perbaikan</h3>
            <p class="py-4">Pilih format laporan yang ingin dicetak:</p>
            <div class="flex flex-col gap-3">
                <button class="btn btn-outline btn-primary">
                    <i class="fas fa-file-pdf mr-2"></i>Cetak PDF
                </button>
                <button class="btn btn-outline">
                    <i class="fas fa-file-excel mr-2"></i>Ekspor Excel
                </button>
            </div>
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn">Tutup</button>
                </form>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

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