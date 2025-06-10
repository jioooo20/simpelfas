<div>
    @push('skrip')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Livewire.on('showSuccessToast', (message) => {
                    Toastify({
                        text: `<div class="flex items-center gap-3">
                              <i class="bi bi-check-circle-fill text-xl"></i>
                              <span>${message}</span>
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
                        onClick: function() {}
                    }).showToast();
                });

                Livewire.on('showErrorToast', (message) => {
                    Toastify({
                        text: `<div class="flex items-center gap-3">
                              <i class="bi bi-exclamation-circle-fill text-xl"></i>
                              <span>${message}</span>
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
                        onClick: function() {}
                    }).showToast();
                });

                // Password confirmation validation
                function validatePasswordMatch() {
                    const password = document.querySelector('input[wire\\:model="password"]');
                    const passwordConfirmation = document.querySelector('input[wire\\:model="password_confirmation"]');

                    if (password && passwordConfirmation) {
                        // Function to show toast
                        function showMismatchToast() {
                            Toastify({
                                text: `<div class="flex items-center gap-3">
                                          <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                                          <span>Password dan konfirmasi password tidak sama</span>
                                       </div>`,
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                                className: "rounded-lg shadow-md",
                                stopOnFocus: true,
                                escapeMarkup: false,
                                style: {
                                    padding: "12px 20px",
                                    fontWeight: "500",
                                    minWidth: "300px"
                                }
                            }).showToast();
                        }

                        // Visual feedback on input for confirmation field
                        passwordConfirmation.addEventListener('input', function() {
                            if (password.value !== '' && passwordConfirmation.value !== '' &&
                                password.value !== passwordConfirmation.value) {
                                passwordConfirmation.classList.add('input-error');
                            } else {
                                passwordConfirmation.classList.remove('input-error');
                            }
                        });

                        // Show toast on blur for confirmation field if mismatch
                        passwordConfirmation.addEventListener('blur', function() {
                            if (password.value !== '' && passwordConfirmation.value !== '' &&
                                password.value !== passwordConfirmation.value) {
                                showMismatchToast();
                            }
                        });

                        // Visual feedback and toast on input for password field if confirmation is already filled and mismatch
                        password.addEventListener('input', function() {
                            if (passwordConfirmation.value !== '') {
                                if (password.value !== passwordConfirmation.value) {
                                    passwordConfirmation.classList.add('input-error');
                                } else {
                                    passwordConfirmation.classList.remove('input-error');
                                }
                            }
                        });

                        // Show toast on blur for password field if confirmation is already filled and mismatch
                        password.addEventListener('blur', function() {
                            if (passwordConfirmation.value !== '' && password.value !== '' &&
                                password.value !== passwordConfirmation.value) {
                                showMismatchToast();
                            }
                        });
                    }
                }

                // Initialize validation when modal opens
                Livewire.on('modalOpened', () => {
                    setTimeout(validatePasswordMatch, 100);
                });

                // Also initialize on page load
                validatePasswordMatch();
            });
        </script>
    @endpush

    {{-- search --}}
    <div class="flex justify-between items-center mb-4">
        <div class="relative w-full">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="bi bi-search text-gray-400"></i>
            </div>
            <input wire:model.live="search" type="text" class="input input-bordered w-full pl-10"
                placeholder="Cari berdasarkan nama, email, atau identitas..." />
        </div>
    </div>

    {{-- table --}}
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full relative" id="user-table">
            <thead>
                <tr class="bg-base-200">
                    <th class="flex gap-2 justify-center">ID</th>
                    <th>Identitas</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Hak Akses</th>
                    <th class="flex gap-2 justify-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($table as $user)
                    <tr class="hover">
                        <td class="flex gap-2 justify-center">{{ $user->user_id }}</td>
                        <td>{{ $user->identitas }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <img class="h-8 w-8 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&background=4338ca&color=fff"
                                    alt="{{ $user->nama }}" loading="lazy">
                                <div>
                                    <div class="font-medium">{{ $user->nama }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-sm text-gray-500">{{ $user->email }}</td>
                        <td>
                            <div class="flex items-center">
                                {{ $user->role->role_nama }}
                            </div>
                        </td>
                        <td class="flex gap-2 justify-center">
                            <a href="#" wire:click.prevent="editUser({{ $user->user_id ?? $user->id }})"
                                class="text-indigo-400 hover:text-indigo-800">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <a href="#" wire:click.prevent="confirmDelete({{ $user->user_id ?? $user->id }})"
                                class="text-red-500 hover:text-red-500">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between mt-6">
        <div class="text-sm text-gray-500">
            Menampilkan {{ $table->firstItem() }} - {{ $table->lastItem() }} dari {{ $table->total() }} hasil
        </div>
        <div class="join">
            @php
                $startPage = max($table->currentPage() - 1, 1);
                $endPage = min($startPage + 2, $table->lastPage());

                if ($endPage - $startPage < 2) {
                    $startPage = max($endPage - 2, 1);
                }
            @endphp

            @for ($page = $startPage; $page <= $endPage; $page++)
                <button wire:click="gotoPage({{ $page }})" class="join-item btn btn-sm {{ $table->currentPage() == $page ? 'btn-active' : '' }}">
                    {{ $page }}
                </button>
            @endfor
        </div>
    </div>

    <!-- Edit User Modal -->
    @if ($showEditModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-lg shadow-xl">
                <h2 class="text-xl font-semibold mb-4">Edit User</h2>

                <form wire:submit="updateUser">
                    <div class="space-y-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Nama<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span>
                            </label>
                            <input type="text" wire:model="nama" placeholder="Nama"
                                class="input input-bordered w-full">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Email<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span>
                            </label>
                            <input type="email" wire:model="email" placeholder="Email"
                                class="input input-bordered w-full">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Identitas<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span>
                            </label>
                            <input type="text" wire:model="identitas" placeholder="Identitas"
                                class="input input-bordered w-full">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Hak Akses<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span>
                            </label>
                            <select wire:model="role_id" class="select select-bordered w-full">
                                <option value="">Pilih Hak Akses</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->role_id }}">{{ $role->role_nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Password Baru</span>
                                <span class="label-text-alt text-gray-500">(Kosongkan jika tidak ingin mengubah)</span>
                            </label>
                            <input type="password" wire:model="password" placeholder="Password baru"
                                class="input input-bordered w-full">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Konfirmasi Password Baru</span>
                            </label>
                            <input type="password" wire:model="password_confirmation" placeholder="Konfirmasi password baru"
                                class="input input-bordered w-full" id="password-confirmation">
                            <label class="label">
                                <span class="label-text-alt text-red-500" id="password-match-error" style="display: none;">
                                    Password tidak sama
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button" wire:click="$set('showEditModal', false)"
                            class="btn btn-sm btn-ghost">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($confirmingUserDeletion)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-xl">
                <div class="text-center mb-6">
                    <div class="flex justify-center">
                        <i class="bi bi-exclamation-triangle-fill text-6xl text-red-500 mb-2"></i>
                    </div>
                    <h2 class="text-xl font-bold">Konfirmasi Hapus</h2>
                    <p class="text-gray-500 mt-1">Tindakan ini tidak dapat dibatalkan</p>
                </div>

                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-5 rounded">
                    <p class="text-md">
                        Anda akan menghapus pengguna <span class="font-semibold">{{ $nama }}</span>
                        dari sistem. Semua data terkait pengguna ini akan dihapus secara permanen.
                    </p>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="$set('confirmingUserDeletion', false)" class="btn btn-outline btn-sm">
                        <i class="bi bi-x mr-1"></i> Batal
                    </button>
                    <button wire:click="deleteUser" class="btn btn-error btn-sm">
                        <i class="bi bi-trash mr-1"></i> Hapus Pengguna
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
