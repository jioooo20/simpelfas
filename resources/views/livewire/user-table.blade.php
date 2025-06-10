<div>
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-4 flex-1">
            <!-- Search -->
            <div class="form-control w-full sm:w-80">
                <input type="text" wire:model.live="search" placeholder="Cari Pengguna..."
                    class="input input-bordered w-full" />
            </div>

            <!-- Role Filter -->
            <div class="form-control w-full sm:w-48">
                <select wire:model.live="roleFilter" class="select select-bordered w-full">
                    <option value="">Semua Role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->role_id }}">{{ $role->role_nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-2">
            <button wire:click="openAddModal" class="btn btn-primary text-white">
                <i class="fas fa-fas fa-plus text-white"></i>
                Tambah User
            </button>
            <button wire:click="openImportModal" class="bg-green-600 hover:bg-green-700 btn text-white">
                <i class="fas fa-upload"></i>
                Import Excel
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-base-200">
                    <th class="flex gap-2 justify-center">No</th>
                    <th>Identitas</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th class="flex gap-2 justify-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                    <tr>
                        <td class="flex gap-2 justify-center">{{ $users->firstItem() + $index }}</td>
                        <td>{{ $user->identitas }}</td>
                        <td>{{ $user->nama }}</td>
                        <td>{{ $user->email }}</td>
                        <td> {{ $user->role->role_nama }} </td>
                        <td>
                            <div class="flex gap-3 justify-center">
                                <button wire:click="openEditModal({{ $user->user_id }})"
                                    class="text-indigo-400 hover:text-indigo-800">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button wire:click="openDeleteModal({{ $user->user_id }})"
                                    class="text-red-500 hover:text-red-500">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8">Tidak ada data user</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between mt-6">
        <div class="text-sm text-gray-500">
            Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} hasil
        </div>
        <div class="join">


            @php
                $startPage = max($users->currentPage() - 1, 1);
                $endPage = min($startPage + 2, $users->lastPage());

                if ($endPage - $startPage < 2) {
                    $startPage = max($endPage - 2, 1);
                }
            @endphp

            @for ($page = $startPage; $page <= $endPage; $page++)
                <a href="#" wire:click.prevent="gotoPage({{ $page }})">
                    <button class="join-item btn btn-sm {{ $users->currentPage() == $page ? 'btn-active' : '' }}">
                        {{ $page }}
                    </button>
                </a>
            @endfor

        </div>
    </div>

    <!-- Add User Modal -->
    @if ($showAddModal)
        <div class="modal modal-open">
            <div class="modal-box">
                <h3 class="font-bold text-lg mb-4">Tambah User</h3>
                <form wire:submit="store">
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text">Role<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span>
                        </label>
                        <select wire:model="role_id" class="select select-bordered w-full">
                            <option value="">Pilih Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->role_id }}">{{ $role->role_nama }}</option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control mb-4">
                        <label class="label">
                             <span class="label-text">Identitas<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span>
                        </label>
                        <input type="text" wire:model="identitas" class="input input-bordered w-full" placeholder="Nomor Identitas Minimal 10 Karakter"/>
                        @error('identitas')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control mb-4">
                        <label class="label">
                             <span class="label-text">Nama<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span>
                        </label>
                        <input type="text" wire:model="nama" class="input input-bordered w-full" placeholder="Masukkan Nama Pengguna"/>
                        @error('nama')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control mb-4">
                        <label class="label">
                             <span class="label-text">Email<span class="text-red-500 text-sm" title="Wajib diisi">* </span></span>
                        </label>
                        <input type="email" wire:model="email" class="input input-bordered w-full" placeholder="Masukkan Alamat Email yang valid"/>
                        @error('email')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control mb-4">
                        <label class="label">
                             <span class="label-text">Password<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span>
                        </label>
                        <input type="password" wire:model="password" class="input input-bordered w-full" placeholder="Masukkan Password Minimal 5 Karakter"/>
                        @error('password')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="modal-action">
                        <button type="button" wire:click="closeModals" class="btn btn-sm">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Edit User Modal -->
    @if ($showEditModal)
        <div class="modal modal-open">
            <div class="modal-box">
                <h3 class="font-bold text-lg mb-4">Edit User</h3>
                <form wire:submit="update">
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text">Role<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span></label>
                        <select wire:model="role_id" class="select select-bordered w-full">
                            <option value="">Pilih Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->role_id }}">{{ $role->role_nama }}</option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control mb-4">
                        <label class="label">
                             <span class="label-text">Identitas<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span>
                            </label>
                        <input type="text" wire:model="identitas" class="input input-bordered w-full" />
                        @error('identitas')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control mb-4">
                        <label class="label">
                             <span class="label-text">Nama<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span>
                            </label>
                        <input type="text" wire:model="nama" class="input input-bordered w-full" />
                        @error('nama')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control mb-4">
                        <label class="label">
                             <span class="label-text">Email<span class="text-red-500 text-sm" title="Wajib diisi">* </span></span>
                            </label>
                        <input type="email" wire:model="email" class="input input-bordered w-full" />
                        @error('email')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control mb-4">
                        <label class="label">
                             <span class="label-text">Password<span class="text-red-500 text-sm" title="Wajib diisi"> *</span></span>
                            </label>
                        <input type="password" wire:model="password" class="input input-bordered w-full" placeholder="Kosongkan jika tidak diubah"/>
                        @error('password')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="modal-action">
                        <button type="button" wire:click="closeModals" class="btn btn-sm">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary text-white">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Import Modal -->
    @if ($showImportModal)
        <div class="modal modal-open">
            <div class="modal-box">
                <h3 class="font-bold text-lg mb-4">Import User dari Excel</h3>

                <!-- Import Guide -->
                <div class="alert alert-info mb-4">
                    <div>
                        <h4 class="font-semibold">Panduan Import:</h4>
                        <ol class="list-decimal list-inside mt-2 space-y-1">
                            <li>Download template Excel</li>
                            <li>Isi data sesuai format template</li>
                            <li>Upload file yang sudah diisi</li>
                            <li>Klik Import untuk memproses data</li>
                        </ol>
                    </div>
                </div>

                <div class="mb-4">
                    <a href="{{ asset('template_user.xlsx') }}" download class="btn btn-outline btn-sm">
                        <i class="bi bi-download"></i>
                        Download Template
                    </a>
                </div>

                <form wire:submit="import">
                    <div class="form-control mb-4">
                        <label class="label">Upload File Excel</label>
                        <input type="file" wire:model="importFile" accept=".xlsx,.xls"
                            class="file-input file-input-bordered w-full" />
                        @error('importFile')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="modal-action">
                        <button type="button" wire:click="closeModals" class="btn btn-sm">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary text-white">Import</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="modal modal-open">
            <div class="modal-box">
                <h3 class="font-bold text-lg mb-4">Konfirmasi Hapus</h3>
                <p class="mb-4">Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.</p>

                <div class="modal-action">
                    <button wire:click="closeModals" class="btn btn-sm">Batal</button>
                    <button wire:click="delete" class="btn btn-sm btn-error">Hapus</button>
                </div>
            </div>
        </div>
    @endif
</div>
