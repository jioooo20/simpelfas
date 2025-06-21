@extends('layouts.main')
@section('judul', 'History Laporan')
@section('content')
    <div class="p-4" x-data="historyTable()">

        <div class="bg-white shadow rounded-lg p-4 space-y-4" x-init="init()">

            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="relative flex-grow">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="bi bi-search text-gray-500"></i>
                    </span>
                    <input type="text" x-model.debounce.300ms="search" @input="page = 1"
                           placeholder="Cari kode, fasilitas, atau pelapor..."
                           class="w-full h-10 pl-10 pr-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"/>
                </div>

                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="btn btn-outline btn-primary h-10">
                        <i class="bi bi-funnel me-2"></i> Filter
                    </label>
                    <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-64 mt-2">
                        <li class="menu-title"><span>Status Laporan</span></li>
                        <li><a @click.prevent="applyFilter('laporan', 'Menunggu')"
                               :class="{'bg-base-200': filter.laporan === 'Menunggu'}">Menunggu</a></li>
                        <li><a @click.prevent="applyFilter('laporan', 'Diterima')"
                               :class="{'bg-base-200': filter.laporan === 'Diterima'}">Diterima</a></li>
                        <li><a @click.prevent="applyFilter('laporan', 'Diproses')"
                               :class="{'bg-base-200': filter.laporan === 'Diproses'}">Diproses</a></li>
                        <li><a @click.prevent="applyFilter('laporan', 'Selesai')"
                               :class="{'bg-base-200': filter.laporan === 'Selesai'}">Selesai</a></li>
                        <li><a @click.prevent="applyFilter('laporan', 'Ditolak')"
                               :class="{'bg-base-200': filter.laporan === 'Ditolak'}">Ditolak</a></li>

                        {{-- [MODIFIKASI] Opsi filter perbaikan disesuaikan --}}
                        <li class="menu-title"><span>Status Perbaikan</span></li>
                        <li><a @click.prevent="applyFilter('perbaikan', 'Menunggu')"
                               :class="{'bg-base-200': filter.perbaikan === 'Menunggu'}">Menunggu</a></li>
                        <li><a @click.prevent="applyFilter('perbaikan', 'Diproses')"
                               :class="{'bg-base-200': filter.perbaikan === 'Diproses'}">Diproses</a></li>
                        <li><a @click.prevent="applyFilter('perbaikan', 'Selesai')"
                               :class="{'bg-base-200': filter.perbaikan === 'Selesai'}">Selesai</a></li>
                        <li><a @click.prevent="applyFilter('perbaikan', 'Belum Ada')"
                               :class="{'bg-base-200': filter.perbaikan === 'Belum Ada'}">Belum Ada</a></li>
                        <li><a @click.prevent="applyFilter('perbaikan', 'Tidak Ada')"
                               :class="{'bg-base-200': filter.perbaikan === 'Tidak Ada'}">Tidak Ada</a></li>

                        <li>
                            <div class="divider my-1"></div>
                        </li>
                        <li><a @click.prevent="resetFilters()">Reset Semua Filter</a></li>
                    </ul>
                </div>
            </div>

            <div x-show="loading" class="flex justify-center items-center py-10">
                <span class="loading loading-spinner loading-lg text-primary"></span>
            </div>

            <div x-show="!loading" x-cloak>
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="table w-full">
                        <thead class="bg-base-200 text-base-content">
                        <tr class="text-center">
                            <th class="w-[15%] text-left">Kode Laporan</th>
                            <th class="w-[30%] text-left">Fasilitas / Pelapor</th>
                            <th class="w-[15%]">Status Laporan</th>
                            <th class="w-[15%]">Status Perbaikan</th>
                            <th class="w-[10%]">Rating</th>
                            <th class="w-[15%]">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-if="paginatedLaporan.length === 0">
                            <tr>
                                <td colspan="6" class="py-10">
                                    <div class="flex flex-col items-center justify-center text-gray-400 gap-3">
                                        <i class="bi bi-folder-x text-5xl"></i>
                                        <p class="text-base font-medium">Data Tidak Ditemukan</p>
                                        <p class="text-sm">Coba ubah kata kunci pencarian atau filter Anda.</p>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <template x-for="(laporan, index) in paginatedLaporan" :key="laporan.id">
                            <tr :class="index % 2 === 0 ? 'bg-white' : 'bg-base-200'">
                                <td class="font-mono text-left" x-text="laporan.kode"></td>
                                <td class="text-left whitespace-nowrap overflow-hidden text-ellipsis">
                                    <div class="font-medium mb-1" x-text="laporan.fasilitas"
                                         :title="laporan.fasilitas"></div>
                                    <div class="text-xs text-gray-500" x-text="'Oleh: ' + laporan.pelapor"></div>
                                </td>
                                <td class="text-center">
                                        <span
                                            class="inline-flex items-center justify-center gap-2 w-32 h-7 px-2 rounded-full text-sm font-medium"
                                            :class="badgeStyle(laporan.statusLaporan)">
                                            <span x-html="badgeIcon(laporan.statusLaporan)"></span>
                                            <span x-text="laporan.statusLaporan"></span>
                                        </span>
                                </td>
                                <td class="text-center">
                                        <span
                                            class="inline-flex items-center justify-center gap-2 w-32 h-7 px-2 rounded-full text-sm font-medium"
                                            :class="badgeStyle(laporan.statusPerbaikan)">
                                            <span x-html="badgeIconPerbaikan(laporan.statusPerbaikan)"></span>
                                            <span x-text="laporan.statusPerbaikan"></span>
                                        </span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-0.5 text-yellow-400"
                                         x-show="laporan.rating > 0">
                                        <template x-for="i in 5">
                                            <i class="bi" :class="i <= laporan.rating ? 'bi-star-fill' : 'bi-star'"></i>
                                        </template>
                                    </div>
                                    <span x-show="laporan.rating === 0" class="text-xs text-gray-400">N/A</span>
                                </td>
                                <td class="text-center">
                                    <a :href="`{{ route('sarpra.laporan.history_laporan_detail', '') }}/${laporan.id}`"
                                       class="btn btn-sm btn-outline btn-primary flex items-center gap-1 justify-center whitespace-nowrap">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex items-center justify-between flex-wrap gap-2">
                    <p class="text-sm text-gray-500">
                        Menampilkan <span class="font-medium"
                                          x-text="Math.min((page - 1) * perPage + 1, filteredLaporan.length)"></span>
                        – <span class="font-medium" x-text="Math.min(page * perPage, filteredLaporan.length)"></span>
                        dari <span class="font-medium" x-text="filteredLaporan.length"></span> hasil
                    </p>
                    <div class="join" x-show="totalPages > 1">
                        <template x-for="p in pages" :key="p">
                            <button @click="page = p" class="join-item btn btn-sm" :class="{'btn-active': page === p}"
                                    x-text="p"></button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('skrip')
    <script>
        function historyTable() {
            return {
                loading: true,
                search: '',
                page: 1,
                perPage: 7,
                filter: {
                    laporan: '',
                    perbaikan: ''
                },
                allLaporan: [],

                init() {
                    this.allLaporan = @json($historyData);
                    this.loading = false;
                    console.log(this.allLaporan);
                },

                get filteredLaporan() {
                    if (!this.search && !this.filter.laporan && !this.filter.perbaikan) {
                        return this.allLaporan;
                    }
                    const keywords = this.search.toLowerCase().trim().split(' ').filter(k => k);
                    return this.allLaporan.filter(laporan => {
                        const searchableString = [
                            laporan.kode,
                            laporan.fasilitas,
                            laporan.pelapor,
                            laporan.statusLaporan,
                            laporan.statusPerbaikan
                        ].join(' ').toLowerCase();
                        const searchMatch = keywords.every(keyword => searchableString.includes(keyword));
                        const laporanMatch = !this.filter.laporan || laporan.statusLaporan === this.filter.laporan;
                        const perbaikanMatch = !this.filter.perbaikan || laporan.statusPerbaikan === this.filter.perbaikan;
                        return searchMatch && laporanMatch && perbaikanMatch;
                    });
                },

                get paginatedLaporan() {
                    return this.filteredLaporan.slice((this.page - 1) * this.perPage, this.page * this.perPage);
                },

                get totalPages() {
                    return Math.ceil(this.filteredLaporan.length / this.perPage);
                },

                get pages() {
                    let pages = [];
                    for (let i = 1; i <= this.totalPages; i++) {
                        pages.push(i);
                    }
                    return pages;
                },

                applyFilter(type, value) {
                    this.filter[type] = (this.filter[type] === value) ? '' : value;
                    this.page = 1;
                },

                resetFilters() {
                    this.filter.laporan = '';
                    this.filter.perbaikan = '';
                    this.search = '';
                    this.page = 1;
                },

                // [MODIFIKASI] badgeStyle diperbarui untuk mencakup semua status
                badgeStyle(status) {
                    return {
                        'Menunggu': 'bg-amber-100 text-amber-800 border border-amber-200',
                        'Diterima': 'bg-sky-100 text-sky-800 border border-sky-200',
                        'Diproses': 'bg-blue-100 text-blue-800 border border-blue-200',
                        'Selesai': 'bg-green-100 text-green-800 border border-green-200',
                        'Ditolak': 'bg-red-100 text-red-800 border border-red-200',
                        // Status 'non-database'
                        'Tidak Ada': 'bg-gray-200 text-gray-800 border border-gray-300',
                        'Belum Ada': 'bg-gray-200 text-gray-800 border border-gray-300',
                    }[status] || 'bg-gray-100 text-gray-800 border border-gray-200';
                },

                badgeIcon(status) {
                    return {
                        'Menunggu': '<i class=\'bi bi-hourglass\'></i>',
                        'Diterima': '<i class=\'bi bi-check2-circle\'></i>',
                        'Diproses': '<i class=\'bi bi-gear\'></i>',
                        'Selesai': '<i class=\'bi bi-check-circle\'></i>',
                        'Ditolak': '<i class=\'bi bi-x-circle\'></i>',
                    }[status] || '';
                },

                // [MODIFIKASI] badgeIconPerbaikan disesuaikan
                badgeIconPerbaikan(status) {
                    return {
                        'Menunggu': '<i class=\'bi bi-hourglass-split\'></i>',
                        'Diproses': '<i class=\'bi bi-tools\'></i>',
                        'Selesai': '<i class=\'bi bi-wrench-adjustable-circle\'></i>',
                        'Tidak Ada': '<i class=\'bi bi-slash-circle\'></i>',
                        'Belum Ada': '<i class=\'bi bi-slash-circle\'></i>',
                    }[status] || '';
                }
            }
        }
    </script>
@endpush
