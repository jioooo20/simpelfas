@extends('layouts.main')
@section('judul', 'Kelola Pengguna')

@section('content')
    <div class="container mx-auto px-4 py-4">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Buat Laporan Kerusakan Fasilitas</h1>
            <p class="text-gray-600 text-sm mt-2 md:mt-0">Laporkan kerusakan fasilitas untuk perbaikan segera</p>
        </div>

        <!-- Main Card -->
        <div class="overflow-hidden border border-gray-200 shadow-md rounded-xl bg-white">
            <!-- Form -->
            <form action="" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Informasi Laporan</h2>

                <div class="space-y-4">
                    <div class="grid gap-2">
                        <label for="nama" class="text-gray-700 font-medium">Nama Pelapor</label>
                        <input
                            id="nama"
                            name="nama"
                            type="text"
                            placeholder="Nama lengkap sesuai identitas"
                            required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>

                    <div class="form-control w-full relative">
                        <label for="search-lokasi" class="label">
                            <span class="label-text text-base text-gray-700 font-semibold">Lokasi Kerusakan</span>
                        </label>

                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.6 3.6a7.5 7.5 0 0013.05 13.05z" />
                                </svg>
                            </div>
                            <input
                                type="text"
                                id="search-lokasi"
                                placeholder="Cari lokasi..."
                                autocomplete="off"
                                class="input input-bordered w-full pl-10 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                onfocus="showDropdown()"
                            />
                            <input type="hidden" id="lokasi" name="lokasi" required />
                        </div>
                    </div>

                    {{-- dropdown sekarang di luar .form-control --}}
                    <div id="dropdown" class="w-full bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-auto hidden mt-1">
                        <ul id="lokasi-options" class="py-1 text-sm"></ul>
                        <div id="not-found" class="px-4 py-3 text-sm text-gray-500 italic bg-gray-50 hidden">
                            Tidak ada lokasi yang cocok ditemukan
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <label for="deskripsi" class="text-gray-700 font-medium">Deskripsi Kerusakan</label>
                        <textarea
                            id="deskripsi"
                            name="deskripsi"
                            placeholder="Contoh: AC tidak menyala, mengeluarkan suara berisik"
                            required
                            class="w-full min-h-[120px] border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        ></textarea>
                    </div>

                    <div class="grid gap-2">
                        <label for="foto" class="text-gray-700 font-medium flex items-center gap-1">
                            Upload Foto Kerusakan
                            <i class="bi bi-info-circle text-gray-400"
                               title="Gunakan foto yang jelas agar proses perbaikan cepat diproses"></i>
                        </label>
                        <label
                            for="foto"
                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors"
                        >
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="bi bi-upload text-2xl text-gray-500 mb-2"></i>
                                <p class="mb-2 text-sm text-gray-500">
                                    <span class="font-semibold">Klik untuk upload</span> atau drag and drop
                                </p>
                                <p class="text-xs text-gray-500">PNG, JPG atau JPEG (Maks. 10MB)</p>
                            </div>
                            <input
                                id="foto"
                                name="foto"
                                type="file"
                                accept="image/*"
                                class="hidden"
                            />
                        </label>
{{--                        <p class="text-xs text-gray-400 italic mt-2 text-center">Belum ada file yang dipilih</p>--}}
                        <!-- Image Preview Now at the Bottom -->
                        <div
                            class="relative w-full h-64 bg-gray-100 border border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-400 mt-6">
                            <i class="bi bi-image text-6xl mb-2"></i>
                            <p>Preview foto kerusakan akan ditampilkan di sini</p>
                        </div>
                    </div>
                </div>

                <div class="pt-4 pb-2 flex justify-end">
                    <button
                        type="submit"
                        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-transform hover:scale-105"
                    >
                        <i class="bi bi-send"></i> Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('skrip')
    <script>
        const searchInput    = document.getElementById('search-lokasi');
        const lokasiHidden   = document.getElementById('lokasi');
        const dropdown       = document.getElementById('dropdown');
        const optionsList    = document.getElementById('lokasi-options');
        const notFound       = document.getElementById('not-found');

        const locations = [
            "Gedung Sipil - lantai 1 - ruang 101 - lampu",
            "Gedung Sipil - lantai 1 - ruang 102 - lampu",
            "Gedung Sipil - lantai 2 - ruang 201 - lampu",
            "Gedung Sipil - lantai 2 - ruang 202 - lampu",
            "Gedung Sipil - lantai 3 - ruang 301 - lampu"
        ];

        let activeIndex = -1;
        let currentOptions = [];

        function showDropdown() {
            dropdown.classList.remove('hidden');
        }

        function hideDropdown() {
            dropdown.classList.add('hidden');
            activeIndex = -1;
        }

        function renderOptions(filter) {
            optionsList.innerHTML = "";
            currentOptions = locations.filter(loc => loc.toLowerCase().includes(filter.toLowerCase()));
            if (currentOptions.length === 0) {
                notFound.classList.remove('hidden');
            } else {
                notFound.classList.add('hidden');
                currentOptions.forEach((loc, index) => {
                    const li = document.createElement('li');
                    li.textContent = loc;
                    li.className = "px-4 py-2 hover:bg-blue-50 cursor-pointer transition-colors";
                    li.dataset.index = index;
                    li.onclick = () => selectOption(index);
                    optionsList.appendChild(li);
                });
            }
        }

        function selectOption(index) {
            if (index >= 0 && index < currentOptions.length) {
                const selected = currentOptions[index];
                searchInput.value = selected;
                lokasiHidden.value = selected;
                hideDropdown();
            }
        }

        function updateActiveOption() {
            const items = optionsList.querySelectorAll('li');
            items.forEach((li, index) => {
                li.classList.toggle('bg-blue-100', index === activeIndex);
            });
        }

        searchInput.addEventListener('input', function () {
            const filter = this.value.trim();
            if (filter.length > 0) {
                showDropdown();
                renderOptions(filter);
            } else {
                hideDropdown();
            }
        });

        searchInput.addEventListener('keydown', function (e) {
            const total = currentOptions.length;
            if (dropdown.classList.contains('hidden') || total === 0) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                activeIndex = (activeIndex + 1) % total;
                updateActiveOption();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                activeIndex = (activeIndex - 1 + total) % total;
                updateActiveOption();
            } else if (e.key === 'Enter') {
                e.preventDefault();
                selectOption(activeIndex);
            }
        });

        document.addEventListener('click', function (event) {
            if (!searchInput.contains(event.target) && !dropdown.contains(event.target)) {
                hideDropdown();
            }
        });
    </script>
@endpush
