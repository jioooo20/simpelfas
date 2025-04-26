{{-- header --}}
<div id="header" class="flex items-center justify-between bg-white shadow-md px-6 py-3 ml-64 transition-all duration-300">
    <div class="flex items-center gap-4">
        <span style="color: oklch(45.7% 0.24 277.023);" class="text-2xl judul ">Simpelfas</span>
    </div>
    <div>
        <button class="text-gray-700 hover:text-black">
            {{Carbon\Carbon::now()->translatedFormat('l, d F Y H:i:s')}}
        </button>
    </div>
</div>
