{{-- sidebar --}}
<div id="sidebar"
    class="transition-all duration-300 bg-base-100 text-base-content w-64 h-screen p-4 flex flex-col fixed top-0">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2 my-1">
            <img class="h-8 w-8 rounded-full"
            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama) }}&background=4338ca&color=fff"
            alt="{{ Auth::user()->nama }}" loading="lazy">
            <span class="text-md judul sidebar-text">{{ Str::limit(Auth::user()->nama, 13) }}</span>
        </div>
        <div id="toggle-button-container" class="flex justify-end w-16">
            <button onclick="toggleSidebar()" class="text-base-content hover:text-primary">
                <i class="fa-solid fa-bars p-2.5"></i>
            </button>
        </div>
    </div>
    <nav class="flex-1">
        <ul class="space-y-2">
            @if(in_array(Auth::user()->role_id, ['1']))
            <li>
                <a href="{{ route('admin') }}" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                    <i class="fa-solid fa-gauge group-hover:text-primary transition-transform duration-200"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
            <li x-data="{ open: false }">
                <a href="#" @click="open = ! open"
                    class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                    <i class="fa-solid fa-user group-hover:text-primary transition-transform duration-200"></i>
                    <span class="sidebar-text">Pengelolaan User</span>
                    <i class="fa-solid fa-chevron-down ml-auto transition-transform duration-200"
                        :class="{ 'rotate-180': open }"></i>
                </a>
                <ul x-show="open" class="space-y-2 mt-2 ml-6">
                    <li>
                        <a href="{{ route('admin.role') }}" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                            <i class="fa-solid fa-users-cog group-hover:text-primary transition-transform duration-200"></i>
                            <span class="sidebar-text text-sm">Hak Akses</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.user') }}" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                            <i class="fa-solid fa-user-plus group-hover:text-primary transition-transform duration-200"></i>
                            <span class="sidebar-text text-sm">Pengguna</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li x-data="{ open: false }">
                <a href="#" @click="open = ! open"
                    class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                    <i class="fa-solid fa-folder group-hover:text-primary transition-transform duration-200"></i>
                    <span class="sidebar-text">Manajemen</span>
                    <i class="fa-solid fa-chevron-down ml-auto transition-transform duration-200"
                        :class="{ 'rotate-180': open }"></i>
                </a>
                <ul x-show="open" class="space-y-2 mt-2 ml-6">
                    <li>
                        <a href="#" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                            <i class="fa-solid fa-file-invoice group-hover:text-primary transition-transform duration-200"></i>
                            <span class="sidebar-text text-sm">Prioritas Perbaikan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                            <i class="fa-solid fa-file-invoice group-hover:text-primary transition-transform duration-200"></i>
                            <span class="sidebar-text text-sm">Fasilitas Kampus</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                            <i class="fa-solid fa-file-invoice group-hover:text-primary transition-transform duration-200"></i>
                            <span class="sidebar-text text-sm">Gedung</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li x-data="{ open: false }">
                <a href="#" @click="open = ! open"
                    class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                    <i class="fa-solid fa-circle-exclamation group-hover:text-primary transition-transform duration-200"></i>
                    <span class="sidebar-text">Laporan</span>
                    <i class="fa-solid fa-chevron-down ml-auto transition-transform duration-200"
                        :class="{ 'rotate-180': open }"></i>
                </a>
                <ul x-show="open" class="space-y-2 mt-2 ml-6">
                    <li>
                        <a href="#" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                            <i class="fa-solid fa-file-contract group-hover:text-primary transition-transform duration-200"></i>
                            <span class="sidebar-text text-sm">Laporan Kerusakan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                            <i class="fa-solid fa-chart-simple group-hover:text-primary transition-transform duration-200"></i>
                            <span class="sidebar-text text-sm">Laporan & Statistik Sistem</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                    <i class="fa-solid fa-calendar-days group-hover:text-primary transition-transform duration-200"></i>
                    <span class="sidebar-text">Periode</span>
                </a>
            </li>
            @endif

            {{-- sarpra --}}
            @if(in_array(Auth::user()->role_id, ['2']))
            @endif

            {{-- teknisi --}}
            @if(in_array(Auth::user()->role_id, ['3']))
            @endif

            {{-- warga polinema --}}
            @if(in_array(Auth::user()->role_id, ['4']))
            <li>
                <a href="{{ route('users') }}" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                    <i class="fa-solid fa-gauge group-hover:text-primary transition-transform duration-200"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                    <i class="fa-solid fa-file-circle-plus w-5 text-center group-hover:text-primary transition-transform duration-200"></i>
                    <span class="sidebar-text">Buat Laporan</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                    <i class="fa-solid fa-clipboard-check w-5 text-center group-hover:text-primary transition-transform duration-200"></i>
                    <span class="sidebar-text">Status Laporan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('users.feedback')}}" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                    <i class="fa-solid fa-comments w-5 text-center group-hover:text-primary transition-transform duration-200"></i>
                    <span class="sidebar-text">Umpan Balik</span>
                </a>
            </li>
            @endif

            <li>
                <a href="{{ route('keluar') }}"
                    class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 text-red-500 group"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa-solid fa-sign-out-alt group-hover:scale-110 transition-transform duration-200"></i>
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
