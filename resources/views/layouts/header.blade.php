{{-- header --}}
<div id="header" {{-- class="flex items-center justify-between bg-base-100 px-6 py-1 pt-5 ml-64 transition-all duration-300"> --}}
    class="sticky top-0 z-50 flex items-center justify-between bg-base-100 px-6 py-1 pt-4 ml-64 transition-all duration-300">
    <div class="flex items-center gap-4 text-2xl font-bold text-base-content text-center md:text-left" >
        @yield('judul')
    </div>

    <div class="flex items-center gap-6">
        {{-- link dashboard --}}
        <div id="realtime-clock"
            class="hidden sm:block text-base-content hover:text-neutral-focus md:flex md:items-center md:gap-2">
            {{ Carbon\Carbon::now()->translatedFormat('l, d F Y H:i:s') }}
        </div>
        <div id="realtime-clock" class="sm:hidden text-base-content hover:text-neutral-focus flex items-center gap-2">
            {{ Carbon\Carbon::now()->translatedFormat('H:i:s') }}
        </div>

        {{-- link profile --}}
        <a href="{{ route('profile') }}" class="flex items-center gap-2">
            @if (Auth::user()->profile_image)
                <img
                    src="{{ asset('storage/' . Auth::user()->profile_image) }}"
                    alt="{{ Auth::user()->nama }}"
                    class="h-10 w-10 rounded-full border-2 border-gray-300 shadow-lg object-cover"
                />
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama) }}&background=4338ca&color=fff"
                    alt="{{ Auth::user()->nama }}" class="h-10 w-10 rounded-full shadow">
            @endif
            <span class="sidebar-text font-semibold text-lg">{{ Auth::user()->nama }}</span>
        </a>
    </div>
</div>

@push('skrip')
    <script>
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
