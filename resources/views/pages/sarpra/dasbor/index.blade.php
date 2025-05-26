@extends('layouts.main')
@section('judul', 'Dasbor Sarana Prasarana')
@section('content')
    <div class="container mx-auto px-4 py-4">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

            <div class="bg-base-100 shadow-lg border border-base-content/20 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Total Laporan</h3>
                        <p class="text-2xl font-bold">124</p>
                        <p class="text-xs text-green-600 mt-1">
                            <span class="font-bold">↑ 12%</span> dari bulan lalu
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-clipboard-list text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-base-100 shadow-lg border border-base-content/20 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Menunggu Verifikasi</h3>
                        <p class="text-2xl font-bold">28</p>
                        <p class="text-xs text-yellow-600 mt-1">
                            <span class="font-bold">⚠ Perlu diverifikasi</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-hourglass-half text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-base-100 shadow-lg border border-base-content/20 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Sedang Diperbaiki</h3>
                        <p class="text-2xl font-bold">45</p>
                        <p class="text-xs text-blue-600 mt-1">
                            <span class="font-bold">↻ Dalam proses</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-wrench text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-base-100 shadow-lg border border-base-content/20 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Selesai Bulan Ini</h3>
                        <p class="text-2xl font-bold">37</p>
                        <p class="text-xs text-green-600 mt-1">
                            <span class="font-bold">✓ Terselesaikan</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2">
                <div class="bg-base-100 shadow-lg border border-base-content/20 rounded-xl p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold">Prioritas Perbaikan</h2>
                        <div class="dropdown dropdown-end">
                            <button class="btn btn-sm btn-ghost"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                            <ul class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                                <li><a>Lihat Semua</a></li>
                                <li><a>Export Data</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full">
                            <thead>
                                <tr>
                                    <th>Laporan</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                    <th>Prioritas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="font-bold">Kebocoran AC</div>
                                        <div class="text-xs text-gray-500">5 jam yang lalu</div>
                                    </td>
                                    <td>Gedung TI - Lantai 3 - Ruang 302</td>
                                    <td><span class="badge badge-warning">Menunggu</span></td>
                                    <td><span class="badge badge-error">Tinggi</span></td>
                                    <td>
                                        <button class="btn btn-xs btn-primary">Tugaskan</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="font-bold">Kursi Rusak</div>
                                        <div class="text-xs text-gray-500">1 hari yang lalu</div>
                                    </td>
                                    <td>Gedung TE - Lantai 2 - Ruang 204</td>
                                    <td><span class="badge badge-warning">Menunggu</span></td>
                                    <td><span class="badge badge-error">Tinggi</span></td>
                                    <td>
                                        <button class="btn btn-xs btn-primary">Tugaskan</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="font-bold">Proyektor Tidak Berfungsi</div>
                                        <div class="text-xs text-gray-500">3 hari yang lalu</div>
                                    </td>
                                    <td>Gedung TI - Lantai 2 - Ruang 201</td>
                                    <td><span class="badge badge-info">Diproses</span></td>
                                    <td><span class="badge badge-error">Tinggi</span></td>
                                    <td>
                                        <button class="btn btn-xs btn-ghost">Detail</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="font-bold">Lampu Mati</div>
                                        <div class="text-xs text-gray-500">2 hari yang lalu</div>
                                    </td>
                                    <td>Gedung TI - Lantai 1 - Ruang 105</td>
                                    <td><span class="badge badge-warning">Menunggu</span></td>
                                    <td><span class="badge badge-warning">Sedang</span></td>
                                    <td>
                                        <button class="btn btn-xs btn-primary">Tugaskan</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-base-100 shadow-lg border border-base-content/20 rounded-xl p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold">Statistik Laporan Kerusakan</h2>
                        <div class="dropdown dropdown-end">
                            <select class="select select-sm select-bordered">
                                <option>Bulan Ini</option>
                                <option>3 Bulan Terakhir</option>
                                <option>6 Bulan Terakhir</option>
                                <option>Tahun Ini</option>
                            </select>
                        </div>
                    </div>
                    <div class="h-64 w-full">
                        <!-- Chart would be inserted here with JavaScript -->
                        <div class="flex h-full items-end justify-between px-4">
                            <div class="flex flex-col items-center">
                                <div class="bg-primary w-10 rounded-t" style="height: 70%"></div>
                                <span class="mt-2 text-xs">Jan</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="bg-primary w-10 rounded-t" style="height: 50%"></div>
                                <span class="mt-2 text-xs">Feb</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="bg-primary w-10 rounded-t" style="height: 65%"></div>
                                <span class="mt-2 text-xs">Mar</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="bg-primary w-10 rounded-t" style="height: 80%"></div>
                                <span class="mt-2 text-xs">Apr</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="bg-primary w-10 rounded-t" style="height: 95%"></div>
                                <span class="mt-2 text-xs">Mei</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="bg-primary w-10 rounded-t" style="height: 40%"></div>
                                <span class="mt-2 text-xs">Jun</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="bg-primary w-10 rounded-t" style="height: 30%"></div>
                                <span class="mt-2 text-xs">Jul</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">

                <div class="bg-base-100 shadow-lg border border-base-content/20 rounded-xl p-6 mb-6">
                    <h2 class="text-lg font-bold mb-4">Penugasan Teknisi</h2>
                    <div class="space-y-4">
                        <div class="flex items-center p-3 border border-base-content/10 rounded-lg">
                            <div class="avatar mr-3">
                                <div class="w-12 rounded-full">
                                    <img src="https://ui-avatars.com/api/?name=Ahmad+Teknisi&background=random" />
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-bold">Ahmad Teknisi</h3>
                                <div class="text-xs">4 tugas aktif</div>
                            </div>
                            <div>
                                <button class="btn btn-xs btn-ghost">Detail</button>
                            </div>
                        </div>

                        <div class="flex items-center p-3 border border-base-content/10 rounded-lg">
                            <div class="avatar mr-3">
                                <div class="w-12 rounded-full">
                                    <img src="https://ui-avatars.com/api/?name=Budi+Kelistrikan&background=random" />
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-bold">Budi Kelistrikan</h3>
                                <div class="text-xs">2 tugas aktif</div>
                            </div>
                            <div>
                                <button class="btn btn-xs btn-ghost">Detail</button>
                            </div>
                        </div>

                        <div class="flex items-center p-3 border border-base-content/10 rounded-lg">
                            <div class="avatar mr-3">
                                <div class="w-12 rounded-full">
                                    <img src="https://ui-avatars.com/api/?name=Charlie+Perbaikan&background=random" />
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-bold">Charlie Perbaikan</h3>
                                <div class="text-xs">1 tugas aktif</div>
                            </div>
                            <div>
                                <button class="btn btn-xs btn-ghost">Detail</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-base-100 shadow-lg border border-base-content/20 rounded-xl p-6 mb-6">
                    <h2 class="text-lg font-bold mb-4">Tingkat Kepuasan</h2>
                    <div class="mb-3">
                        <div class="flex justify-between mb-1">
                            <span class="text-sm">Sangat Puas</span>
                            <span class="text-sm font-bold">68%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 68%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="flex justify-between mb-1">
                            <span class="text-sm">Puas</span>
                            <span class="text-sm font-bold">25%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: 25%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="flex justify-between mb-1">
                            <span class="text-sm">Cukup Puas</span>
                            <span class="text-sm font-bold">5%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-500 h-2 rounded-full" style="width: 5%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm">Tidak Puas</span>
                            <span class="text-sm font-bold">2%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full" style="width: 2%"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-base-100 shadow-lg border border-base-content/20 rounded-xl p-6">
                    <h2 class="text-lg font-bold mb-4">Distribusi Kategori Kerusakan</h2>
                    <div class="flex justify-center">
                        <div class="w-40 h-40 rounded-full border-8 border-gray-200 relative">
                            <div class="absolute inset-0 border-t-8 border-r-8 border-blue-500 rounded-full" style="clip-path: polygon(50% 0, 100% 0, 100% 100%, 50% 100%)"></div>
                            <div class="absolute inset-0 border-b-8 border-l-8 border-green-500 rounded-full" style="clip-path: polygon(0 0, 50% 0, 50% 100%, 0% 100%)"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-xl font-bold">124</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center mt-4 gap-4">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 mr-2"></div>
                            <span class="text-xs">Elektronik (65)</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 mr-2"></div>
                            <span class="text-xs">Furniture (59)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('skrip')
    <script>
        // chart.js
        console.log('Dashboard charts initialized');
    </script>
@endpush
