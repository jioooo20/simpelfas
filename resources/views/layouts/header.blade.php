{{-- header --}}
<div id="header"
    {{-- class="flex items-center justify-between bg-base-100 px-6 py-1 pt-5 ml-64 transition-all duration-300"> --}}
    class="sticky top-0 z-50 flex items-center justify-between bg-base-100 px-6 py-1 pt-5 ml-64 transition-all duration-300">
    <div class="flex items-center gap-4">
        <span class="text-2xl judul text-content-accent">Simpelfas</span>
    </div>
    <div id="realtime-clock" class="hidden sm:block text-base-content hover:text-neutral-focus md:flex md:items-center md:gap-2">
        {{ Carbon\Carbon::now()->translatedFormat('l, d F Y H:i:s') }}
    </div>
    <div id="realtime-clock" class="sm:hidden text-base-content hover:text-neutral-focus flex items-center gap-2">
        {{ Carbon\Carbon::now()->translatedFormat('H:i:s') }}
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
