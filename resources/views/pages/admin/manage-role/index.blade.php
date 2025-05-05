@extends('layouts.main')
@section('judul', 'Hak Akses')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <div class="bg-base-100 shadow-lg border border-base-content rounded-xl p-6">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h1 class="text-2xl font-bold text-base-content text-center md:text-left">Pengelolaan Hak Akses</h1>
                <button onclick="add_role_modal.showModal()"
                    class="btn bg-blue-200 text-black hover:bg-blue-300 border-blue-200 hover:border-blue-300 btn-sm rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 inline-flex items-center gap-2 w-full md:w-auto">
                    <i class="bi bi-plus-circle text-lg"></i>
                    <span>Tambah Hak Akses</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-base-200">
                            <th>ID</th>
                            <th>Kode</th>
                            <th>Nama Role</th>
                            <th>Deskripsi</th>
                            <th>Jumlah Pengguna</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($table as $role)
                            <tr class="hover">
                                <td>{{ $role->role_id }}</td>
                                <td>{{ $role->role_kode }}</td>
                                <td class="font-medium">{{ $role->role_nama }}</td>
                                <td>{{ $role->role_deskripsi }}</td>
                                <td>{{ $role->jumlah_user }}</td>
                                <td class="flex gap-2">
                                    <a href="{{ route('admin.role-edit', $role->role_id) }}"
                                        class="text-indigo-400 hover:text-indigo-800-focus">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="{{ route('admin.role-delete', $role->role_id) }}"
                                        class="text-red-500 hover:text-red-500-focus">
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
        </div>
    </div>

    {{-- add --}}
    @include('pages.admin.manage-role.add')
    {{-- edit --}}
    @include('pages.admin.manage-role.edit')
    {{-- delete --}}
    @include('pages.admin.manage-role.delete')

@endsection

@push('skrip')
    @if (isset($data))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const currentUrl = window.location.href;

                if (currentUrl.includes('edit')) {
                    document.getElementById('edit_role_modal').showModal();
                }

                if (currentUrl.includes('delete')) {
                    document.getElementById('delete_role_modal').showModal();
                }
            });
        </script>
    @endif
@endpush
