{{-- Update Status --}}
<dialog id="update_status_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Update Status Perbaikan <span id="kode_perbaikan">PRB-001</span></h3>
        <form method="POST" action="" class="mt-4 space-y-4" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="perbaikan_id" id="perbaikan_id">

            <div>
                <label for="status" class="block mb-2 text-sm font-medium">Status Perbaikan</label>
                <select name="status" id="status" class="select select-bordered w-full" required>
                    <option value="" disabled selected>Pilih Status</option>
                    <option value="Diproses">Diproses</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>

            <!-- Upload Foto Kerusakan -->
            <div class="grid gap-2"> 
                <label for="foto" class="text-gray-700 font-medium flex items-center gap-1">
                    Upload Foto Kerusakan
                    <i class="bi bi-info-circle text-gray-400"
                        title="Gunakan foto yang jelas agar proses perbaikan cepat diproses"></i>
                </label> <label id="upload-area" for="foto"
                    class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div id="upload-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                        <i class="bi bi-upload text-2xl text-gray-500 mb-2"></i>
                        <p class="mb-2 text-sm text-gray-500">
                            <span class="font-semibold">Klik untuk upload</span> atau drag and drop
                        </p>
                        <p class="text-xs text-gray-500">PNG, JPG atau JPEG (Maks. 10MB)</p>
                    </div>
                    <div id="image-preview" class="hidden w-full h-full p-2">
                        <img src="#" alt="Preview" class="w-full h-full object-contain rounded-md">
                    </div>
                    <input id="foto" name="foto" type="file" accept="image/*" class="hidden" />
                </label>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-sm" onclick="update_status_modal.close()">Batal</button>
                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</dialog>


@push('skrip')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Script untuk menampilkan preview gambar
            const fileInput = document.getElementById('foto');
            const uploadPlaceholder = document.getElementById('upload-placeholder');
            const imagePreview = document.getElementById('image-preview');
            const previewImg = imagePreview.querySelector('img');

            fileInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    const file = e.target.files[0];

                    // Hanya proses jika file adalah gambar
                    if (file.type.match('image.*')) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            previewImg.src = e.target.result;
                            uploadPlaceholder.classList.add('hidden');
                            imagePreview.classList.remove('hidden');
                        };

                        reader.readAsDataURL(file);
                    }
                } else {
                    // Reset preview jika tidak ada file yang dipilih
                    uploadPlaceholder.classList.remove('hidden');
                    imagePreview.classList.add('hidden');
                }
            });
            // Tambahkan tombol untuk menghapus gambar
            const uploadArea = document.getElementById('upload-area');
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className =
                'absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hidden';
            removeButton.innerHTML = '<i class="bi bi-x text-xl"></i>';
            removeButton.id = 'remove-image';

            uploadArea.style.position = 'relative';
            uploadArea.appendChild(removeButton);

            // Event listener untuk tombol hapus
            removeButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                fileInput.value = '';
                uploadPlaceholder.classList.remove('hidden');
                imagePreview.classList.add('hidden');
                removeButton.classList.add('hidden');
            });

            // Tampilkan tombol hapus saat ada gambar
            fileInput.addEventListener('change', function() {
                if (fileInput.files.length > 0) {
                    removeButton.classList.remove('hidden');
                } else {
                    removeButton.classList.add('hidden');
                }
            });

            @if (session('error'))
                Toastify({
                    text: `<div class="flex items-center gap-3">
                              <i class="bi bi-exclamation-circle-fill text-xl"></i>
                              <span>{{ session('error') }}</span>
                           </div>`,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                    className: "rounded-lg shadow-md",
                    stopOnFocus: true,
                    // close: true,
                    escapeMarkup: false,
                    style: {
                        padding: "12px 20px",
                        fontWeight: "500",
                        minWidth: "300px"
                    },
                }).showToast();
            @endif

            @if (session('success'))
                Toastify({
                    text: `<div class="flex items-center gap-3">
                              <i class="bi bi-check-circle-fill text-xl"></i>
                              <span>{{ session('success') }}</span>
                           </div>`,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                    className: "rounded-lg shadow-md",
                    stopOnFocus: true,
                    // close: true,
                    escapeMarkup: false,
                    style: {
                        padding: "12px 20px",
                        fontWeight: "500",
                        minWidth: "300px"
                    },
                }).showToast();
            @endif
        });
    </script>
@endpush
