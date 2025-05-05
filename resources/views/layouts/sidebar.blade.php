{{-- sidebar --}}
<div id="sidebar"
    class="transition-all duration-300 bg-base-100 text-base-content w-64 h-screen p-4 flex flex-col fixed top-0">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/default-profile.png') }}" alt="Profile Picture"
                class="w-8 h-8 rounded-full object-cover border-1 border-primary ring-2 ring-opacity-50 ring-primary">
            <span class="text-md judul sidebar-text">{{ Str::limit(Auth::user()->nama, 13) }}</span>
        </div>
        <div id="toggle-button-container" class="flex justify-end w-16">
            <button onclick="toggleSidebar()" class="text-base-content hover:text-primary">
                <i class="fa-solid fa-bars p-2.5"></i>
            </button>
        </div>
    </div>
    <hr class="border-t border-2 border-neutral-content my-2">

    <nav class="flex-1">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('admin') }}" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                    <i class="fa-solid fa-gauge group-hover:text-primary transition-transform duration-200"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
            @if(in_array(Auth::user()->role_id, ['1']))
            <li x-data="{ open: false }">
                <a href="#" @click="open = ! open"
                    class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
                    <i class="fa-solid fa-user group-hover:text-primary transition-transform duration-200"></i>
                    <span class="sidebar-text">Manajemen User</span>
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
                        <a href="#" class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 group">
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
            @endif

            <li>
                <a href="{{ route('keluar') }}"
                    class="flex items-center gap-4 p-2 rounded-md hover:bg-base-200 text-error group"
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
            const sidebarTitleContainer = document.querySelector('#sidebar .flex.items-center.gap-3');
            const navLinks = document.querySelectorAll('#sidebar nav ul li > a'); // Targetkan link level pertama
            const chevronIcons = document.querySelectorAll('#sidebar nav ul li > a i.fa-chevron-down');
            const toggleButtonContainer = document.getElementById('toggle-button-container');
            const submenus = document.querySelectorAll('#sidebar nav ul li ul'); // Semua submenu containers

            // Toggle sidebar width classes
            sidebar.classList.toggle('w-64');
            sidebar.classList.toggle('w-20');

            // Toggle main content margin classes
            mainContent.classList.toggle('ml-64');
            mainContent.classList.toggle('ml-20');

            // Toggle header margin classes
            header.classList.toggle('ml-64');
            header.classList.toggle('ml-20');

            // Hide/show text elements
            texts.forEach(text => {
                text.classList.toggle('hidden');
            });

            // Hide/show sidebar title container
            sidebarTitleContainer.classList.toggle('hidden');

            // Toggle nav link justification
            navLinks.forEach(link => {
                link.classList.toggle('justify-start');
                link.classList.toggle('justify-center');
            });

            // Toggle chevron icon visibility
            chevronIcons.forEach(icon => {
                icon.classList.toggle('hidden');
            });

            // Handle submenu state and visibility
            if (sidebar.classList.contains('w-20')) {
                // Force close and hide all submenus when sidebar is collapsed
                submenus.forEach(submenu => {
                    submenu.classList.add('hidden'); // Force hide submenu
                });

                // Using Alpine.js to close the state
                if (window.Alpine) {
                    document.querySelectorAll('[x-data]').forEach(el => {
                        if (el.__x && el.__x.$data.hasOwnProperty('open')) {
                            el.__x.$data.open = false;
                        }
                    });
                }
            } else {
                // When expanding, keep submenus hidden until clicked
                // This prevents them from auto-showing when sidebar expands
                submenus.forEach(submenu => {
                    // Remove the forced hidden class but let Alpine control visibility
                    submenu.classList.remove('hidden');
                });
            }

            // Adjust toggle button container justification
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
