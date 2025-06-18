{{-- [TAMBAHKAN KODE INI] PANEL NOTIFIKASI UNTUK MOBILE --}}
<div id="notif-panel"
     class="fixed top-0 right-0 z-50 h-full w-full max-w-sm transform bg-base-100 shadow-xl transition-transform duration-300 ease-in-out translate-x-full md:hidden">
    <div class="flex h-full flex-col">
        {{-- Header Panel --}}
        <div class="p-4 font-semibold border-b border-base-300 bg-base-200">
            <div class="flex items-center justify-between">
                <span class="text-lg text-base-content">Notifikasi</span>
                <button onclick="toggleNotifPanel()" class="p-2 rounded-full hover:bg-base-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Konten Notifikasi --}}
        <div class="flex-1 overflow-y-auto">
            @php $notifs = auth()->user()->unreadNotifications; @endphp
            @forelse ($notifs as $notif)
                <a href="{{ $notif->data['url'] ?? '#' }}"
                   class="block px-4 py-3 transition-colors duration-200 border-b border-base-300 hover:bg-base-200 group">
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 mt-2 bg-primary rounded-full flex-shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <div
                                class="font-semibold text-base-content group-hover:text-primary transition-colors duration-200 truncate">
                                {{ $notif->data['title'] }}
                            </div>
                            <div class="mt-1 text-sm text-base-content/70 line-clamp-2">
                                {{ $notif->data['message'] }}
                            </div>
                            <div class="mt-1 text-xs text-base-content/50">
                                {{ $notif->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-4 py-8 text-sm text-center text-base-content/60">
                    <svg class="w-12 h-12 mx-auto mb-3 text-base-content/30" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <div>Tidak ada notifikasi baru</div>
                </div>
            @endforelse
        </div>

        {{-- Footer Panel --}}
        @if ($notifs->count() > 0)
            <div class="p-3 bg-base-200 border-t border-base-300">
                <form method="POST" action="{{ route('notifikasi.readAll') }}">
                    @csrf
                    <button type="submit" class="w-full btn btn-sm btn-primary">
                        Tandai Semua Dibaca
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

{{-- Overlay untuk Panel Notifikasi Mobile --}}
<div id="notif-overlay" class="fixed inset-0 z-40 hidden bg-black bg-opacity-50 md:hidden"
     onclick="toggleNotifPanel()"></div>
