<div id="sidebar"
     class="fixed top-0 left-0 z-40 flex h-screen w-64 flex-col bg-base-100 p-4 text-base-content transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0">
    <div class="mb-4 flex items-center justify-between">
        <div class="my-1 flex items-center gap-2">
            <span class="ml-2 text-2xl judul text-content-accent">Simpelfas</span>
        </div>
        <button onclick="toggleSidebar()" class="p-2 rounded-full hover:bg-base-300 lg:hidden">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto">
        <ul class="space-y-2">

            <!-- Admin -->
            @if (in_array(Auth::user()->role_id, ['1']))
                {{-- Dashboard --}}
                <li>
                    <a href="{{ route('admin') }}"
                       class="flex items-center gap-3 p-2 rounded-md hover:bg-base-200 group {{-- Modifikasi --}}
           {{ request()->routeIs('admin') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center">
                            {{-- Modifikasi --}}
                            <i class="fa-solid fa-gauge text-lg group-hover:text-primary transition-transform duration-200
                   {{ request()->routeIs('admin') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                        </div>
                        {{-- Modifikasi --}}
                        <span class="sidebar-text text-sm">Dasbor</span>
                    </a>
                </li>

                {{-- Kelola Pengguna --}}
                <li>
                    <a href="{{ route('admin.user') }}"
                       class="flex items-center gap-3 p-2 rounded-md hover:bg-base-200 group
           {{ request()->routeIs('admin.user') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center">
                            <i class="fa-solid fa-users text-lg group-hover:text-primary transition-transform duration-200
                   {{ request()->routeIs('admin.user') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                        </div>
                        <span class="sidebar-text text-sm">Kelola Pengguna</span>
                    </a>
                </li>

                {{-- Manajemen (Dengan Submenu) --}}
                @php
                    $isManajemenActive = request()->routeIs('admin.gedung', 'admin.fasilitas', 'admin.barang');
                    $isManajemenActiveJs = $isManajemenActive ? 'true' : 'false';
                @endphp
                <li x-data="{ open: false }"
                    x-init="
            open = JSON.parse(localStorage.getItem('manajemenOpen') || {{ $isManajemenActiveJs }});
            $watch('open', val => localStorage.setItem('manajemenOpen', JSON.stringify(val)));
        ">
                    {{-- Link Parent untuk membuka submenu --}}
                    <a href="#" @click.prevent="open = ! open"
                       class="flex items-center gap-3 p-2 rounded-md hover:bg-base-200 group"
                       :class="{ 'bg-slate-100 font-semibold': open || {{ $isManajemenActiveJs }} }">
                        <div class="w-6 text-center">
                            <i class="fa-solid fa-folder text-lg group-hover:text-primary transition-transform duration-200"
                               :class="(open || {{ $isManajemenActiveJs }}) ? 'text-primary' : 'text-gray-500'"></i>
                        </div>
                        <span class="sidebar-text text-sm">Manajemen</span>
                        {{-- Ikon panah, ukurannya akan mengikuti text-sm dari parent --}}
                        <i class="fa-solid fa-chevron-down ml-auto transition-transform duration-200"
                           :class="{ 'rotate-180': open }"></i>
                    </a>

                    {{-- Daftar Submenu --}}
                    <ul x-show="open" x-transition class="space-y-2 mt-2 ml-6" style="display: none;">
                        {{-- Submenu: Data Gedung --}}
                        <li>
                            <a href="{{ route('admin.gedung') }}"
                               class="flex items-center gap-3 p-2 rounded-md hover:bg-base-200 group
                   {{ request()->routeIs('admin.gedung') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                                <div class="w-6 text-center">
                                    <i class="fa-solid fa-building text-lg group-hover:text-primary transition-transform duration-200
                           {{ request()->routeIs('admin.gedung') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                                </div>
                                <span class="sidebar-text text-sm">Data Gedung</span>
                            </a>
                        </li>
                        {{-- Submenu: Fasilitas Kampus --}}
                        <li>
                            <a href="{{ route('admin.fasilitas') }}"
                               class="flex items-center gap-3 p-2 rounded-md hover:bg-base-200 group
                   {{ request()->routeIs('admin.fasilitas') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                                <div class="w-6 text-center">
                                    <i class="fa-solid fa-chair text-lg group-hover:text-primary transition-transform duration-200
                           {{ request()->routeIs('admin.fasilitas') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                                </div>
                                <span class="sidebar-text text-sm">Fasilitas Kampus</span>
                            </a>
                        </li>
                        {{-- Submenu: Data Barang --}}
                        <li>
                            <a href="{{ route('admin.barang') }}"
                               class="flex items-center gap-3 p-2 rounded-md hover:bg-base-200 group
                   {{ request()->routeIs('admin.barang') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                                <div class="w-6 text-center">
                                    <i class="fa-solid fa-box text-lg group-hover:text-primary transition-transform duration-200
                           {{ request()->routeIs('admin.barang') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                                </div>
                                <span class="sidebar-text text-sm">Data Barang</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Laporan & Statistik Sistem --}}
                <li>
                    <a href="{{ route('laporan.index') }}"
                       class="flex items-center gap-3 p-2 rounded-md hover:bg-base-200 group
           {{ request()->routeIs('laporan.index') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center">
                            <i class="fa-solid fa-chart-simple text-lg group-hover:text-primary transition-transform duration-200
                   {{ request()->routeIs('laporan.index') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                        </div>
                        <span class="sidebar-text text-sm">Laporan & Statistik</span>
                    </a>
                </li>
            @endif

            <!-- Sarpra -->
            @if (in_array(Auth::user()->role_id, ['2']))
                <li>
                    {{-- Modifikasi: gap-3, text-sm, dan wrapper ikon --}}
                    <a href="{{ route('sarpra') }}"
                       class="flex items-center gap-3 p-2 rounded-md hover:bg-base-200 group
                 {{ request()->routeIs('sarpra') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-5 h-5 inline-block group-hover:text-primary transition-colors duration-200
                         {{ request()->routeIs('sarpra') ? 'text-sky-600' : 'text-gray-500' }}"
                                 viewBox="0 0 24 24" fill="currentColor">
                                <rect x="3" y="3" width="8" height="8" rx="1"/>
                                <rect x="13" y="3" width="8" height="8" rx="1"/>
                                <rect x="3" y="13" width="8" height="8" rx="1"/>
                                <rect x="13" y="13" width="8" height="8" rx="1"/>
                            </svg>
                        </div>
                        <span class="sidebar-text text-sm">Dasbor</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('sarpra.laporan-kerusakan-fasilitas') }}"
                       class="flex items-center gap-3 p-2 rounded-md hover:bg-base-200 group
                        {{ request()->routeIs('sarpra.laporan-kerusakan-fasilitas') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-5 h-5 inline-block group-hover:text-primary transition-transform duration-200
                             {{ request()->routeIs('sarpra.laporan-kerusakan-fasilitas') ? 'text-sky-600' : 'text-gray-500' }}"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
                                <path d="M13 2v7h7"/>
                                <path d="M9 13h6"/>
                                <path d="M9 17h6"/>
                                <path d="M9 9h1"/>
                            </svg>
                        </div>
                        <span class="sidebar-text text-sm">Laporan Kerusakan Fasilitas</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('sarpra.rekomendasi-prioritas-perbaikan') }}"
                       class="flex items-center gap-3 p-2 rounded-md hover:bg-base-200 group
                 {{ request()->routeIs('sarpra.rekomendasi-prioritas-perbaikan') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center flex-shrink-0">
                            <i class="fa-solid fa-sliders text-lg group-hover:text-primary transition-transform duration-200
                       {{ request()->routeIs('sarpra.rekomendasi-prioritas-perbaikan') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                        </div>
                        <span class="sidebar-text text-sm">Rekomendasi Prioritas</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('statistik-fasilitas') }}"
                       class="flex items-center gap-3 p-2 rounded-md hover:bg-base-200 group
            {{ request()->routeIs('statistik-fasilitas') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center">
                            <i class="bi bi-bar-chart-fill text-lg group-hover:text-sky-600
                    {{ request()->routeIs('statistik-fasilitas') ? 'text-sky-600' : 'text-gray-500' }}">
                            </i>
                        </div>
                        <span class="sidebar-text text-sm">Analisis Statistik Fasilitas</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('penugasan-perbaikan') }}"
                       class="flex items-center gap-3 p-2 rounded-md hover:bg-base-200 group
            {{ request()->routeIs('penugasan-perbaikan') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center">
                            <i class="bi bi-clipboard-check-fill text-lg group-hover:text-sky-600
                    {{ request()->routeIs('penugasan-perbaikan') ? 'text-sky-600' : 'text-gray-500' }}">
                            </i>
                        </div>
                        <span class="sidebar-text text-sm">Penugasan Perbaikan</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('sarpra.history-laporan') }}"
                       class="flex items-center gap-3 p-2 rounded-md hover:bg-base-200 group
            {{ request()->routeIs('sarpra.history-laporan') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center">
                            <i class="fa-solid fa-history text-lg group-hover:text-sky-600
                    {{ request()->routeIs('sarpra.history-laporan') ? 'text-sky-600' : 'text-gray-500' }}">
                            </i>
                        </div>
                        <span class="sidebar-text text-sm">Riwayat Laporan</span>
                    </a>
                </li>
            @endif

            <!-- Teknisi -->
            @if (in_array(Auth::user()->role_id, ['3']))
                {{-- Perbaikan Fasilitas --}}
                <li>
                    <a href="{{ route('teknisi') }}"
                       class="flex items-center gap-3 p-2 rounded-md hover:bg-base-200 group {{-- Modifikasi --}}
            {{ request()->routeIs('teknisi') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center">
                            {{-- Modifikasi --}}
                            <i class="fa-solid fa-screwdriver-wrench text-lg group-hover:text-primary transition-transform duration-200
                    {{ request()->routeIs('teknisi') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                        </div>
                        {{-- Modifikasi --}}
                        <span class="sidebar-text text-sm">Perbaikan Fasilitas</span>
                    </a>
                </li>

                {{-- Rekomendasi Biaya Perbaikan --}}
                <li>
                    <a href="{{ route('teknisi.rekomendasi-biaya-perbaikan') }}"
                       class="flex items-center gap-3 p-2 rounded-md hover:bg-base-200 group {{-- Modifikasi --}}
            {{ request()->routeIs('teknisi.rekomendasi-biaya-perbaikan') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center">
                            {{-- Modifikasi --}}
                            <i class="fa-solid fa-hand-holding-dollar text-lg group-hover:text-primary transition-transform duration-200 hover:scale-110
                    {{ request()->routeIs('teknisi.rekomendasi-biaya-perbaikan') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                        </div>
                        {{-- Modifikasi --}}
                        <span class="sidebar-text text-sm">Rekomendasi Biaya Perbaikan</span>
                    </a>
                </li>

                {{-- Riwayat Perbaikan --}}
                <li>
                    <a href="{{ route('riwayat-perbaikan') }}"
                       class="flex items-center gap-3 p-2 rounded-md hover:bg-base-200 group
            {{ request()->routeIs('riwayat-perbaikan') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center">
                            <i class="fa-solid fa-history text-lg group-hover:text-primary transition-transform duration-200
                    {{ request()->routeIs('riwayat-perbaikan') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                        </div>
                        <span class="sidebar-text text-sm">Riwayat Perbaikan</span>
                    </a>
                </li>
            @endif

            <!-- User -->
            @if (in_array(Auth::user()->role_id, ['4', '5', '6']))
                <li>
                    <a href="{{ route('users') }}"
                       class="flex items-center gap-3 p-2 rounded-md hover:bg-base-200 group {{-- Modifikasi: gap --}}
                         {{ request()->routeIs('users') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center">
                            {{-- Modifikasi: Ukuran ikon --}}
                            <i class="fa-solid fa-file-circle-plus text-lg group-hover:text-primary transition-transform duration-200
                         {{ request()->routeIs('users') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                        </div>
                        {{-- Modifikasi: Ukuran teks --}}
                        <span class="sidebar-text text-sm">Buat Laporan</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('status-laporan') }}"
                       class="flex items-center gap-3 p-2 rounded-md hover:bg-base-200 group {{-- Modifikasi: gap --}}
                        {{ request()->routeIs('status-laporan') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center">
                            {{-- Modifikasi: Ukuran ikon --}}
                            <i class="fa-solid fa-clipboard-check text-lg group-hover:text-primary transition-transform duration-200
                          {{ request()->routeIs('status-laporan') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                        </div>
                        {{-- Modifikasi: Ukuran teks --}}
                        <span class="sidebar-text text-sm">Status Laporan</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('users.feedback') }}"
                       class="flex items-center gap-3 p-2 rounded-md hover:bg-base-200 group {{-- Modifikasi: gap --}}
                      {{ request()->routeIs('users.feedback') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        {{-- Modifikasi: Ukuran ikon --}}
                        <i class="fa-solid fa-comments w-6 text-center text-lg group-hover:text-primary transition-transform duration-200
           {{ request()->routeIs('users.feedback') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                        {{-- Modifikasi: Ukuran teks --}}
                        <span class="sidebar-text text-sm">Umpan Balik</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</div>

@push('css')
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush

@push('skrip')
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const texts = document.querySelectorAll('.sidebar-text');
            const mainContent = document.getElementById('main-content');
            const header = document.getElementById('header');
            const sidebarTitleContainer = document.querySelector('#sidebar .flex.items-center.gap-2');
            const navLinks = document.querySelectorAll('#sidebar nav ul li > a');
            const chevronIcons = document.querySelectorAll('#sidebar nav ul li > a i.fa-chevron-down');
            const toggleButtonContainer = document.getElementById('toggle-button-container');
            const submenus = document.querySelectorAll('#sidebar nav ul li ul');

            sidebar.classList.toggle('w-64');
            sidebar.classList.toggle('w-20');

            mainContent.classList.toggle('ml-64');
            mainContent.classList.toggle('ml-20');

            header.classList.toggle('ml-64');
            header.classList.toggle('ml-20');

            texts.forEach(text => {
                text.classList.toggle('hidden');
            });

            sidebarTitleContainer.classList.toggle('hidden');

            navLinks.forEach(link => {
                link.classList.toggle('justify-start');
                link.classList.toggle('justify-center');
            });

            chevronIcons.forEach(icon => {
                icon.classList.toggle('hidden');
            });

            if (sidebar.classList.contains('w-20')) {
                submenus.forEach(submenu => {
                    submenu.classList.add('hidden');
                });

                if (window.Alpine) {
                    document.querySelectorAll('[x-data]').forEach(el => {
                        if (el.__x && el.__x.$data.hasOwnProperty('open')) {
                            el.__x.$data.open = false;
                        }
                    });
                }
            } else {
                submenus.forEach(submenu => {
                    submenu.classList.remove('hidden');
                });
            }

            if (sidebar.classList.contains('w-20')) {
                toggleButtonContainer.classList.remove('justify-end');
                toggleButtonContainer.classList.add('justify-center');
            } else {
                toggleButtonContainer.classList.remove('justify-center');
                toggleButtonContainer.classList.add('justify-end');
            }
        }
    </script>
@endpush
