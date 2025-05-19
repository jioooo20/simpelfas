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
            });
        </script>
    @endpush

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-base-200">
                    <th class="flex gap-2 justify-center">ID</th>
                    <th>Kode</th>
                    <th>Nama Role</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Pengguna</th>
                    <th class="flex gap-2 justify-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($table as $role)
                    <tr class="hover">
                        <td class="flex gap-2 justify-center">{{ $role->role_id }}</td>
                        <td>{{ $role->role_kode }}</td>
                        <td class="font-medium">{{ $role->role_nama }}</td>
                        <td>{{ $role->role_deskripsi }}</td>
                        <td>{{ $role->jumlah_user }}</td>
                        <td class="flex gap-2 justify-center">
                            <button wire:click="openEditModal({{ $role->role_id }})"
                                class="text-indigo-400 hover:text-indigo-800-focus">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button wire:click="openDeleteModal({{ $role->role_id }})"
                                class="text-red-500 hover:text-red-500-focus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between mt-6">
        <div class="text-sm text-gray-500">
            Showing {{ $table->firstItem() }} to {{ $table->lastItem() }} of {{ $table->total() }} results
        </div>

        <div class="join">
            @foreach ($table->getUrlRange(1, $table->lastPage()) as $page => $url)
                <a href="{{ $url }}">
                    <button class="join-item btn-sm {{ $table->currentPage() == $page ? 'btn-active' : '' }}">
                        {{ $page }}
                    </button>
                </a>
            @endforeach
        </div>
    </div>

    <div x-data="{ show: @entangle('editModal') }">
        <div x-show="show" x-cloak class="modal modal-open">
            <div class="modal-box">
                <div class="flex items-center justify-between border-b pb-4">
                    <h3 class="text-lg font-bold">Edit Hak Akses</h3>
                </div>
                <form wire:submit.prevent="update" class="space-y-4">
                    <div>
                        <label for="role_kode" class="block mb-2 text-sm font-medium">Kode</label>
                        <input type="text" wire:model="role_kode" id="role_kode" class="input input-bordered w-full"
                            required>
                    </div>
                    <div>
                        <label for="role_nama" class="block mb-2 text-sm font-medium">Nama Role</label>
                        <input type="text" wire:model="role_nama" id="role_nama" class="input input-bordered w-full"
                            required>
                    </div>
                    <div>
                        <label for="role_deskripsi" class="block mb-2 text-sm font-medium">Deskripsi</label>
                        <textarea id="role_deskripsi" wire:model="role_deskripsi" rows="3" class="textarea textarea-bordered w-full"
                            required></textarea>
                    </div>
                    <div class="modal-action">
                        <button type="button" wire:click="closeEditModal" class="btn btn-sm">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div x-data="{ show: @entangle('deleteModal') }">
        <div x-show="show" x-cloak class="modal modal-open">
            <div class="modal-box">
                <div class="p-6 text-center">
                    <i class="bi bi-exclamation-triangle text-6xl text-red-500 mb-4 block"></i>
                    <h3 class="mb-5 text-lg font-normal text-base-content">Apakah yakin ingin menghapus Hak Akses
                        <span class="font-semibold text-accent">{{ $role_nama }}</span>?
                    </h3>
                    <p class="mb-5 text-sm text-base-content">Setelah dihapus, data tidak dapat dikembalikan</p>
                    <div class="modal-action">
                        <button type="button" wire:click="delete" class="btn btn-sm btn-error">Hapus</button>
                        <button type="button" wire:click="closeDeleteModal" class="btn btn-sm">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
