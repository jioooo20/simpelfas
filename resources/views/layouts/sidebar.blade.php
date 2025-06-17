{{-- sidebar --}}
<div id="sidebar"
     class="transition-all duration-300 bg-gradient-to-b from-base-100 to-base-200 text-base-content w-64 h-screen p-4 flex flex-col fixed top-0 left-0">
    {{--  rounded-tr-xl rounded-br-xl ring-1 ring-inset ring-gray-200 shadow-inner shadow-black/10 ini garis vertikal --}}
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2 my-1">
            <span class="text-2xl judul ml-2 text-content-accent">Simpelfas</span>
        </div>
        {{-- matiin dl, lg g mood --}}
        {{-- <div id="toggle-button-container" class="flex justify-end w-16">
            <button onclick="toggleSidebar()" class="text-base-content hover:text-primary">
                <i class="fa-solid fa-bars p-2.5"></i>
            </button>
        </div> --}}
    </div>
    <nav class="flex-1">
        <ul class="space-y-2">

            <!-- Admin -->
            @if (in_array(Auth::user()->role_id, ['1']))
                {{-- Dashboard --}}
                <li>
                    <a href="{{ route('admin') }}"
                       class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group {{ request()->routeIs('admin') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center">
                            <i class="fa-solid fa-gauge group-hover:text-primary transition-transform duration-200 {{ request()->routeIs('admin') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                        </div>
                        <span class="sidebar-text text-md">Dashboard</span>
                    </a>
                </li>

                {{-- Kelola Pengguna --}}
                <li>
                    <a href="{{ route('admin.user') }}"
                       class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group {{ request()->routeIs('admin.user') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center">
                            <i class="fa-solid fa-users group-hover:text-primary transition-transform duration-200 {{ request()->routeIs('admin.user') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                        </div>
                        <span class="sidebar-text text-md">Kelola Pengguna</span>
                    </a>
                </li>

                {{-- Manajemen --}}
                @php
                    $isManajemenActive = request()->routeIs('admin.gedung', 'admin.fasilitas', 'admin.barang');
                    // Mengubah boolean menjadi string 'true' atau 'false' agar aman untuk JavaScript
                    $isManajemenActiveJs = $isManajemenActive ? 'true' : 'false';
                @endphp
                <li x-data="{ open: false }"
                    x-init="
            open = JSON.parse(localStorage.getItem('manajemenOpen') || {{ $isManajemenActiveJs }});
            $watch('open', val => localStorage.setItem('manajemenOpen', JSON.stringify(val)));
        ">
                    <a href="#" @click.prevent="open = ! open"
                       class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group"
                       :class="{ 'bg-slate-100 font-semibold': open || {{ $isManajemenActiveJs }} }">
                        <div class="w-6 text-center">
                            {{-- Ikon berubah warna jika section manajemen aktif atau sedang terbuka --}}
                            <i class="fa-solid fa-folder group-hover:text-primary transition-transform duration-200"
                               :class="(open || {{ $isManajemenActiveJs }}) ? 'text-primary' : 'text-gray-500'"></i>
                        </div>
                        <span class="sidebar-text text-md">Manajemen</span>
                        <i class="fa-solid fa-chevron-down ml-auto transition-transform duration-200"
                           :class="{ 'rotate-180': open }"></i>
                    </a>
                    <ul x-show="open" x-transition class="space-y-2 mt-2 ml-6" style="display: none;">
                        {{-- Submenu items --}}
                        <li>
                            <a href="{{ route('admin.gedung') }}"
                               class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group {{ request()->routeIs('admin.gedung') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                                <div class="w-6 text-center">
                                    <i class="fa-solid fa-file-invoice group-hover:text-primary transition-transform duration-200 {{ request()->routeIs('admin.gedung') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                                </div>
                                <span class="sidebar-text text-md">Data Gedung</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.fasilitas') }}"
                               class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group {{ request()->routeIs('admin.fasilitas') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                                <div class="w-6 text-center">
                                    <i class="fa-solid fa-file-invoice group-hover:text-primary transition-transform duration-200 {{ request()->routeIs('admin.fasilitas') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                                </div>
                                <span class="sidebar-text text-md">Fasilitas Kampus</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.barang') }}"
                               class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group {{ request()->routeIs('admin.barang') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                                <div class="w-6 text-center">
                                    <i class="fa-solid fa-file-invoice group-hover:text-primary transition-transform duration-200 {{ request()->routeIs('admin.barang') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                                </div>
                                <span class="sidebar-text text-md">Data Barang</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Laporan & Statistik Sistem --}}
                <li>
                    <a href="{{ route('laporan.index') }}"
                       class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group {{ request()->routeIs('laporan.index') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center">
                            <i class="fa-solid fa-chart-simple group-hover:text-primary transition-transform duration-200 {{ request()->routeIs('laporan.index') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                        </div>
                        <span class="sidebar-text text-md">Laporan & Statistik Sistem</span>
                    </a>
                </li>
            @endif

            <!-- Sarpra -->
            @if (in_array(Auth::user()->role_id, ['2']))
                <li>
                    <a href="{{ route('sarpra') }}"
                       class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group
                             {{ request()->routeIs('sarpra') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-6 h-6 text-center group-hover:text-primary transition-colors duration-200 flex-shrink-0
                                 {{ request()->routeIs('sarpra') ? 'text-sky-600' : 'text-gray-500' }}"
                             viewBox="0 0 24 24" fill="currentColor">
                            <rect x="3" y="3" width="8" height="8" rx="1"/>
                            <rect x="13" y="3" width="8" height="8" rx="1"/>
                            <rect x="3" y="13" width="8" height="8" rx="1"/>
                            <rect x="13" y="13" width="8" height="8" rx="1"/>
                        </svg>
                        <span class="sidebar-text">Dasbor</span>
                    </a>
                </li>

                {{-- Laporan Kerusakan Fasilitas: Logika active state ditambahkan --}}
                <li>
                    <a href="{{ route('sarpra.laporan-kerusakan-fasilitas') }}"
                       class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group {{-- [MODIFIKASI] --}}
                                    {{ request()->routeIs('sarpra.laporan-kerusakan-fasilitas') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-6 group-hover:text-primary transition-transform duration-200 {{-- [MODIFIKASI] --}}
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
                        <span class="sidebar-text text-md">Laporan Kerusakan Fasilitas</span>
                    </a>
                </li>

                {{-- Rekomendasi Prioritas Perbaikan: Logika active state ditambahkan --}}
                <li>
                    <a href="{{ route('sarpra.rekomendasi-prioritas-perbaikan') }}"
                       class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group {{-- [MODIFIKASI] --}}
                             {{ request()->routeIs('sarpra.rekomendasi-prioritas-perbaikan') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center flex-shrink-0">
                            <i class="fa-solid fa-sliders group-hover:text-primary transition-transform duration-200 {{-- [MODIFIKASI] --}}
                                   {{ request()->routeIs('sarpra.rekomendasi-prioritas-perbaikan') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                        </div>
                        <span class="sidebar-text text-md">Rekomendasi Prioritas Perbaikan</span>
                    </a>
                </li>


                {{-- Analisis & Laporan: TIDAK DIUBAH, SUDAH BENAR --}}
                <li>
                    <a href="{{ route('statistik-fasilitas') }}"
                       class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group
                    {{ request()->routeIs('statistik-fasilitas') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <i
                            class="bi bi-bar-chart-fill
                      {{ request()->routeIs('statistik-fasilitas') ? 'text-sky-600' : 'text-gray-500' }}
                      group-hover:text-sky-600 text-lg w-6 text-center">
                        </i>
                        <span class="sidebar-text text-[0.925rem]">Analisis Fasilitas</span>
                    </a>
                </li>

                {{-- Penugasan Perbaikan: TIDAK DIUBAH, SUDAH BENAR --}}
                <li>
                    <a href="{{ route('penugasan-perbaikan') }}"
                       class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group
                    {{ request()->routeIs('penugasan-perbaikan') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <i
                            class="bi bi-clipboard-check-fill
                      {{ request()->routeIs('penugasan-perbaikan') ? 'text-sky-600' : 'text-gray-500' }}
                      group-hover:text-sky-600 text-lg w-6 text-center">
                        </i>
                        <span class="sidebar-text text-[0.925rem]">Penugasan Perbaikan</span>
                    </a>
                </li>

                {{-- History Laporan: TIDAK DIUBAH, SUDAH BENAR --}}
                <li>
                    <a href="{{ route('sarpra.history-laporan') }}"
                       class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group
                {{ request()->routeIs('sarpra.history-laporan') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <i
                            class="fa-solid fa-history
                {{ request()->routeIs('sarpra.history-laporan') ? 'text-sky-600' : 'text-gray-500' }}
                  group-hover:text-sky-600 text-lg w-6 text-center">
                        </i>
                        <span class="sidebar-text text-[0.925rem]">History Laporan</span>
                    </a>
                </li>
            @endif

            <!-- Teknisi -->
            @if (in_array(Auth::user()->role_id, ['3']))
                {{-- Perbaikan Fasilitas --}}
                <li>
                    <a href="{{ route('teknisi') }}"
                       class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group
           {{ request()->routeIs('teknisi') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center">
                            <i class="fa-solid fa-screwdriver-wrench group-hover:text-primary transition-transform duration-200
                    {{ request()->routeIs('teknisi') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                        </div>
                        <span class="sidebar-text text-md">Perbaikan Fasilitas</span>
                    </a>
                </li>

                {{-- Riwayat Perbaikan --}}
                <li>
                    <a href="{{ route('riwayat-perbaikan') }}"
                       class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group
           {{ request()->routeIs('riwayat-perbaikan') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center">
                            <i class="fa-solid fa-history group-hover:text-primary transition-transform duration-200
                    {{ request()->routeIs('riwayat-perbaikan') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                        </div>
                        <span class="sidebar-text text-md">Riwayat Perbaikan</span>
                    </a>
                </li>
            @endif

            <!-- User -->
            @if (in_array(Auth::user()->role_id, ['4', '5', '6']))
                <li>
                    <a href="{{ route('users') }}"
                       class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group
                {{ request()->routeIs('users') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center">
                            <i class="fa-solid fa-file-circle-plus group-hover:text-primary transition-transform duration-200
                       {{ request()->routeIs('users') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                        </div>
                        <span class="sidebar-text text-md">Buat Laporan</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('status-laporan') }}"
                       class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group
                {{ request()->routeIs('status-laporan') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <div class="w-6 text-center">
                            <i class="fa-solid fa-clipboard-check group-hover:text-primary transition-transform duration-200
                       {{ request()->routeIs('status-laporan') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                        </div>
                        <span class="sidebar-text text-md">Status Laporan</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('users.feedback') }}"
                       class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group
                {{ request()->routeIs('users.feedback') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                        <i class="fa-solid fa-comments w-6 text-center group-hover:text-primary transition-transform duration-200
                      {{ request()->routeIs('users.feedback') ? 'text-sky-600' : 'text-gray-500' }}"></i>
                        <span class="sidebar-text text-md">Umpan Balik</span>
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
