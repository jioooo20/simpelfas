<div>
    <!-- Session Messages -->
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2500)" x-show="show" class="alert bg-green-600 text-white mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2500)" x-show="show" class="alert bg-red-500 text-white mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Search Input -->
    <div class="flex justify-between items-center mb-4">
        <div class="relative w-full">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="bi bi-search text-gray-400"></i>
            </div>
            <input wire:model.live="search" type="text" class="input input-bordered w-full pl-10"
                placeholder="Search by name, email, or identity..." />
        </div>
    </div>

    <!-- User Table -->
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
                            <!-- Edit button - now calls component method directly -->
                            <a href="#" wire:click.prevent="editUser({{ $user->user_id ?? $user->id }})"
                               class="text-indigo-400 hover:text-indigo-800">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <!-- Delete button - now calls component method -->
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

    <!-- Pagination -->
    <div class="flex items-center justify-between mt-6">
        <div class="text-sm text-gray-500">
            Showing {{ $table->firstItem() }} to {{ $table->lastItem() }} of {{ $table->total() }} results
        </div>
        <div class="join">
            <a href="{{ $table->previousPageUrl() }}">
                <button class="join-item btn btn-sm" {{ $table->onFirstPage() ? 'disabled' : '' }}>«</button>
            </a>

            @php
                $startPage = max($table->currentPage() - 1, 1);
                $endPage = min($startPage + 2, $table->lastPage());

                if ($endPage - $startPage < 2) {
                    $startPage = max($endPage - 2, 1);
                }
            @endphp

            @for ($page = $startPage; $page <= $endPage; $page++)
                <a href="{{ $table->url($page) }}">
                    <button class="join-item btn btn-sm {{ $table->currentPage() == $page ? 'btn-active' : '' }}">
                        {{ $page }}
                    </button>
                </a>
            @endfor

            <a href="{{ $table->nextPageUrl() }}">
                <button class="join-item btn btn-sm"
                    {{ $table->currentPage() == $table->lastPage() ? 'disabled' : '' }}>»</button>
            </a>
        </div>
    </div>

    <!-- Edit User Modal -->
    @if($showEditModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-lg shadow-xl">
            <h2 class="text-xl font-semibold mb-4">Edit User</h2>

            <form wire:submit="updateUser">
                <div class="space-y-4">
                    <input type="text" wire:model="nama" placeholder="Nama" class="input input-bordered w-full">
                    {{-- @error('nama')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror --}}

                    <input type="email" wire:model="email" placeholder="Email" class="input input-bordered w-full">
                    {{-- @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror --}}

                    <input type="text" wire:model="identitas" placeholder="Identitas" class="input input-bordered w-full">
                    {{-- @error('identitas') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror --}}

                    <select wire:model="role_id" class="select select-bordered w-full">
                        <option value="">Pilih Hak Akses</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->role_nama }}</option>
                        @endforeach
                    </select>
                    {{-- @error('role_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror --}}
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" wire:click="$set('showEditModal', false)" class="btn btn-ghost">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($confirmingUserDeletion)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-xl">
            <h2 class="text-xl font-semibold mb-4">Konfirmasi Hapus</h2>
            <p class="mb-4">Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.</p>

            <div class="flex justify-end gap-2">
                <button wire:click="$set('confirmingUserDeletion', false)" class="btn btn-ghost">Batal</button>
                <button wire:click="deleteUser" class="btn btn-error">Hapus</button>
            </div>
        </div>
    </div>
    @endif
</div>
