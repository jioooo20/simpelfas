@extends('layouts.main')
@section('judul', 'Status Laporan')
@section('content')
    <!-- Main Content -->
    <div class="p-4" x-data="laporanTable()">

        <!-- Table Container -->
        <div class="bg-white shadow rounded-lg p-4 space-y-4" x-data="laporanTable()" x-init="init()">

            <!-- Search and Filter Section -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <!-- Search Input -->
                <div class="relative flex-grow">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="bi bi-search text-gray-500"></i>
                    </span>
                    <input type="text" x-model="search" placeholder="Cari laporan..."
                           class="w-full h-10 pl-10 pr-4 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-400"/>
                </div> <!-- End of Search Input -->

                <!-- Filter Dropdown -->
                <div class="dropdown dropdown-end" x-data="{ showDropdown: false }">
                    <label tabindex="0" @click="showDropdown = !showDropdown" class="btn btn-outline btn-primary h-10">
                        <i class="bi bi-funnel me-2"></i> Filter
                    </label>
                    <ul x-show="showDropdown" @click.away="showDropdown = false"
                        class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52 mt-2">
                        <li>
                            <a href="#"
                               :class="filter === 'Menunggu' ? 'bg-gray-200 text-gray-600' : 'hover:bg-gray-100'"
                               @click.prevent="filter = 'Menunggu'; page = 1; showDropdown = false">Menunggu</a>
                        </li>
                        <li>
                            <a href="#"
                               :class="filter === 'Diproses' ? 'bg-gray-200 text-gray-600' : 'hover:bg-gray-100'"
                               @click.prevent="filter = 'Diproses'; page = 1; showDropdown = false">Diproses</a>
                        </li>
                        <li>
                            <a href="#"
                               :class="filter === 'Selesai' ? 'bg-gray-200 text-gray-600' : 'hover:bg-gray-100'"
                               @click.prevent="filter = 'Selesai'; page = 1; showDropdown = false">Selesai</a>
                        </li>
                        <li>
                            <a href="#"
                               :class="filter === 'Ditolak' ? 'bg-gray-200 text-gray-600' : 'hover:bg-gray-100'"
                               @click.prevent="filter = 'Ditolak'; page = 1; showDropdown = false">Ditolak</a>
                        </li>
                        <li>
                            <a href="#"
                               :class="filter === '' ? 'bg-gray-200 text-gray-600' : 'hover:bg-gray-100'"
                               @click.prevent="filter = ''; page = 1; showDropdown = false">Semua</a>
                        </li>
                    </ul>
                </div> <!-- End of Filter Dropdown -->
            </div> <!-- End of Search and Filter Section -->

            <!-- Loading Spinner -->
            <div x-show="loading" class="flex items-center justify-center py-10">
                <div class="flex flex-col items-center gap-2 text-gray-500">
                    <span class="loading loading-spinner loading-md text-primary"></span>
                    <p class="text-sm">Memuat data...</p>
                </div>
            </div> <!-- End of Loading Spinner -->

            <!-- Tabel Laporan -->
            <div class="overflow-x-auto rounded-xl border border-gray-200" x-show="!loading" x-cloak>
                <table class="table w-full table-fixed">
                    <thead class="bg-base-200 text-base-content">
                    <tr class="text-center">
                        <th class="w-[4rem]">No</th>
                        <th class="w-[15%] text-left">Kode Laporan</th>
                        <th class="w-[40%] text-left">Fasilitas</th>
                        <th class="w-[20%]">Tanggal</th>
                        <th class="w-[20%]">Status</th>
                        <th class="w-[16%]">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>

                    <!-- Data laporan -->
                    <template x-for="(laporan, index) in paginatedLaporan()" :key="laporan.id">
                        <tr :class="index % 2 === 0 ? 'bg-white' : 'bg-base-200'">
                            <!-- Nomor -->
                            <td class="text-center" x-text="index + 1 + (page - 1) * perPage"></td>

                            <!-- Kode Laporan -->
                            <td class="text-left" x-text="laporan.kode"></td>

                            <!-- Fasilitas -->
                            <td class="whitespace-nowrap overflow-hidden text-ellipsis max-w-xs"
                                :title="laporan.fasilitas" x-text="laporan.fasilitas"></td>

                            <!-- Tanggal -->
                            <td class="text-center" x-text="laporan.tanggal"></td>

                            <!-- Status -->
                            <td class="text-center">
                                <template x-if="laporan.status">
                                <span :class="badgeStyle(laporan.status)"
                                      class="inline-flex items-center justify-center gap-1 w-28 h-7 px-2 rounded-full text-sm font-medium">
                                    <span x-html="badgeIcon(laporan.status)"
                                          class="text-sm opacity-80 leading-none"></span>
                                    <span class="text-center" x-text="laporan.status"></span>
                                </span>
                                </template>
                            </td>

                            <!-- Aksi -->
                            <td class="text-center">
                                <a href="#"
                                   @click.prevent="redirectToDetail(laporan.id)"
                                   class="btn btn-sm btn-outline btn-primary flex items-center gap-1 justify-center whitespace-nowrap">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    </template> <!-- End of Data Laporan -->

                    <!-- Jika hasil pencarian tidak ditemukan -->
                    <tr x-show="(search || filter) && paginatedLaporan().length === 0 && laporan.length > 0"
                        class="bg-base-100">
                        <td colspan="5" class="py-10">
                            <div class="flex flex-col items-center justify-center text-gray-400 gap-3">
                                <i class="bi bi-folder-x text-4xl"></i>
                                <p class="text-sm font-medium">Tidak ada laporan ditemukan</p>
                                <p class="text-xs">Coba ubah kata kunci pencarian atau filter yang digunakan.</p>
                            </div>
                        </td>
                    </tr>

                    <!-- Jika memang belum ada laporan sama sekali -->
                    <tr x-show="laporan.length === 0" class="bg-base-100">
                        <td colspan="5" class="py-10">
                            <div class="flex flex-col items-center justify-center text-gray-400 gap-3">
                                <i class="bi bi-clipboard-x text-4xl"></i>
                                <p class="text-sm font-medium">Belum ada laporan</p>
                                <p class="text-xs">Kamu belum pernah membuat laporan.</p>
                            </div>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div> <!-- End of Table -->

            <!-- Pagination Section -->
            <div class="mt-4 flex items-center justify-between flex-wrap gap-2" x-show="!loading" x-cloak>
                <!-- Show pagination info only if there are results -->
                <template x-if="filteredLaporan().length > 0">
                    <p class="text-sm text-gray-500"
                       x-text="`Menampilkan ${(page - 1) * perPage + 1} – ${Math.min(page * perPage, filteredLaporan().length)} dari ${filteredLaporan().length} hasil`">
                    </p>
                </template>

                <!-- Empty State -->
                <template x-if="filteredLaporan().length === 0">
                    <p></p>
                </template>

                <!-- Pagination Buttons -->
                <div class="join" x-show="filteredLaporan().length > 0">
                    <template x-for="n in visiblePages()" :key="generateKey(n)">
                        <button
                            :class="[
                                'join-item',
                                typeof n === 'number'
                                    ? 'btn btn-sm' + (page === n ? ' btn-active' : '')
                                    : 'px-3 text-gray-400 bg-transparent border-none cursor-default hover:bg-transparent select-none'
                            ]"
                            x-show="typeof n === 'number' || n === 'left-ellipsis' || n === 'right-ellipsis'"
                            @click="typeof n === 'number' ? page = n : handleEllipsisClick(n === 'left-ellipsis' ? 'left' : 'right')"
                            x-text="typeof n === 'number' ? n : '...'"
                            :disabled="typeof n !== 'number'"
                        ></button>
                    </template>
                </div> <!-- End of Pagination Buttons -->
            </div> <!-- End of Pagination Section -->
        </div> <!-- End of Table Container -->
    </div> <!-- End of Main Content -->
@endsection
@push('css')
    <style>
        [x-cloak] {
            display: none !important;
        }

        .dropdown-content a:active {
            background-color: #d1d5db !important;
            color: #374151 !important;
        }
    </style>
@endpush
@push('skrip')
    <script>
        function laporanTable() {
            return {
                search: '',
                filter: '',
                page: 1,
                perPage: 4,
                loading: true,
                laporan: [],
                laporanCache: {},
                ellipsisClickedAt: null,

                init() {
                    fetch('/users/laporan-data')
                        .then(response => response.json())
                        .then(data => {
                            this.laporan = data;
                            this.loading = false;
                        })
                        .catch(error => {
                            console.error('Gagal mengambil data:', error);
                            this.loading = false;
                        });
                },
                redirectToDetail(id) {
                    window.location.href = `/users/laporan-detail/${id}`;
                },
                filteredLaporan() {
                    const result = this.laporan
                        .map((item, index) => ({...item, index}))
                        .filter(item => {
                            const searchText = this.search.toLowerCase();
                            const searchWords = searchText.split(' ').filter(Boolean);
                            const searchTarget = `${item.kode} ${item.fasilitas} ${item.tanggal} ${item.status} ${item.index + 1}`.toLowerCase();
                            const matchSearch = searchWords.every(word => searchTarget.includes(word));
                            const matchFilter = this.filter === '' || item.status === this.filter;
                            return matchSearch && matchFilter;
                        });

                    if (result.length === 0 || (this.page - 1) * this.perPage >= result.length) {
                        this.page = 1;
                    }

                    return result;
                },
                totalPages() {
                    return Math.ceil(this.filteredLaporan().length / this.perPage);
                },
                paginatedLaporan() {
                    const start = (this.page - 1) * this.perPage;
                    return this.filteredLaporan().slice(start, start + this.perPage);
                },
                visiblePages() {
                    const total = this.totalPages();
                    const current = this.page;

                    if (total <= 7) {
                        return Array.from({length: total}, (_, i) => i + 1);
                    }

                    const pages = [];

                    // Always show first page
                    pages.push(1);

                    // Show left ellipsis if needed
                    if (current > 4) {
                        pages.push('left-ellipsis');
                    }

                    // Middle pages
                    const startPage = Math.max(2, current - 1);
                    const endPage = Math.min(total - 1, current + 1);
                    for (let i = startPage; i <= endPage; i++) {
                        pages.push(i);
                    }

                    // Show right ellipsis if needed
                    if (current < total - 3) {
                        pages.push('right-ellipsis');
                    }

                    // Always show last page
                    pages.push(total);

                    return pages;
                },
                generateKey(n) {
                    return typeof n === 'number' ? `page-${n}` : `ellipsis-${n}`;
                },
                handleEllipsisClick(position) {
                    const total = this.totalPages();
                    if (position === 'left') {
                        this.page = Math.floor((1 + this.page) / 2);
                    } else if (position === 'right') {
                        this.page = Math.floor((this.page + total) / 2);
                    }
                },

                badgeStyle(status) {
                    return {
                        'Menunggu': 'bg-amber-100 text-amber-800 border border-amber-200',
                        'Diterima': 'bg-emerald-100 text-emerald-800 border border-emerald-200',
                        'Diproses': 'bg-blue-100 text-blue-800 border border-blue-200',
                        'Selesai': 'bg-green-100 text-green-800 border border-green-200',
                        'Ditolak': 'bg-red-100 text-red-800 border border-red-200',
                    }[status] || 'bg-gray-100 text-gray-800 border border-gray-200';
                },
                badgeIcon(status) {
                    return {
                        'Menunggu': '<i class=\'bi bi-hourglass\'></i>',
                        'Diterima': '<i class =\'bi bi-check2-circle\'></i>',
                        'Diproses': '<i class=\'bi bi-gear\'></i>',
                        'Selesai': '<i class=\'bi bi-check-circle\'></i>',
                        'Ditolak': '<i class=\'bi bi-x-circle\'></i>',
                    }[status] || '';
                }
            }
        }
    </script>
@endpush
