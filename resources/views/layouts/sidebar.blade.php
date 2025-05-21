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
                    <a href="{{ route('admin') }}"
                        class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <i class="fa-solid fa-gauge w-6 text-center group-hover:text-primary transition-transform duration-200"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li x-data="{ open: false }">
                    <a href="#" @click="open = ! open"
                        class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <i class="fa-solid fa-user w-6 text-center group-hover:text-primary transition-transform duration-200"></i>
                        <span class="sidebar-text">Pengelolaan User</span>
                        <i class="fa-solid fa-chevron-down ml-auto transition-transform duration-200"
                            :class="{ 'rotate-180': open }"></i>
                    </a>
                    <ul x-show="open" class="space-y-2 mt-2 ml-6">
                        <li>
                            <a href="{{ route('admin.role') }}"
                                class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                                <i class="fa-solid fa-users-cog w-6 text-center group-hover:text-primary transition-transform duration-200"></i>
                                <span class="sidebar-text text-sm">Hak Akses</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.user') }}"
                                class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                                <i class="fa-solid fa-user-plus w-6 text-center group-hover:text-primary transition-transform duration-200"></i>
                                <span class="sidebar-text text-sm">Pengguna</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li x-data="{ open: false }">
                    <a href="#" @click="open = ! open"
                        class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <i class="fa-solid fa-folder w-6 text-center group-hover:text-primary transition-transform duration-200"></i>
                        <span class="sidebar-text">Manajemen</span>
                        <i class="fa-solid fa-chevron-down ml-auto transition-transform duration-200"
                            :class="{ 'rotate-180': open }"></i>
                    </a>
                    <ul x-show="open" class="space-y-2 mt-2 ml-6">
                        <li>
                            <a href="{{ route('admin.gedung') }}"
                                class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                                <i class="fa-solid fa-file-invoice w-6 text-center group-hover:text-primary transition-transform duration-200"></i>
                                <span class="sidebar-text text-sm">Data Gedung</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                                <i class="fa-solid fa-file-invoice w-6 text-center group-hover:text-primary transition-transform duration-200"></i>
                                <span class="sidebar-text text-sm">Fasilitas Kampus</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <i class="fa-solid fa-file-invoice w-6 text-center group-hover:text-primary transition-transform duration-200"></i>
                        <span class="sidebar-text text-sm">Prioritas Perbaikan</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <i class="fa-solid fa-file-contract w-6 text-center group-hover:text-primary transition-transform duration-200"></i>
                        <span class="sidebar-text text-sm">Laporan Kerusakan</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <i class="fa-solid fa-chart-simple w-6 text-center group-hover:text-primary transition-transform duration-200"></i>
                        <span class="sidebar-text text-sm">Laporan & Statistik Sistem</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <i class="fa-solid fa-calendar-days w-6 text-center group-hover:text-primary transition-transform duration-200"></i>
                        <span class="sidebar-text">Periode</span>
                    </a>
                </li>
            @endif

            {{-- sarpra --}}
            @if (in_array(Auth::user()->role_id, ['2']))
                <li>
                    <a href="{{ route('sarpra') }}"
                        class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-6 h-6 text-center text-gray-500 group-hover:text-primary transition-colors duration-200"
                            viewBox="0 0 24 24" fill="currentColor">
                          <rect x="3"  y="3"  width="8" height="8" rx="1"/>
                          <rect x="13" y="3"  width="8" height="8" rx="1"/>
                          <rect x="3"  y="13" width="8" height="8" rx="1"/>
                          <rect x="13" y="13" width="8" height="8" rx="1"/>
                        </svg>
                        <span class="sidebar-text">Dasbor</span>
                    </a>
                </li>
            @endif

            {{-- teknisi --}}
            @if (in_array(Auth::user()->role_id, ['3']))
                <li>
                    <a href="{{ route('teknisi') }}"
                        class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <i class="fa-solid fa-screwdriver-wrench w-6 text-center group-hover:text-primary transition-transform duration-200"></i>
                        <span class="sidebar-text">Perbaikan Fasilitas</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <i class="fa-solid fa-history w-6 text-center group-hover:text-primary transition-transform duration-200"></i>
                        <span class="sidebar-text">Riwayat Perbaikan</span>
                    </a>
                </li>
            @endif

            {{-- warga polinema --}}
            @if (in_array(Auth::user()->role_id, ['4', '5', '6']))
                <li>
                    <a href="{{ route('users') }}"
                        class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <i class="fa-solid fa-file-circle-plus w-6 text-center group-hover:text-primary transition-transform duration-200"></i>
                        <span class="sidebar-text">Buat Laporan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('status-laporan') }}"
                        class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <i class="fa-solid fa-clipboard-check w-6 text-center group-hover:text-primary transition-transform duration-200"></i>
                        <span class="sidebar-text">Status Laporan</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                        <i class="fa-solid fa-comments w-6 text-center group-hover:text-primary transition-transform duration-200"></i>
                        <span class="sidebar-text">Umpan Balik</span>
                    </a>
                </li>
            @endif

            <li>
                <a href="{{ route('keluar') }}"
                    class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 text-red-500 group"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa-solid fa-sign-out-alt w-6 text-center group-hover:scale-110 transition-transform duration-200"></i>
                    <span class="sidebar-text">Logout</span>
                </a>
                <form id="logout-form" action="{{ route('keluar') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>
</div>

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
