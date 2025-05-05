@extends('layouts.main')
@section('judul', 'Dasbor')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <div class="bg-base-100 shadow-lg border border-base-content rounded-xl p-6">
            {{-- <h1 class="text-4xl font-bold mb-4 text-center font-['Montserrat'] tracking-tight">Dasbor Pelaporan Fasilitas</h1>
            <p class="text-center mb-10 text-lg text-base-content/80 font-['Open_Sans']">Ringkasan status laporan kerusakan fasilitas terkini.</p> --}}

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div class="stats shadow bg-base-200 rounded-lg">
                    <div class="stat p-4">
                        <div class="stat-figure text-indigo-500">
                            <i class="bi bi-journal-text text-5xl "></i>
                        </div>
                        <div class="stat-title font-['Open_Sans'] text-sm font-semibold uppercase tracking-wider">Total Laporan</div>
                        <div class="stat-value font-['Montserrat'] text-3xl">152</div>
                        <div class="stat-desc font-['Open_Sans'] text-xs mt-1 opacity-90">Semua laporan masuk</div>
                    </div>
                </div>

                <div class="stats shadow bg-base-200 rounded-lg">
                    <div class="stat p-4">
                        <div class="stat-figure">
                            <i class="bi bi-hourglass-split text-5xl opacity-80 text-yellow-500"></i>
                        </div>
                        <div class="stat-title font-['Open_Sans'] text-sm font-semibold uppercase tracking-wider">Laporan Pending</div>
                        <div class="stat-value font-['Montserrat'] text-3xl">15</div>
                        <div class="stat-desc font-['Open_Sans'] text-xs mt-1 opacity-90">Menunggu verifikasi</div>
                    </div>
                </div>

                <div class="stats shadow bg-base-200 rounded-lg">
                    <div class="stat p-4">
                        <div class="stat-figure">
                            <i class="bi bi-tools text-5xl opacity-80 text-blue-600"></i>
                        </div>
                        <div class="stat-title font-['Open_Sans'] text-sm font-semibold uppercase tracking-wider">Sedang Diproses</div>
                        <div class="stat-value font-['Montserrat'] text-3xl">8</div>
                        <div class="stat-desc font-['Open_Sans'] text-xs mt-1 opacity-90">Dalam perbaikan</div>
                    </div>
                </div>

                <div class="stats shadow bg-base-200 rounded-lg">
                    <div class="stat p-4">
                        <div class="stat-figure">
                            <i class="bi bi-check-circle-fill text-5xl opacity-80 text-green-600"></i>
                        </div>
                        <div class="stat-title font-['Open_Sans'] text-sm font-semibold uppercase tracking-wider">Laporan Selesai</div>
                        <div class="stat-value font-['Montserrat'] text-3xl">129</div>
                        <div class="stat-desc font-['Open_Sans'] text-xs mt-1 opacity-90">Telah ditangani</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-base-200 p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold mb-4 font-['Montserrat']">Grafik Tren Laporan</h2>
                    <div class="h-64 md:h-80">
                        {{-- chart js atau apalah --}}
                        <canvas id="reportTrendsChart"></canvas>

                        {{-- chart --}}
                        <p class="text-center text-base-content/50 font-['Open_Sans'] mt-4">(Integrasi pustaka grafik diperlukan)</p>
                    </div>
                </div>

                <div class="bg-base-200 p-6 rounded-lg shadow-md flex flex-col">
                    <h2 class="text-xl font-semibold mb-4 font-['Montserrat'] flex-shrink-0">Laporan Terbaru</h2>
                    <div class="space-y-5 max-h-80 overflow-y-auto pr-2 flex-grow min-h-0">
                        {{-- Loop through recent reports (replace with dynamic data) --}}
                        {{-- Example Item 1: New Report --}}
                        <div class="flex items-start space-x-3 group pl-1">
                            <div class="avatar placeholder mt-1 flex-shrink-0">
                                <div class="bg-warning text-warning-content rounded-full w-8 h-8 flex items-center justify-center ring ring-offset-base-100 ring-offset-2 ring-warning group-hover:ring-primary transition-all duration-300">
                                    <i class="bi bi-exclamation-triangle-fill text-base"></i>
                                </div>
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium font-['Open_Sans'] text-sm leading-snug">Laporan Baru: <a href="#" class="font-bold hover:text-primary">Keran Air Bocor</a> di Gedung A Lt. 2.</p>
                                <p class="text-xs text-base-content/60 font-['Open_Sans']">Oleh: Budi S. - 10 menit lalu</p>
                            </div>
                        </div>
                        {{-- Example Item 2: Report In Progress --}}
                        <div class="flex items-start space-x-3 group pl-1">
                             <div class="avatar placeholder mt-1 flex-shrink-0">
                                <div class="bg-info text-info-content rounded-full w-8 h-8 flex items-center justify-center ring ring-offset-base-100 ring-offset-2 ring-info group-hover:ring-secondary transition-all duration-300">
                                    <i class="bi bi-wrench text-base"></i>
                                </div>
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium font-['Open_Sans'] text-sm leading-snug">Status Diubah: <a href="#" class="font-bold hover:text-info">Lampu Koridor Mati</a> menjadi "Sedang Diproses".</p>
                                <p class="text-xs text-base-content/60 font-['Open_Sans']">Oleh: Admin - 1 jam lalu</p>
                            </div>
                        </div>
                         {{-- Example Item 3: Report Completed --}}
                        <div class="flex items-start space-x-3 group pl-1">
                            <div class="avatar placeholder mt-1 flex-shrink-0">
                                <div class="bg-success text-success-content rounded-full w-8 h-8 flex items-center justify-center ring ring-offset-base-100 ring-offset-2 ring-success group-hover:ring-accent transition-all duration-300">
                                    <i class="bi bi-check-circle-fill text-base"></i>
                                </div>
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium font-['Open_Sans'] text-sm leading-snug">Status Diubah: <a href="#" class="font-bold hover:text-accent">AC Ruang Rapat Rusak</a> menjadi "Selesai".</p>
                                <p class="text-xs text-base-content/60 font-['Open_Sans']">Oleh: Admin - 3 jam lalu</p>
                            </div>
                        </div>
                         {{-- Example Item 4: New Report --}}
                        <div class="flex items-start space-x-3 group pl-1">
                             <div class="avatar placeholder mt-1 flex-shrink-0">
                                <div class="bg-warning text-warning-content rounded-full w-8 h-8 flex items-center justify-center ring ring-offset-base-100 ring-offset-2 ring-warning group-hover:ring-info transition-all duration-300">
                                     <i class="bi bi-exclamation-triangle-fill text-base"></i>
                                </div>
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium font-['Open_Sans'] text-sm leading-snug">Laporan Baru: <a href="#" class="font-bold hover:text-info">Pintu Toilet Macet</a> di Gedung B Lt. 1.</p>
                                <p class="text-xs text-base-content/60 font-['Open_Sans']">Oleh: Citra L. - Kemarin</p>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-ghost btn-sm mt-4 w-full font-['Open_Sans'] text-primary hover:bg-primary/10 flex-shrink-0"> {{-- Added flex-shrink-0 --}}
                        Lihat Semua Laporan <i class="bi bi-arrow-right ml-1"></i>
                    </button>
                </div>
            </div>

            <!-- Quick Actions -->
            {{-- <div class="mt-10 pt-6 border-t border-base-content/10">
                <h2 class="text-xl font-semibold mb-4 font-['Montserrat']">Aksi Cepat</h2>
                <div class="flex flex-wrap gap-4">

                    <button class="btn btn-primary font-['Open_Sans'] shadow hover:shadow-lg transition-shadow">
                        <i class="bi bi-eye-fill mr-2"></i> Lihat Semua Laporan
                    </button>
                    <button class="btn btn-secondary font-['Open_Sans'] shadow hover:shadow-lg transition-shadow">
                        <i class="bi bi-card-list mr-2"></i> Kelola Kategori Laporan
                    </button>
                     <button class="btn btn-accent font-['Open_Sans'] shadow hover:shadow-lg transition-shadow">
                        <i class="bi bi-people-fill mr-2"></i> Kelola Pengguna
                    </button>
                    <button class="btn btn-info font-['Open_Sans'] shadow hover:shadow-lg transition-shadow">
                        <i class="bi bi-gear-wide-connected mr-2"></i> Pengaturan Sistem
                    </button>
                </div>
            </div> --}}
        </div>
    </div>
@endsection
