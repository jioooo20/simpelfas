{{-- File: resources/views/pages/teknisi/perbaikan/view-image.blade.php --}}

<dialog id="image_viewer_modal" class="modal modal-middle">
    <div class="modal-box w-11/12 max-w-4xl p-0 flex flex-col h-auto max-h-[90vh]">
        <div class="flex items-center justify-between p-4 border-b flex-shrink-0">
            <h3 id="image_viewer_title" class="font-bold text-lg">Tampilan Gambar</h3>
            <button onclick="document.getElementById('image_viewer_modal').close()"
                    class="btn btn-sm btn-circle btn-ghost">
                <i class="bi bi-x text-2xl"></i>
            </button>
        </div>

        <div class="p-4 flex-grow flex justify-center items-center bg-slate-50 min-h-0">
            <img id="image_viewer_img" src="" alt="Tampilan Gambar"
                 class="max-w-full max-h-full rounded-lg object-contain">
        </div>
    </div>

    <!-- Close Buttom -->
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form> <!-- End Close Buttom -->
</dialog>

@push('skrip')
    <script>
        function openImageModal(imageUrl, title = 'Tampilan Gambar') {
            if (!imageUrl) {
                console.error('Image URL is not provided for openImageModal function.');
                return;
            }

            const modal = document.getElementById('image_viewer_modal');
            const img = document.getElementById('image_viewer_img');
            const modalTitle = document.getElementById('image_viewer_title');

            img.src = imageUrl;
            modalTitle.innerText = title;
            modal.showModal();
        }

        document.addEventListener('DOMContentLoaded', function () {
            const imageViewerModal = document.getElementById('image_viewer_modal');

            if (imageViewerModal) {
                imageViewerModal.addEventListener('click', function (event) {
                    if (event.target === imageViewerModal) {
                        imageViewerModal.close();
                    }
                });
            }

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
