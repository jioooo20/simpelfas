@extends('layouts.main')
@section('judul', 'Detail Perbaikan Fasilitas')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <!-- Tombol kembali -->
        <div class="mt-6 flex justify-between">
            <h1 class="text-2xl font-bold mb-4">PRB-001</h1>
            <a href="{{ route('teknisi') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Section 1: Informasi Perbaikan -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-lg font-bold bg-base-200 p-2 -mx-4 -mt-4 rounded-t-lg">
                        Informasi Perbaikan
                    </h2>

                    <div class="mt-4">
                        <div class="mb-3 flex flex-col">
                            <span class="font-semibold">Status :</span>
                            <span class="badge badge-primary mt-1 text-white">Dalam Proses</span>
                        </div>

                        <div class="mb-3">
                            <span class="font-semibold">Kode Perbaikan :</span>
                            <p>PBR-001</p>
                        </div>

                        <div class="mb-3">
                            <span class="font-semibold">Tanggal Dibuat :</span>
                            <p>19/05/2025 09:30</p>
                        </div>

                        <div class="mb-3">
                            <span class="font-semibold">Terakhir Update :</span>
                            <p>19/05/2025 14:45</p>
                        </div>
                    </div>
                    <!-- Tombol aksi untuk teknisi -->
                    <div class="card-actions justify-end mt-16">
                        <button onclick="update_status_modal.showModal()" class="btn btn-primary btn-sm text-white">Update
                            Status</button>
                    </div>
                </div>
            </div>

            <!-- Section 2: Informasi Laporan -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-lg font-bold bg-base-200 p-2 -mx-4 -mt-4 rounded-t-lg">
                        Informasi Laporan
                    </h2>

                    <div class="mt-4">
                        <div class="mb-3">
                            <span class="font-semibold">Lokasi :</span>
                            <p>Gedung Informatika Lt. 2</p>
                        </div>

                        <div class="mb-3">
                            <span class="font-semibold">Fasilitas :</span>
                            <p>Proyektor Sony VPL-EX455</p>
                        </div>

                        <div class="mb-3">
                            <span class="font-semibold">Deskripsi Masalah :</span>
                            <p class="text-sm">Proyektor tidak menyala saat dihidupkan. Lampu indikator power berkedip merah
                                sebanyak 6 kali secara berulang.</p>
                        </div>

                        <div class="mb-3">
                            <span class="font-semibold">Dilaporkan oleh :</span>
                            <p>Ahmad Santoso (Dosen)</p>
                        </div>

                        <div>
                            <span class="font-semibold">Bukti Foto :</span>
                        </div>
                        <div class="mt-2">
                            <button onclick="view_laporan_image_modal.showModal()"
                                class="btn btn-sm btn-primary mt-1 text-white">
                                <i class="fas fa-image"></i>Lihat Foto
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Informasi Teknisi -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-lg font-bold bg-base-200 p-2 -mx-4 -mt-4 rounded-t-lg">
                        Informasi Teknisi
                    </h2>

                    <div class="mt-4">
                        <div class="mb-3">
                            <span class="font-semibold">Nama Teknisi :</span>
                            <p>Budi Setiawan</p>
                        </div>

                        <div class="mb-3">
                            <span class="font-semibold">Deskripsi Perbaikan :</span>
                            <p class="text-sm">Berdasarkan pemeriksaan awal, lampu proyektor perlu diganti. Sudah memesan
                                komponen baru, estimasi tiba 2 hari lagi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Histori update perbaikan -->
        <div class="mt-8 mb-10">
            <h2 class="text-xl font-bold mb-4">Histori Perbaikan</h2>
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>19/05/2025 09:30</td>
                            <td><span class="badge badge-ghost">Dilaporkan</span></td>
                            <td>Penugasan Perbaikan Fasilitas dibuat</td>
                            <td>Ahmad Santoso (Sarpras)</td>
                        </tr>
                        <tr>
                            <td>19/05/2025 10:15</td>
                            <td><span class="badge badge-info">Diverifikasi</span></td>
                            <td>Tiket diverifikasi dan diteruskan ke teknisi</td>
                            <td>Admin</td>
                        </tr>
                        <tr>
                            <td>19/05/2025 13:20</td>
                            <td><span class="badge badge-warning">Dalam Proses</span></td>
                            <td>Mulai diagnosis masalah</td>
                            <td>Budi Setiawan</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('pages.teknisi.perbaikan.view-image')
    @include('pages.teknisi.perbaikan.update-status')
@endsection
