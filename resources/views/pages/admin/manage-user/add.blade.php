{{-- add --}}

<dialog id="modal_add_user" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Tambah Pengguna Baru</h3>
        <form method="POST" action="{{ route('admin.user-add') }}">
            @csrf
            <div class="form-control mb-3">
                <label class="label">
                    <span class="label-text">Nama Lengkap<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span>
                </label>
                <input type="text" name="nama" class="input input-bordered" maxlength="50" required />
            </div>
            <div class="form-control mb-3">
                <label class="label">
                    <span class="label-text">Identitas (NIM / NIP)<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span>
                </label>
                <input type="text" name="identitas" class="input input-bordered" maxlength="20" required />
            </div>
            <div class="form-control mb-3">
                <label class="label">
                    <span class="label-text">Email<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span>
                </label>
                <input type="email" name="email" class="input input-bordered"
                    title="Masukkan alamat email yang valid" oninput="validateEmail(this)" required maxlength="60" />
                <div id="email-validation-message" class="text-xs text-red-500 mt-1 hidden">
                    Format email tidak valid
                </div>
            </div>
            <div class="form-control mb-3">
                <label class="label">
                    <span class="label-text">Password<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span>
                </label>
                <input type="password" id="password" name="password" class="input input-bordered" minlength="5"
                    title="Password harus minimal 5 karakter" required />
                <label class="label">
                    <span class="label-text-alt text-gray-500">Minimal 5 karakter</span>
                </label>
            </div>
            <div class="form-control mb-3">
                <label class="label">
                    <span class="label-text">Hak Akses<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span>
                </label>
                <select name="role_id" class="select select-bordered w-full" required>
                    <option disabled selected>Pilih hak akses</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->role_id }}">{{ $role->role_nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-sm" onclick="modal_add_user.close()">Batal</button>
                <button type="submit" class="btn btn-sm btn-primary text-white">Simpan</button>
            </div>
        </form>
    </div>
</dialog>


@push('skrip')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            @if (session('error'))
                Toastify({
                    text: `<div class="flex items-center gap-3">
                              <i class="bi bi-exclamation-circle-fill text-xl"></i>
                              <span>{{ session('error') }}</span>
                           </div>`,
                    duration: 2500,
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
        });
    </script>
@endpush
