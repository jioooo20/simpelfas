{{-- Modal Cetak Laporan --}}
<dialog id="cetak_laporan_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Cetak Laporan Perbaikan</h3>
        <p class="py-4">Pilih format laporan yang ingin dicetak:</p>
        <div class="flex flex-col gap-3">
            <button class="btn btn-outline btn-primary">
                <i class="bi bi-file-pdf mr-2"></i>Cetak PDF
            </button>
            <button class="btn btn-outline">
                <i class="bi bi-file-excel mr-2"></i>Ekspor Excel
            </button>
        </div>
        <div class="modal-action">
            <form method="dialog">
                <button class="btn">Tutup</button>
            </form>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
