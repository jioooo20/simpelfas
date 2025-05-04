{{-- delete --}}
<dialog id="delete_role_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <div class="p-6 text-center">
            <i class="bi bi-exclamation-triangle text-6xl text-warning mb-4 block"></i>
            <h3 class="mb-5 text-lg font-normal text-base-content">Apakah yakin ingin menghapus Hak Akses
                <span class="font-semibold text-accent">{{ isset($data) ? $data->role_nama : '' }}</span>?
            </h3>
            <p class="mb-5 text-sm text-base-content">Setelah dihapus, data tidak dapat dikembalikan</p>
            <div class="modal-action">
                @if(isset($data))
                <form action="{{ route('admin.role-deleted', ['id' => $data->role_id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-error">Hapus</button>
                </form>
                @endif
                <a href="{{ route('admin.role') }}" class="btn btn-sm">Batal</a>
            </div>
        </div>
    </div>
</dialog>

