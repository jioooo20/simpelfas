{{-- header --}}
<div id="header" {{-- class="flex items-center justify-between bg-base-100 px-6 py-1 pt-5 ml-64 transition-all duration-300"> --}}
    class="sticky top-0 z-50 flex items-center justify-between bg-base-100 px-6 py-1 pt-4 ml-64 transition-all duration-300">
    <div class="flex items-center gap-4 text-2xl font-bold text-base-content text-center md:text-left">
        @yield('judul')
    </div>

    <div class="flex items-center gap-6">
        {{-- Clock --}}
        <div id="realtime-clock"
            class="hidden sm:block text-base-content hover:text-neutral-focus md:flex md:items-center md:gap-2">
            {{ Carbon\Carbon::now()->translatedFormat('l, d F Y H:i:s') }}
        </div>
        <div id="realtime-clock" class="sm:hidden text-base-content hover:text-neutral-focus flex items-center gap-2">
            {{ Carbon\Carbon::now()->translatedFormat('H:i:s') }}
        </div>

        <div class="relative">
            @php
            $notifs = auth()->user()->unreadNotifications;
            @endphp

            <button onclick="toggleNotifDropdown()" class="relative p-2 rounded-full hover:bg-base-200 transition-all duration-200 group">
            <svg class="w-6 h-6 text-base-content group-hover:text-primary transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            @if ($notifs->count() > 0)
                <span class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-error rounded-full animate-pulse">
                {{ $notifs->count() > 99 ? '99+' : $notifs->count() }}
                </span>
            @endif
            </button>

            <div id="notifDropdown"
            class="hidden absolute right-0 mt-3 w-96 bg-base-100 shadow-2xl rounded-2xl border border-base-300 z-50 transform transition-all duration-200">
            <div class="p-4 font-semibold border-b border-base-300 bg-base-200 rounded-t-2xl">
                <div class="flex items-center justify-between">
                <span class="text-base-content">Notifikasi</span>
                @if ($notifs->count() > 0)
                    <span class="badge badge-error badge-sm text-white">{{ $notifs->count() }}</span>
                @endif
                </div>
            </div>
            <div class="max-h-80 overflow-y-auto scrollbar-thin scrollbar-thumb-base-300">
                @forelse ($notifs as $notif)
                <a href="{{ $notif->data['url'] }}"
                    class="block px-4 py-3 hover:bg-base-200 border-b border-base-300 transition-colors duration-200 group">
                    <div class="flex items-start gap-3">
                    <div class="w-2 h-2 bg-primary rounded-full mt-2 flex-shrink-0"></div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-base-content group-hover:text-primary transition-colors duration-200 truncate">
                        {{ $notif->data['title'] }}
                        </div>
                        <div class="text-sm text-base-content/70 mt-1 line-clamp-2">
                        {{ $notif->data['message'] }}
                        </div>
                        <div class="text-xs text-base-content/50 mt-1">
                        {{ $notif->created_at->diffForHumans() }}
                        </div>
                    </div>
                    </div>
                </a>
                @empty
                <div class="px-4 py-8 text-base-content/60 text-sm text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 text-base-content/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <div>Tidak ada notifikasi baru</div>
                </div>
                @endforelse
            </div>
            @if ($notifs->count() > 0)
                <div class="p-3 bg-base-200 rounded-b-2xl border-t border-base-300">
                <form method="POST" action="{{ route('notifikasi.readAll') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary w-full hover:btn-primary-focus transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Tandai Semua Dibaca
                    </button>
                </form>
                </div>
            @endif
            </div>
        </div>

        {{-- Profile Dropdown --}}
        <div class="relative">
            <button onclick="toggleProfileDropdown()" class="flex items-center gap-2 focus:outline-none">
                @if (Auth::user()->profile_image)
                    <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="{{ Auth::user()->nama }}"
                        class="h-10 w-10 rounded-full border-2 border-gray-300 shadow-lg object-cover" />
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama) }}&background=4338ca&color=fff"
                        alt="{{ Auth::user()->nama }}" class="h-10 w-10 rounded-full shadow">
                @endif
                <span class="sidebar-text font-semibold text-lg hidden sm:block">{{ Auth::user()->nama }}</span>
                <svg class="w-4 h-4 ml-1 text-base-content hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div id="profileDropdown"
                class="hidden absolute right-0 mt-3 w-48 bg-base-100 shadow-2xl rounded-xl border border-base-300 z-50 transform transition-all duration-200">
                <div class="py-1">
                    <a href="{{ route('profile') }}"
                        class="block px-4 py-2 text-sm text-base-content hover:bg-base-200 hover:text-primary transition-colors duration-200">
                        Lihat Profil
                    </a>
                    <div class="border-t border-base-300"></div>
                    <a href="{{ route('keluar') }}"
                        class="block px-4 py-2 text-sm text-red-500 hover:bg-red-50  transition-colors duration-200"
                        onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                        Logout
                    </a>
                    <form id="logout-form-header" action="{{ route('keluar') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@push('skrip')
    <script>
        function toggleNotifDropdown() {
            const dropdown = document.getElementById('notifDropdown');
            dropdown.classList.toggle('hidden');
            // Close profile dropdown if open
            document.getElementById('profileDropdown')?.classList.add('hidden');
        }

        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('hidden');
            // Close notif dropdown if open
            document.getElementById('notifDropdown')?.classList.add('hidden');
        }

        document.addEventListener('click', function(event) {
            const notifDropdown = document.getElementById('notifDropdown');
            const profileDropdown = document.getElementById('profileDropdown');

            const isClickInsideNotif = notifDropdown?.contains(event.target) || event.target.closest('button[onclick="toggleNotifDropdown()"]');
            const isClickInsideProfile = profileDropdown?.contains(event.target) || event.target.closest('button[onclick="toggleProfileDropdown()"]');

            if (!isClickInsideNotif) {
                notifDropdown?.classList.add('hidden');
            }
            if (!isClickInsideProfile) {
                profileDropdown?.classList.add('hidden');
            }
        });

        function updateClock() {
            fetch('/realtime-clock')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('realtime-clock').innerText = data;
                });
        }

        setInterval(updateClock, 1000);
    </script>
@endpush
