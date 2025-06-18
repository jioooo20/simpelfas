{{-- header.blade.php --}}
<div id="header"
     class="sticky top-0 z-20 flex items-center justify-between bg-base-100 py-1 pt-4 pl-4 pr-6 transition-all duration-300">

    {{-- Grup Kiri: Hamburger & Judul --}}
    <div class="flex items-center min-w-0 gap-4"> {{-- [MODIFIKASI] Tambahkan min-w-0 --}}
        {{-- Tombol Hamburger: Muncul di Tablet & Mobile, Hilang di Desktop --}}
        <button id="hamburger-btn" class="p-2 rounded-md text-base-content lg:hidden">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                {{-- [MODIFIKASI] Menggunakan path SVG untuk ikon 3 garis yang simetris --}}
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        {{-- Judul Header: Selalu Muncul --}}
        {{-- [MODIFIKASI] Ukuran teks 3-tingkat & pemotongan teks panjang --}}
        <div class="truncate text-lg font-bold text-base-content md:text-xl lg:text-2xl text-left">
            @yield('judul')
        </div>
    </div>


    {{-- Grup Kanan: Jam, Notifikasi, & Profil --}}
    <div class="flex items-center gap-3 md:gap-6">

        {{-- Wrapper untuk Jam --}}
        {{-- Logika: Hilang di mobile (<768px), muncul di tablet (md) ke atas --}}
        <div id="clock-wrapper" class="hidden md:flex md:items-center">
            {{-- [MODIFIKASI UKURAN TEKS] Dibuat lebih kecil di tablet (text-sm) dan standar di desktop (lg:text-base) --}}
            <div id="realtime-clock" class="text-sm text-base-content hover:text-neutral-focus lg:text-base">
                {{ Carbon\Carbon::now()->translatedFormat('l, d F Y H:i:s') }}
            </div>
        </div>


        <div class="relative">
            @php
                $notifs = auth()->user()->unreadNotifications;
            @endphp

            <button
                onclick="toggleNotifDropdown()"
                class="relative p-2 rounded-full hover:bg-base-200 transition-all duration-200 group hidden md:block">
                {{-- Ikon lonceng (sama) --}}
                <svg class="w-6 h-6 text-base-content group-hover:text-primary" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                {{-- Badge (sama) --}}
                @if ($notifs->count() > 0)
                    <span
                        class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-error rounded-full animate-pulse">
                {{ $notifs->count() > 99 ? '99+' : $notifs->count() }}
            </span>
                @endif
            </button>

            <button
                onclick="toggleNotifPanel()"
                class="relative p-2 rounded-full hover:bg-base-200 transition-all duration-200 group md:hidden">
                {{-- Ikon lonceng (sama) --}}
                <svg class="w-6 h-6 text-base-content group-hover:text-primary" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                {{-- Badge (sama) --}}
                @if ($notifs->count() > 0)
                    <span
                        class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-error rounded-full animate-pulse">
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
                                    <div
                                        class="font-semibold text-base-content group-hover:text-primary transition-colors duration-200 truncate">
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
                            <svg class="w-12 h-12 mx-auto mb-3 text-base-content/30" fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <div>Tidak ada notifikasi baru</div>
                        </div>
                    @endforelse
                </div>
                @if ($notifs->count() > 0)
                    <div class="p-3 bg-base-200 rounded-b-2xl border-t border-base-300">
                        <form method="POST" action="{{ route('notifikasi.readAll') }}">
                            @csrf
                            <button type="submit"
                                    class="btn btn-sm btn-primary w-full hover:btn-primary-focus transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M5 13l4 4L19 7"></path>
                                </svg>
                                Tandai Semua Dibaca
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <div class="relative">
            <button onclick="toggleProfileDropdown()" class="flex items-center gap-2 focus:outline-none">

                @if (Auth::user()->profile_image)
                    {{-- JIKA ADA FOTO PROFIL: Tampilkan foto --}}
                    <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="{{ Auth::user()->nama }}"
                         class="h-10 w-10 rounded-full border-2 border-base-300 object-cover shadow-lg"/>

                @else
                    {{-- [MODIFIKASI] JIKA TIDAK ADA FOTO PROFIL: Buat avatar inisial dengan CSS --}}
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-600 text-white shadow-lg">
                <span class="text-base font-semibold">
                    {{-- Mengambil 1 atau 2 huruf inisial dari nama --}}
                    @php
                        $nama = Auth::user()->nama;
                        $words = explode(' ', $nama);
                        $initials = '';
                        if (isset($words[0][0])) {
                            $initials .= strtoupper($words[0][0]);
                        }
                        if (count($words) > 1 && isset($words[1][0])) {
                            $initials .= strtoupper($words[1][0]);
                        } elseif (strlen($nama) > 1 && !isset($words[1][0])) {
                             $initials = strtoupper(substr($nama, 0, 2));
                        }
                    @endphp
                    {{ $initials }}
                </span>
                    </div>
                @endif

                {{-- [MODIFIKASI] Menyesuaikan ukuran teks nama agar konsisten --}}
                <span class="hidden font-semibold text-base text-base-content sm:block">{{ Auth::user()->nama }}</span>
                <svg class="ml-1 hidden h-4 w-4 text-base-content sm:block" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            {{-- Dropdown menu tidak berubah --}}
            <div id="profileDropdown"
                 class="hidden absolute right-0 mt-3 w-48 bg-base-100 shadow-2xl rounded-xl border border-base-300 z-50">
                <div class="py-1">
                    <a href="{{ route('profile') }}"
                       class="block px-4 py-2 text-sm text-base-content hover:bg-base-200 hover:text-primary transition-colors duration-200">
                        Lihat Profil
                    </a>
                    <div class="border-t border-base-300"></div>
                    <a href="{{ route('keluar') }}"
                       class="block px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition-colors duration-200"
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
