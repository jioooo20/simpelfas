{{-- edit --}}
<dialog id="edit_role_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <div class="flex items-center justify-between border-b pb-4">
            <h3 class="text-lg font-bold">Edit Hak Akses</h3>
        </div>
        <form class=" space-y-4" action="{{ isset($data) ? route('admin.role-update', $data->role_id) : '#' }}" method="POST">
            @csrf
            @method('PUT')
            <div>
                <label for="role_kode" class="block mb-2 text-sm font-medium">Kode</label>
                <input type="text" name="role_kode" id="role_kode" value="{{ isset($data) ? $data->role_kode : '' }}" class="input input-bordered w-full" required>
            </div>
            <div>
                <label for="role_nama" class="block mb-2 text-sm font-medium">Nama Role</label>
                <input type="text" name="role_nama" id="role_nama" value="{{ isset($data) ? $data->role_nama : '' }}" class="input input-bordered w-full" required>
            </div>
            <div>
                <label for="role_deskripsi" class="block mb-2 text-sm font-medium">Deskripsi</label>
                <textarea id="role_deskripsi" name="role_deskripsi" rows="3" class="textarea textarea-bordered w-full" required>{{ isset($data) ? $data->role_deskripsi : '' }}</textarea>
            </div>
            <div class="modal-action">
                <a href="{{ route('admin.role') }}" class="btn btn-sm">Batal</a>
                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</dialog>

