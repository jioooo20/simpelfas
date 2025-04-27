{{-- header --}}
<div id="header"
    class="flex items-center justify-between bg-white shadow-md px-6 py-3 ml-64 transition-all duration-300">
    <div class="flex items-center gap-4">
        <span style="color: oklch(45.7% 0.24 277.023);" class="text-2xl judul ">Simpelfas</span>
    </div>
    <div id="realtime-clock" class="hidden sm:block text-gray-700 hover:text-black md:flex md:items-center md:gap-2">
        {{ Carbon\Carbon::now()->translatedFormat('l, d F Y H:i:s') }}
    </div>
    <div id="realtime-clock" class="sm:hidden text-gray-700 hover:text-black flex items-center gap-2">
        {{ Carbon\Carbon::now()->translatedFormat('H:i:s') }}
    </div>
</div>

@push('skrip')
    <script>
        function updateClock() {
            fetch('/realtime-clock') // Kirim permintaan ke route yang akan mengembalikan waktu saat ini
                .then(response => response.text())
                .then(data => {
                    document.getElementById('realtime-clock').innerText = data;
                });
        }

        setInterval(updateClock, 1000); // Perbarui setiap 1 detik (1000 milidetik)
    </script>
@endpush
