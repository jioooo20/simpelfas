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
            @if (in_array(Auth::user()->role_id, ['1']))
                <li>
                    <a href="{{ route('admin') }}" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <div class="w-6 text-center">
                            <i class="fa-solid fa-gauge group-hover:text-primary transition-transform duration-200"></i>
                        </div>
                        <span class="sidebar-text text-md">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.user') }}"
                        class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <div class="w-6 text-center">
                            <i class="fa-solid fa-users group-hover:text-primary transition-transform duration-200"></i>
                        </div>
                        <span class="sidebar-text text-md">Kelola Pengguna</span>
                    </a>
                </li>
                <li x-data="{ open: false }">
                    <a href="#" @click="open = ! open"
                        class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <div class="w-6 text-center">
                            <i
                                class="fa-solid fa-folder group-hover:text-primary transition-transform duration-200"></i>
                        </div>
                        <span class="sidebar-text text-md">Manajemen</span>
                        <i class="fa-solid fa-chevron-down ml-auto transition-transform duration-200"
                            :class="{ 'rotate-180': open }"></i>
                    </a>
                    <ul x-show="open" class="space-y-2 mt-2 ml-6">
                        <li>
                            <a href="{{ route('admin.gedung') }}"
                                class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                                <div class="w-6 text-center">
                                    <i
                                        class="fa-solid fa-file-invoice group-hover:text-primary transition-transform duration-200"></i>
                                </div>
                                <span class="sidebar-text text-md">Data Gedung</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.fasilitas') }}"
                                class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                                <div class="w-6 text-center">
                                    <i
                                        class="fa-solid fa-file-invoice group-hover:text-primary transition-transform duration-200"></i>
                                </div>
                                <span class="sidebar-text text-md">Fasilitas Kampus</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <div class="w-6 text-center">
                            <i
                                class="fa-solid fa-file-invoice group-hover:text-primary transition-transform duration-200"></i>
                        </div>
                        <span class="sidebar-text text-md">Prioritas Perbaikan</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <div class="w-6 text-center">
                            <i
                                class="fa-solid fa-file-contract group-hover:text-primary transition-transform duration-200"></i>
                        </div>
                        <span class="sidebar-text text-md">Laporan Kerusakan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('laporan.index') }}" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <div class="w-6 text-center">
                            <i
                                class="fa-solid fa-chart-simple group-hover:text-primary transition-transform duration-200"></i>
                        </div>
                        <span class="sidebar-text text-md">Laporan & Statistik Sistem</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <div class="w-6 text-center">
                            <i
                                class="fa-solid fa-calendar-days group-hover:text-primary transition-transform duration-200"></i>
                        </div>
                        <span class="sidebar-text text-md">Periode</span>
                    </a>
                </li>
            @endif

                {{-- sarpra --}}
                @if (in_array(Auth::user()->role_id, ['2']))
                    <li>
                        <a href="{{ route('sarpra') }}"
                           class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                            <i class="bi bi-grid-fill text-gray-500 group-hover:text-primary text-lg w-6 text-center"></i>
                            <span class="sidebar-text text-[0.925rem]">Dasbor</span>
                        </a>
                    </li>

                    <li>
                        <a href="#"
                           class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                            <i class="bi bi-file-earmark-text text-gray-500 group-hover:text-primary text-lg w-6 text-center"></i>
                            <span class="sidebar-text text-[0.925rem]">Laporan Kerusakan Fasilitas</span>
                        </a>
                    </li>

                    {{-- Submenu: Analisis dan Laporan (Expandable) --}}
                    @php
                        $isSubmenuOpen = request()->routeIs('statistik-fasilitas', 'frekuensi-perbaikan', 'kepuasan-pengguna', 'perencanaan-pemeliharaan');
                    @endphp

                    <li x-data="{ open: {{ $isSubmenuOpen ? 'true' : 'false' }} }" x-init="open = {{ $isSubmenuOpen ? 'true' : 'false' }}">
                        <a href="#" @click.prevent="open = !open"
                           class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                            <i class="bi bi-bar-chart-fill text-gray-500 group-hover:text-primary text-lg w-6 text-center"></i>
                            <span class="sidebar-text text-[0.925rem]">Analisis & Laporan</span>
                            <i class="bi bi-chevron-down ml-auto transform transition-transform duration-300"
                               :class="{ 'rotate-0': open, '-rotate-90': !open }"></i>
                        </a>

                        <ul x-show="open"
                            x-cloak
                            x-transition:enter="transition-all ease-out duration-300"
                            x-transition:enter-start="opacity-0 max-h-0"
                            x-transition:enter-end="opacity-100 max-h-96"
                            x-transition:leave="transition-all ease-in duration-300"
                            x-transition:leave-start="opacity-100 max-h-96"
                            x-transition:leave-end="opacity-0 max-h-0"
                            class="space-y-2 mt-2 ml-6 overflow-hidden">

                            <li>
                                <a href="{{ route('statistik-fasilitas') }}"
                                   class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group
                                    {{ request()->routeIs('statistik-fasilitas') ? 'bg-gray-200 text-gray-800 font-semibold' : '' }}">
                                    <i class="bi bi-graph-up text-gray-500 group-hover:text-primary text-lg w-6 text-center"></i>
                                    <span class="sidebar-text text-[0.925rem]">Statistik Fasilitas</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('frekuensi-perbaikan') }}"
                                   class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group
                                    {{ request()->routeIs('frekuensi-perbaikan') ? 'bg-gray-200 text-gray-800 font-semibold' : '' }}">
                                    <i class="bi bi-arrow-repeat text-gray-500 group-hover:text-primary text-lg w-6 text-center"></i>
                                    <span class="sidebar-text text-[0.925rem]">Frekuensi Perbaikan</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('kepuasan-pengguna') }}"
                                   class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group
                                    {{ request()->routeIs('kepuasan-pengguna') ? 'bg-gray-200 text-gray-800 font-semibold' : '' }}">
                                    <i class="bi bi-emoji-smile text-gray-500 group-hover:text-primary text-lg w-6 text-center"></i>
                                    <span class="sidebar-text text-[0.925rem]">Kepuasan Pengguna</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('perencanaan-pemeliharaan') }}"
                                   class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group
                                    {{ request()->routeIs('perencanaan-pemeliharaan') ? 'bg-gray-200 text-gray-800 font-semibold' : '' }}">
                                    <i class="bi bi-tools text-gray-500 group-hover:text-primary text-lg w-6 text-center"></i>
                                    <span class="sidebar-text text-[0.925rem]">Perencanaan Pemeliharaan</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                @endif

                {{-- teknisi --}}
            @if (in_array(Auth::user()->role_id, ['3']))
                <li>
                    <a href="{{ route('teknisi') }}"
                        class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <div class="w-6 text-center">
                            <i
                                class="fa-solid fa-screwdriver-wrench group-hover:text-primary transition-transform duration-200"></i>
                        </div>
                        <span class="sidebar-text text-md">Perbaikan Fasilitas</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <div class="w-6 text-center">
                            <i
                                class="fa-solid fa-history group-hover:text-primary transition-transform duration-200"></i>
                        </div>
                        <span class="sidebar-text text-md">Riwayat Perbaikan</span>
                    </a>
                </li>
            @endif

            {{-- warga polinema --}}
            @if (in_array(Auth::user()->role_id, ['4', '5', '6']))
                <li>
                    <a href="{{ route('users') }}"
                        class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <div class="w-6 text-center">
                            <i
                                class="fa-solid fa-file-circle-plus group-hover:text-primary transition-transform duration-200"></i>
                        </div>
                        <span class="sidebar-text text-md">Buat Laporan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('status-laporan') }}"
                        class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <div class="w-6 text-center">
                            <i
                                class="fa-solid fa-clipboard-check group-hover:text-primary transition-transform duration-200"></i>
                        </div>
                        <span class="sidebar-text text-md">Status Laporan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('users.feedback') }}" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <i
                            class="fa-solid fa-comments w-6 text-center group-hover:text-primary transition-transform duration-200"></i>
                        <span class="sidebar-text">Umpan Balik</span>
                    </a>
                </li>
            @endif

            <li>
                <a href="{{ route('keluar') }}"
                    class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 text-red-500 group"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i
                        class="fa-solid fa-sign-out-alt w-6 text-center group-hover:scale-110 transition-transform duration-200"></i>
                    <span class="sidebar-text">Logout</span>
                </a>
                <form id="logout-form" action="{{ route('keluar') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>
</div>

@push('css')
    <style>
        [x-cloak] { display: none !important; }

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
