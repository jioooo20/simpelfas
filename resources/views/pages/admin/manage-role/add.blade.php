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
                <button type="button" class="btn btn-sm" onclick="add_role_modal.close()">Cancel</button>
                <button type="submit" class="btn btn-sm btn-primary">Save Role</button>
            </div>
        </form>
    </div>
</dialog>


@push('skrip')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('error'))
                Toastify({
                    text: "{{ session('error') }}",
                    duration: 2000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#f44336",
                    stopOnFocus: true,
                }).showToast();
            @endif

            @if (session('success'))
                Toastify({
                    text: "{{ session('success') }}",
                    duration: 2000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#4caf50",
                    stopOnFocus: true,
                }).showToast();
            @endif
        });
    </script>
@endpush
