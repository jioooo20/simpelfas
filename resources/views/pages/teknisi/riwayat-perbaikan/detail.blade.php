@extends('layouts.main')
@section('judul', 'Detail Riwayat Perbaikan')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Detail Riwayat Perbaikan</h1>
            <div>
                <button onclick="cetak_laporan_modal.showModal()" class="btn btn-primary btn-sm">
                    <i class="fas fa-print mr-2"></i>Cetak Laporan
                </button>
            </div>
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
                            <span class="badge badge-primary mt-1 text-white">Selesai</span>
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
                            <span class="font-semibold">Kontak :</span>
                            <p>08123456789</p>
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

        <!-- Dokumentasi Foto -->
        <div class="mt-8">
            <h2 class="text-xl font-bold mb-4">Dokumentasi Foto</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Foto Saat Dilaporkan -->
                <div class="card bg-base-100 shadow-lg">
                    <figure class="px-4 pt-4">
                        <img src="https://placehold.co/400x300" alt="Foto Saat Dilaporkan"
                            class="rounded-lg h-48 w-full object-cover cursor-pointer"
                            onclick="openImageModal('https://via.placeholder.com/800x600?text=Foto+Kerusakan', 'Foto Saat Dilaporkan')">
                    </figure>
                    <div class="card-body pt-2">
                        <h3 class="card-title text-md">Foto Saat Dilaporkan</h3>
                        <p class="text-sm text-gray-500">19/05/2025 09:30</p>
                        <p class="text-sm">Lampu indikator menunjukkan kedipan merah 6 kali berulang.</p>
                    </div>
                </div>

                <!-- Foto Saat Perbaikan -->
                <div class="card bg-base-100 shadow-lg">
                    <figure class="px-4 pt-4">
                        <img src="https://placehold.co/400x300" alt="Foto Saat Perbaikan"
                            class="rounded-lg h-48 w-full object-cover cursor-pointer"
                            onclick="openImageModal('https://via.placeholder.com/800x600?text=Foto+Proses+Perbaikan', 'Foto Saat Perbaikan')">
                    </figure>
                    <div class="card-body pt-2">
                        <h3 class="card-title text-md">Foto Saat Perbaikan</h3>
                        <p class="text-sm text-gray-500">20/05/2025 13:45</p>
                        <p class="text-sm">Proses penggantian lampu proyektor dan pembersihan komponen.</p>
                    </div>
                </div>

                <!-- Foto Setelah Perbaikan -->
                <div class="card bg-base-100 shadow-lg">
                    <figure class="px-4 pt-4">
                        <img src="https://placehold.co/400x300" alt="Foto Setelah Perbaikan"
                            class="rounded-lg h-48 w-full object-cover cursor-pointer"
                            onclick="openImageModal('https://via.placeholder.com/800x600?text=Foto+Setelah+Perbaikan', 'Foto Setelah Perbaikan')">
                    </figure>
                    <div class="card-body pt-2">
                        <h3 class="card-title text-md">Foto Setelah Perbaikan</h3>
                        <p class="text-sm text-gray-500">21/05/2025 14:35</p>
                        <p class="text-sm">Proyektor berfungsi normal setelah penggantian lampu.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Histori update perbaikan -->
        <div class="mt-8">
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
                            <td>Ahmad Santoso (Dosen)</td>
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
                        <tr>
                            <td>19/05/2025 14:45</td>
                            <td><span class="badge badge-primary">Menunggu Komponen</span></td>
                            <td>Perlu penggantian lampu proyektor. Komponen dipesan</td>
                            <td>Budi Setiawan</td>
                        </tr>
                        <tr>
                            <td>20/05/2025 13:30</td>
                            <td><span class="badge badge-warning">Dalam Proses</span></td>
                            <td>Komponen diterima, mulai penggantian</td>
                            <td>Budi Setiawan</td>
                        </tr>
                        <tr>
                            <td>21/05/2025 14:35</td>
                            <td><span class="badge badge-success">Selesai</span></td>
                            <td>Perbaikan selesai, proyektor sudah berfungsi normal</td>
                            <td>Budi Setiawan</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tombol kembali -->
        <div class="mt-6 flex justify-end">
            <a href="{{ route('riwayat-perbaikan') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Modal Foto -->
    <dialog id="image_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box max-w-3xl">
            <h3 id="modal-title" class="font-bold text-lg mb-4">Foto</h3>
            <div class="flex justify-center">
                <img id="modal-image" src="https://placehold.co/400x300" alt="Preview" class="max-h-96 rounded-lg">
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

    <!-- Modal Cetak Laporan -->
    @include('pages.teknisi.perbaikan.cetak-laporan')

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
