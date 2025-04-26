{{-- sidebar --}}
<div id="sidebar" class="transition-all duration-300 bg-slate-800 text-white w-64 h-screen p-4 flex flex-col fixed top-0">
    <div class="flex items-center gap-4 p-2.5 justify-between mb-6">
        <span class="text-2xl judul" id="sidebar-title">Simpelfas</span>
        <button onclick="toggleSidebar()" class="text-white hover:text-gray-300">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>

    <nav class="flex-1">
        <ul class="space-y-2">
            <li>
                <a href="#" class="flex items-center gap-4 p-2 rounded-md hover:bg-slate-700">
                    <i class="fa-solid fa-gauge"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center gap-4 p-2 rounded-md hover:bg-slate-700">
                    <i class="fa-solid fa-user"></i>
                    <span class="sidebar-text">Profil</span>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('keluar') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-4 p-2 rounded-md hover:bg-slate-700 text-red-500">
                        <i class="fa-solid fa-sign-out-alt"></i>
                        <span class="sidebar-text">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</div>
