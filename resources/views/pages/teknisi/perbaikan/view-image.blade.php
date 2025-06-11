<dialog id="view_laporan_image_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <div class="flex items-center justify-between align-content-center ">
            <h3 class="font-bold text-lg">Foto Laporan</h3>
            <button onclick="document.getElementById('view_laporan_image_modal').close()" class="btn btn-sm btn-circle hover:bg-red-400 hover:text-white" type="button"><i class="fa fa-xmark"></i></button>
        </div>
        <div class="mt-4">
            <img id="foto_laporan_img" src="https://placehold.co/600x400" alt="Foto Bukti" class="w-full rounded-lg max-h-96 object-contain">
        </div>
    </div>
</dialog>

@push('skrip')
    <script>
        // Fungsi untuk membuka modal dan menampilkan foto
        function openImageModal(fotoPath) {
            const modal = document.getElementById('view_laporan_image_modal');
            const img = document.getElementById('foto_laporan_img');
            if (fotoPath) {
                img.src = `${window.location.origin}/storage/${fotoPath}`;
            } else {
                img.src = 'https://placehold.co/600x400?text=Foto+Tidak+Tersedia';
            }
            modal.showModal();
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Script untuk menutup modal saat mengklik area luar
            const viewLaporanModal = document.getElementById('view_laporan_image_modal');

            // Menambahkan event listener untuk click pada modal
            viewLaporanModal.addEventListener('click', function(event) {
                // Jika yang diklik adalah modal itu sendiri (bukan elemen di dalamnya)
                if (event.target === viewLaporanModal) {
                    viewLaporanModal.close();
                }
            });

            // Tambahkan juga event listener untuk tombol ESC
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && viewLaporanModal.open) {
                    viewLaporanModal.close();
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
