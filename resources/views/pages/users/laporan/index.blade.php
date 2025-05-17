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
            <form id="pelaporanForm" enctype="multipart/form-data" class="px-6 pb-3 space-y-4">
                @csrf
                <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4 ">Informasi Laporan</h2>

                <div class="space-y-4"> <!-- Nama Pelapor -->
                    <div class="grid gap-2">
                        <label for="nama" class="text-gray-700 font-medium">Nama Pelapor</label>
                        <input
                            id="nama"
                            name="nama"
                            type="text"
                            placeholder="Nama lengkap sesuai identitas"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div> <!-- End of Nama Pelapor -->

                    <div class="form-control w-full relative"> <!-- Lokasi Kerusakan -->
                        <label for="search-lokasi" class="label">
                            <span class="label-text text-base text-gray-700 font-semibold">Lokasi Kerusakan</span>
                        </label>

                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.6 3.6a7.5 7.5 0 0013.05 13.05z"/>
                                </svg>
                            </div>
                            <input
                                type="text"
                                id="search-lokasi"
                                placeholder="Cari lokasi..."
                                autocomplete="off"
                                class="input input-bordered w-full pl-10 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <input type="hidden" id="lokasi" name="lokasi"/>
                        </div>
                    </div> <!-- End of Lokasi Kerusakan -->

                    <!-- Dropdown -->
                    <div id="dropdown"
                         class="w-full bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-auto hidden mt-1">
                        <ul id="lokasi-options" class="py-1 text-sm divide-y divide-gray-100"></ul>
                        <div id="not-found" class="px-4 py-3 text-sm text-gray-500 italic bg-gray-50 hidden">
                            Tidak ada lokasi yang cocok ditemukan
                        </div>
                    </div> <!-- End of Dropdown -->

                    <div class="grid gap-2"> <!-- Deskripsi Kerusakan -->
                        <label for="deskripsi" class="text-gray-700 font-medium">Deskripsi Kerusakan</label>
                        <textarea
                            id="deskripsi"
                            name="deskripsi"
                            placeholder="Contoh: AC tidak menyala, mengeluarkan suara berisik"
                            class="w-full min-h-[120px] border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        ></textarea>
                    </div> <!-- End of Deskripsi Kerusakan -->

                    <div class="grid gap-2"> <!-- Upload Foto Kerusakan -->
                        <label for="foto" class="text-gray-700 font-medium flex items-center gap-1">
                            Upload Foto Kerusakan
                            <i class="bi bi-info-circle text-gray-400"
                               title="Gunakan foto yang jelas agar proses perbaikan cepat diproses"></i>
                        </label>
                        <label
                            id="upload-area"
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
                    </div> <!-- End of Upload Area -->

                    <!!-- Preview Image -->
                    <div id="preview-container" class="mt-4 hidden">
                        <img id="preview-image" class="w-full h-64 object-contain border rounded-lg"/>
                        <button type="button" id="remove-preview"
                                class="mt-2 text-sm text-red-500 hover:underline">
                            Hapus Foto
                        </button>
                    </div> <!-- End of Preview Image -->

                    <div class="pt-4 pb-2 flex justify-end">
                        <button
                            type="submit"
                            class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-transform hover:scale-105"
                        >
                            <i class="bi bi-send"></i> Kirim Laporan
                        </button>
                    </div> <!--  End of Submit Button -->
                </div> <!-- End of Card Body -->
            </form> <!-- End of Form -->
        </div> <!-- End of Card Body -->
    </div> <!-- End of Main Card -->
@endsection
@push('skrip')
    <script>
        // This script handles the location search dropdown and form submission
        const searchInput = document.getElementById('search-lokasi');
        const lokasiHidden = document.getElementById('lokasi');
        const dropdown = document.getElementById('dropdown');
        const optionsList = document.getElementById('lokasi-options');
        const notFound = document.getElementById('not-found');

        let locations = [];
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
            optionsList.innerHTML = '';
            const terms = filter.toLowerCase().split(/\s+/).filter(Boolean);

            currentOptions = locations.filter(loc => {
                const searchable = `${loc.label} ${loc.search || ''}`.toLowerCase();
                return terms.every(term => searchable.includes(term));
            });

            if (!currentOptions.length) {
                notFound.classList.remove('hidden');
            } else {
                notFound.classList.add('hidden');
                currentOptions.slice(0, 8).forEach((loc, i) => {
                    const li = document.createElement('li');
                    li.textContent = loc.label;
                    li.className = "px-4 py-2 hover:bg-blue-50 cursor-pointer transition-colors";
                    li.dataset.index = i;
                    li.onclick = () => selectOption(i);
                    optionsList.appendChild(li);
                });
            }
        }

        function selectOption(index) {
            if (index < 0 || index >= currentOptions.length) return;
            const selected = currentOptions[index];
            searchInput.value = selected.label;
            lokasiHidden.value = selected.id;
            hideDropdown();
        }

        function updateActiveOption() {
            optionsList.querySelectorAll('li').forEach((li, i) => {
                li.classList.toggle('bg-blue-100', i === activeIndex);
            });
        }

        fetch('/users/lokasi-options')
            .then(res => {
                if (!res.ok) throw new Error(`Fetch failed: ${res.status}`);
                return res.json();
            })
            .then(data => locations = data)
            .catch(err => console.error('Fetch error:', err));

        searchInput.addEventListener('input', function () {
            const filter = this.value.trim();
            if (filter) {
                showDropdown();
                renderOptions(filter);
            } else hideDropdown();
        });

        searchInput.addEventListener('keydown', function (e) {
            if (dropdown.classList.contains('hidden') || !currentOptions.length) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                activeIndex = (activeIndex + 1) % currentOptions.length;
                updateActiveOption();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                activeIndex = (activeIndex - 1 + currentOptions.length) % currentOptions.length;
                updateActiveOption();
            } else if (e.key === 'Enter') {
                e.preventDefault();
                selectOption(activeIndex);
            }
        });

        searchInput.addEventListener('focus', function () {
            if (this.value.trim()) {
                showDropdown();
                renderOptions(this.value.trim());
            }
        });

        document.addEventListener('click', e => {
            if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                hideDropdown();
            }
        });

        // This script handles the form submission
        document.getElementById('pelaporanForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');

            const values = {
                nama: form.querySelector('#nama').value.trim(),
                lokasi: form.querySelector('#lokasi').value.trim(),
                deskripsi: form.querySelector('#deskripsi').value.trim(),
                foto: form.querySelector('#foto').files[0]
            };

            if (!validateForm(values)) return;

            submitBtn.disabled = true;

            try {
                const res = await fetch('{{ route('store-pelaporan') }}', {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    body: formData
                });

                const data = await res.json();
                submitBtn.disabled = false;
                handleResponse(res, data, form);
            } catch (err) {
                console.error('Error:', err);
                showToast('Terjadi kesalahan pada sistem.', 'red');
                submitBtn.disabled = false;
            }
        });

        function validateForm({nama, lokasi, deskripsi, foto}) {

            if (!nama) return showToast("Nama harus diisi.", "red"), false;
            if (!lokasi) return showToast("Lokasi harus dipilih.", "red"), false;
            if (!deskripsi) return showToast("Deskripsi harus diisi.", "red"), false;
            if (!foto) return showToast("Foto harus diupload.", "red"), false;
            return true;
        }

        function showToast(message, color = 'blue', cb = null) {

            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: color,
                callback: cb
            }).showToast();
        }

        function handleResponse(res, data, form) {
            if (res.ok) {
                form.reset();
                showToast(data.message || "Laporan berhasil dikirim.", "green", () => location.reload());
            } else if (data.errors) {
                for (const key in data.errors) {
                    showToast(`${key}: ${data.errors[key][0]}`, "red");
                }
            } else {
                showToast(data.message || 'Terjadi kesalahan.', "red");
            }
        }

        // This script handles the image upload preview
        const fotoInput = document.getElementById('foto');
        const previewContainer = document.getElementById('preview-container');
        const previewImage = document.getElementById('preview-image');
        const removePreviewBtn = document.getElementById('remove-preview');
        const uploadLabel = fotoInput.closest('label');
        const uploadArea = document.getElementById('upload-area');
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        const maxFileSize = 10 * 1024 * 1024;

        function initFotoUploadPreview() {
            fotoInput.addEventListener('change', handleFotoChange);
            removePreviewBtn.addEventListener('click', resetFotoPreview);

            ['dragenter', 'dragover'].forEach(evt => {
                uploadArea.addEventListener(evt, e => {
                    e.preventDefault();
                    uploadArea.classList.add('bg-blue-50', 'border-blue-300');
                });
            });

            ['dragleave', 'drop'].forEach(evt => {
                uploadArea.addEventListener(evt, e => {
                    e.preventDefault();
                    uploadArea.classList.remove('bg-blue-50', 'border-blue-300');
                });
            });

            uploadArea.addEventListener('drop', handleFileDrop);
        }

        function handleFotoChange(e) {
            const file = e.target.files[0];
            if (file && validateFile(file)) showPreview(file);
        }

        function handleFileDrop(e) {
            const file = e.dataTransfer.files[0];
            if (file && validateFile(file)) {
                fotoInput.files = e.dataTransfer.files;
                showPreview(file);
            }
        }

        function showPreview(file) {
            const reader = new FileReader();
            reader.onload = e => {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
                uploadLabel.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }

        function resetFotoPreview() {
            fotoInput.value = "";
            previewImage.src = "";
            previewContainer.classList.add('hidden');
            uploadLabel.classList.remove('hidden');
        }

        function validateFile(file) {
            if (!allowedTypes.includes(file.type)) {
                showToast("Jenis file tidak didukung. Gunakan PNG, JPG, atau JPEG.", "red");
                return false;
            }
            if (file.size > maxFileSize) {
                showToast("Ukuran file maksimal 10MB.", "red");
                return false;
            }
            return true;
        }

        document.addEventListener('DOMContentLoaded', initFotoUploadPreview);
    </script>
@endpush
