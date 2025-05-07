@extends('layouts.main')
@section('judul', 'Kelola Pengguna')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <!-- Header section -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Pengelolaan Pengguna</h1>
            <div class="flex gap-3">
                <button class="bg-green-500 text-white btn btn-outline btn-sm flex items-center gap-2">
                    <i class="fas fa-file-excel"></i>Impor Data Pengguna
                </button>
                <button class="btn btn-primary btn-sm flex items-center gap-2" onclick="modal_add_user.showModal()">
                    <i class="fas fa-user-plus"></i>Tambah Pengguna
                </button>

                <!-- Modal for adding user -->
                <dialog id="modal_add_user" class="modal">
                    <div class="modal-box">
                        <h3 class="font-bold text-lg mb-4">Tambah Pengguna Baru</h3>
                        <form method="POST" action="{{ route('admin.user-add') }}">
                            @csrf
                            <div class="form-control mb-3">
                                <label class="label">
                                    <span class="label-text">Nama Lengkap</span>
                                </label>
                                <input type="text" name="nama" class="input input-bordered" required />
                            </div>
                            <div class="form-control mb-3">
                                <label class="label">
                                    <span class="label-text">Identitas (NIM / NIP)</span>
                                </label>
                                <input type="text" name="identitas" class="input input-bordered" required />
                            </div>
                            <div class="form-control mb-3">
                                <label class="label">
                                    <span class="label-text">Email</span>
                                </label>
                                <input type="email" name="email" class="input input-bordered"
                                       {{-- pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" --}}
                                       title="Masukkan alamat email yang valid"
                                       oninput="validateEmail(this)"
                                       required />
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
            </div>
        </div>



        <!-- Stats boxes -->
        {{-- <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-base-100 shadow-sm rounded-lg p-4 border">
                <div class="flex items-center gap-3">
                    <i class="bi bi-people text-xl"></i>
                    <div>Total admins</div>
                </div>
                <div class="text-3xl font-bold mt-2">841</div>
            </div>
            <div class="bg-base-100 shadow-sm rounded-lg p-4 border">
                <div class="flex items-center gap-3">
                    <i class="bi bi-building text-xl"></i>
                    <div>Organizations</div>
                </div>
                <div class="text-3xl font-bold mt-2">23</div>
            </div>
            <div class="bg-base-100 shadow-sm rounded-lg p-4 border">
                <div class="flex items-center gap-3">
                    <i class="bi bi-circle-fill text-emerald-600 text-sm"></i>
                    <div>Active</div>
                </div>
                <div class="text-3xl font-bold mt-2 text-emerald-600">782</div>
            </div>
            <div class="bg-base-100 shadow-sm rounded-lg p-4 border">
                <div class="flex items-center gap-3">
                    <i class="bi bi-circle-fill text-amber-500 text-sm"></i>
                    <div>Inactive</div>
                </div>
                <div class="text-3xl font-bold mt-2 text-amber-500">64</div>
            </div>
        </div> --}}

        <!-- Administrators section -->
        <div class="bg-base-100 shadow-md border rounded-xl p-6">

            <!-- Search bar -->
            <div class="flex justify-between items-center mb-4">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="bi bi-search text-gray-400"></i>
                    </div>
                    <input type="text" class="input input-bordered w-full pl-10" placeholder="Search...">
                </div>
                <button class="btn btn-outline ml-3 flex items-center gap-1">
                    Filter <i class="bi bi-funnel"></i>
                </button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full relative" id="user-table">
                    <thead>
                        <tr class="bg-base-200">
                            <th>ID</th>
                            <th>Identitas</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Hak Akses</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($table as $user)
                            <tr class="hover">
                                <td>{{ $user->user_id }}</td>
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
                                        {{ $user->role->role_nama }}</span>
                                        </span>
                                    </div>
                                </td>
                                <td class="flex gap-2 justify-center">
                                    <a href="{{ route('admin.role-edit', $user->role->role_id) }}"
                                        class="text-indigo-400 hover:text-indigo-800-focus">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="{{ route('admin.role-delete', $user->role->role_id) }}"
                                        class="text-red-500 hover:text-red-500-focus">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            {{-- <div class="flex items-center justify-between mt-6">
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
            </div> --}}
        </div>
    </div>

    @include('pages.admin.manage-user.add')
@endsection


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
