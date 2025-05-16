{{-- add --}}
<dialog id="add_role_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Tambahkan Hak Akses Baru</h3>
        <form method="POST" action="{{ route('admin.role-add') }}" class="mt-4 space-y-4">
            @csrf
            <div>
                <label for="role_kode" class="block mb-2 text-sm font-medium">Kode</label>
                <input type="text" name="role_kode" id="role_kode" class="input input-bordered w-full" required>
            </div>
            <div>
                <label for="role_nama" class="block mb-2 text-sm font-medium">Nama Role</label>
                <input type="text" name="role_nama" id="role_nama" class="input input-bordered w-full" required>
            </div>
            <div>
                <label for="role_deskripsi" class="block mb-2 text-sm font-medium">Deskripsi</label>
                <textarea id="role_deskripsi" name="role_deskripsi" rows="3" class="textarea textarea-bordered w-full" required></textarea>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-sm" onclick="add_role_modal.close()">Batal</button>
                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</dialog>


@push('skrip')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
