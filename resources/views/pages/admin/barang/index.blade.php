@extends('layouts.main')
@section('judul', 'Data Barang')
@section('content')
<div class="container mx-auto px-4 py-4">
    <div class="bg-base-100 shadow-md border rounded-xl mb-3">
        <div class="flex border-b">
            <div class="px-6 py-4 font-semibold border-b-2 border-primary text-primary bg-gray-100 rounded-t-lg flex items-center gap-2">
                <i class="bi bi-box-seam"></i>
                <span>Data Barang</span>
            </div>
        </div>
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-full max-w-xs relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="searchInput" class="input input-bordered w-full pl-10" placeholder="Cari barang...">
                </div>
                <button class="btn btn-primary ml-4" id="btnTambah">
                    <i class="bi bi-plus-lg"></i> Tambah Barang
                </button>
            </div>
            <div class="table-responsive">
                <table class="table align-middle min-w-full" id="barang-table">
                    <thead style="background: #f5f8ff;">
                        <tr>
                            <th>ID</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Deskripsi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $barang)
                        <tr data-barang="{{ htmlspecialchars(json_encode($barang), ENT_QUOTES, 'UTF-8') }}" class="odd:bg-white even:bg-blue-50">
                            <td>{{ $barang->barang_id }}</td>
                            <td>{{ $barang->barang_kode }}</td>
                            <td>{{ $barang->barang_nama }}</td>
                            <td>{{ $barang->deskripsi }}</td>
                            <td class="flex gap-2 justify-center">
                                <button class="btn-detail" title="Detail">
                                    <i class="bi bi-eye text-cyan-500 hover:text-cyan-700"></i>
                                </button>
                                <button class="btn-edit" title="Edit">
                                    <i class="bi bi-pencil text-blue-500 hover:text-blue-700"></i>
                                </button>
                                <button class="btn-hapus-modal" title="Hapus" data-id="{{ $barang->barang_id }}" data-nama="{{ $barang->barang_nama }}">
                                    <i class="bi bi-trash text-red-500 hover:text-red-700"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data barang.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col md:flex-row justify-between items-center mt-2">
                <div class="text-gray-600 text-sm mb-2 md:mb-0">
                    Menampilkan {{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }} dari {{ $data->total() }} hasil
                </div>
                {{-- <div>
                    {{ $data->links('vendor.pagination.tailwind') }}
                </div> --}}
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit -->
<div id="barangModal" tabindex="-1" aria-hidden="true" class="hidden fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full bg-black/50">
  <div class="relative w-full max-w-md h-full md:h-auto mx-auto mt-20">
    <form id="formBarang" class="relative bg-white rounded-lg shadow p-6">
      @csrf
      <input type="hidden" name="_method" id="formMethod" value="POST">
      <input type="hidden" name="barang_id" id="barang_id">
      <h3 class="mb-4 text-xl font-bold" id="modalTitle">Tambah Barang</h3>
      <div class="mb-3">
        <label class="block mb-1">Kode Barang</label>
        <input type="text" name="barang_kode" id="barang_kode" class="input input-bordered w-full" required maxlength="20">
        <div class="text-red-500 text-sm" id="error_kode"></div>
      </div>
      <div class="mb-3">
        <label class="block mb-1">Nama Barang</label>
        <input type="text" name="barang_nama" id="barang_nama" class="input input-bordered w-full" required maxlength="100">
        <div class="text-red-500 text-sm" id="error_nama"></div>
      </div>
      <div class="mb-3">
        <label class="block mb-1">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" class="textarea textarea-bordered w-full"></textarea>
        <div class="text-red-500 text-sm" id="error_deskripsi"></div>
      </div>
      <div class="flex justify-end gap-2 mt-4">
        <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
        <button type="button" class="bg-white text-gray-700 border border-gray-300 hover:bg-gray-100 btn" data-modal-hide="barangModal" id="btnTutupModal">Tutup</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" tabindex="-1" aria-hidden="true" class="hidden fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full bg-black/50">
  <div class="relative w-full max-w-md h-full md:h-auto mx-auto mt-20">
    <div class="relative bg-white rounded-lg shadow p-6">
      <h3 class="mb-4 text-xl font-bold text-gray-800">Detail Barang</h3>
      <div class="mb-4" id="detailBody"></div>
      <div class="flex justify-end">
        <button type="button" class="bg-white text-gray-700 border border-gray-300 hover:bg-gray-100 btn" data-modal-hide="detailModal" id="btnTutupDetail">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="hapusModal" tabindex="-1" aria-hidden="true" class="hidden fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full bg-black/50">
  <div class="relative w-full max-w-md h-full md:h-auto mx-auto mt-20">
    <div class="relative bg-white rounded-lg shadow p-6">
      <h3 class="mb-4 text-xl font-bold text-gray-800">Konfirmasi Hapus</h3>
      <p class="mb-4">Apakah Anda yakin ingin menghapus <span class="font-semibold" id="hapusNama"></span>?</p>
      <div class="flex justify-end gap-2">
        <button type="button" class="bg-white text-gray-700 border border-gray-300 hover:bg-gray-100 btn" id="btnBatalHapus">Batal</button>
        <button type="button" class="btn btn-primary" id="btnKonfirmasiHapus">Hapus</button>
      </div>
    </div>
  </div>
</div>

@push('skrip')
<script>
function showModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function hideModal(id) {
    document.getElementById(id).classList.add('hidden');
}
function clearForm() {
    document.getElementById('formBarang').reset();
    clearError();
}
function clearError() {
    document.getElementById('error_kode').innerText = '';
    document.getElementById('error_nama').innerText = '';
    document.getElementById('error_deskripsi').innerText = '';
}

// Live search
document.getElementById('searchInput').addEventListener('keyup', function() {
    let value = this.value.toLowerCase();
    document.querySelectorAll('#barang-table tbody tr').forEach(function(row) {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(value) ? '' : 'none';
    });
});

let barangIdHapus = null;

document.addEventListener('DOMContentLoaded', function () {
    // Tambah Barang
    document.getElementById('btnTambah').addEventListener('click', function() {
        clearForm();
        document.getElementById('modalTitle').innerText = 'Tambah Barang';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('barang_id').value = '';
        showModal('barangModal');
    });

    // Tutup Modal Tambah/Edit
    document.getElementById('btnTutupModal').addEventListener('click', function() {
        hideModal('barangModal');
    });

    // Tutup Modal Detail
    document.getElementById('btnTutupDetail').addEventListener('click', function() {
        hideModal('detailModal');
    });

    // Tutup Modal Hapus
    document.getElementById('btnBatalHapus').addEventListener('click', function() {
        hideModal('hapusModal');
        barangIdHapus = null;
    });

    // Konfirmasi Hapus
    document.getElementById('btnKonfirmasiHapus').addEventListener('click', function() {
        if(barangIdHapus) {
            fetch(`/admin/barang/${barangIdHapus}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                hideModal('hapusModal');
                if(data.success) {
                    document.querySelector(`button[data-id="${barangIdHapus}"]`).closest('tr').remove();
                    Toastify({ text: "Data berhasil dihapus!", backgroundColor: "#22c55e", duration: 2000 }).showToast();
                } else {
                    Toastify({ text: "Gagal menghapus data!", backgroundColor: "#ef4444", duration: 2000 }).showToast();
                }
                barangIdHapus = null;
            })
            .catch(() => {
                hideModal('hapusModal');
                Toastify({ text: "Terjadi kesalahan!", backgroundColor: "#ef4444", duration: 2000 }).showToast();
                barangIdHapus = null;
            });
        }
    });

    // Event delegation untuk aksi di tabel
    document.getElementById('barang-table').addEventListener('click', function(e) {
        // Detail
        if (e.target.closest('.btn-detail')) {
            let tr = e.target.closest('tr');
            let id = tr.querySelector('.btn-hapus-modal, .btn-hapus').getAttribute('data-id');
            fetch(`/admin/barang/${id}`)
                .then(res => res.json())
                .then(barang => {
                    let html = `<table class="table w-full">
                        <tr><th>ID</th><td>${barang.barang_id}</td></tr>
                        <tr><th>Kode</th><td>${barang.barang_kode}</td></tr>
                        <tr><th>Nama</th><td>${barang.barang_nama}</td></tr>
                        <tr><th>Deskripsi</th><td>${barang.deskripsi ?? ''}</td></tr>
                    </table>`;
                    document.getElementById('detailBody').innerHTML = html;
                    showModal('detailModal');
                })
                .catch(() => {
                    Toastify({ text: "Gagal mengambil data!", backgroundColor: "#ef4444", duration: 2000 }).showToast();
                });
        }

        // Edit
        if (e.target.closest('.btn-edit')) {
            let tr = e.target.closest('tr');
            let id = tr.querySelector('.btn-hapus-modal, .btn-hapus').getAttribute('data-id');
            clearForm();
            fetch(`/admin/barang/${id}`)
                .then(res => res.json())
                .then(barang => {
                    document.getElementById('modalTitle').innerText = 'Edit Barang';
                    document.getElementById('formMethod').value = 'PUT';
                    document.getElementById('barang_id').value = barang.barang_id;
                    document.getElementById('barang_kode').value = barang.barang_kode;
                    document.getElementById('barang_nama').value = barang.barang_nama;
                    document.getElementById('deskripsi').value = barang.deskripsi ?? '';
                    showModal('barangModal');
                })
                .catch(() => {
                    Toastify({ text: "Gagal mengambil data!", backgroundColor: "#ef4444", duration: 2000 }).showToast();
                });
        }

        // Hapus (pakai modal)
        if (e.target.closest('.btn-hapus-modal')) {
            let btn = e.target.closest('.btn-hapus-modal');
            barangIdHapus = btn.getAttribute('data-id');
            document.getElementById('hapusNama').innerText = btn.getAttribute('data-nama');
            showModal('hapusModal');
        }
    });

    // Submit Tambah/Edit Barang (AJAX)
    document.getElementById('formBarang').addEventListener('submit', function(e) {
        e.preventDefault();
        clearError();
        let id = document.getElementById('barang_id').value;
        let method = document.getElementById('formMethod').value;
        let url = method === 'POST' ? '/admin/barang' : `/admin/barang/${id}`;
        let formData = new FormData(this);
        formData.append('_method', method);

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async res => {
            if(res.ok) {
                location.reload();
            } else if(res.status === 422) {
                let data = await res.json();
                if(data.errors) {
                    if(data.errors.barang_kode) document.getElementById('error_kode').innerText = data.errors.barang_kode[0];
                    if(data.errors.barang_nama) document.getElementById('error_nama').innerText = data.errors.barang_nama[0];
                    if(data.errors.deskripsi) document.getElementById('error_deskripsi').innerText = data.errors.deskripsi[0];
                }
            } else {
                Toastify({ text: "Terjadi kesalahan!", backgroundColor: "#ef4444", duration: 2000 }).showToast();
            }
        })
        .catch(() => Toastify({ text: "Terjadi kesalahan!", backgroundColor: "#ef4444", duration: 2000 }).showToast());
    });
});
</script>
@endpush
@endsection