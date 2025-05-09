{{-- add --}}

<dialog id="modal_add_user" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Tambah Pengguna Baru</h3>
        <form method="POST" action="{{ route('admin.user-add') }}">
            @csrf
            <div class="form-control mb-3">
                <label class="label">
                    <span class="label-text">Nama Lengkap</span>
                </label>
                <input type="text" name="nama" class="input input-bordered" maxlength="50" required />
            </div>
            <div class="form-control mb-3">
                <label class="label">
                    <span class="label-text">Identitas (NIM / NIP)</span>
                </label>
                <input type="text" name="identitas" class="input input-bordered" maxlength="20" required />
            </div>
            <div class="form-control mb-3">
                <label class="label">
                    <span class="label-text">Email</span>
                </label>
                <input type="email" name="email" class="input input-bordered"
                       title="Masukkan alamat email yang valid"
                       oninput="validateEmail(this)"
                       required
                        maxlength="60"
                       />
                <div id="email-validation-message" class="text-xs text-red-500 mt-1 hidden">
                    Format email tidak valid
                </div>
            </div>
            <div class="form-control mb-3">
                <label class="label">
                    <span class="label-text">Password</span>
                </label>
                <input type="password" id="password" name="password" class="input input-bordered"
                    minlength="5"
                    title="Password harus minimal 5 karakter"
                    required />
                <label class="label">
                    <span class="label-text-alt text-gray-500">Minimal 5 karakter</span>
                </label>
            </div>
            <div class="form-control mb-3">
                <label class="label">
                    <span class="label-text">Hak Akses</span>
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
                    text: "{{ session('error') }}",
                    duration: 2000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #f87171, #ef4444)",
                        borderRadius: "8px",
                        boxShadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)"
                    },
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
                    style: {
                        background: "linear-gradient(to right, #4ade80, #22c55e)",
                        borderRadius: "8px",
                        boxShadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)"
                    },
                    stopOnFocus: true,
                }).showToast();
            @endif
        });
        function validateEmail(input) {
                const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                const validationMessage = document.getElementById('email-validation-message');

                if (input.value && !emailPattern.test(input.value)) {
                    validationMessage.classList.remove('hidden');
                    input.classList.add('input-error');
                } else {
                    validationMessage.classList.add('hidden');
                    input.classList.remove('input-error');
                }
            }
    </script>
@endpush
