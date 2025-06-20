{{-- filepath: d:\Coolyeah\SEM4\A PBL\code app\simpelfas\resources\views\livewire\rekomendasi-biaya-perbaikan.blade.php --}}
<div>
    @if ($facilities->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach ($facilities as $facility)
                <div class="card bg-white shadow-lg border border-gray-200">
                    <div class="card-body p-6">
                        <!-- Facility Header -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="card-title text-lg font-semibold text-gray-800">
                                    {{ $facility->barang->barang_nama }} - {{ substr($facility->fasilitas_kode, -2) }}
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="bi bi-geo-alt-fill mr-1 text-red-500"></i>
                                    {{ $facility->ruang->ruang_nama }} - {{ $facility->ruang->lantai->lantai_nama }} -
                                    {{ $facility->ruang->lantai->gedung->gedung_nama }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    Kode: {{ $facility->fasilitas_kode }}
                                </p>
                            </div>
                            <div class="badge badge-outline">
                                {{ $facility->pelaporan->count() }} Laporan
                            </div>
                        </div>

                        <!-- Reports List -->
                        <div class="mb-4">
                            <h4 class="font-medium text-gray-700 mb-3">Detail Laporan:</h4>
                            <div class="space-y-3 max-h-60 overflow-y-auto">
                                @foreach ($facility->pelaporan as $pelaporan)
                                    <div class="bg-gray-50 rounded-lg p-3 border-l-4 border-blue-400">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="text-sm font-medium text-gray-800">
                                                {{ $pelaporan->pelaporan_kode }}
                                            </span>
                                            @php
                                                $latestStatus = $pelaporan->statusPelaporan->last();
                                            @endphp
                                            <span
                                                class="badge badge-sm
                                                {{ $latestStatus->status_pelaporan === 'Menunggu' ? 'badge-warning' : 'badge-success' }}">
                                                {{ $latestStatus->status_pelaporan }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <p class="text-sm text-gray-600 mb-2">
                                                {{ Str::limit($pelaporan->pelaporan_deskripsi, 100) }}
                                            </p>
                                            <p class="text-sm text-gray-600 text-right">
                                                {{ $pelaporan->created_at->format('d M Y H:i') }}
                                            </p>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <p class="text-xs text-gray-500">
                                                Dilaporkan oleh: {{ $pelaporan->user->nama }}
                                            </p>
                                            <div class="flex items-center gap-2">
                                                @if ($pelaporan->pelaporan_gambar)
                                                    @php
                                                        $images = is_string($pelaporan->pelaporan_gambar)
                                                            ? json_decode($pelaporan->pelaporan_gambar, true)
                                                            : $pelaporan->pelaporan_gambar;
                                                        $images = is_array($images)
                                                            ? $images
                                                            : [$pelaporan->pelaporan_gambar];
                                                    @endphp
                                                    <button class="btn btn-xs btn-outline btn-primary"
                                                        onclick="viewPhotos{{ $pelaporan->pelaporan_id }}.showModal()">
                                                        <i class="bi bi-image mr-1"></i>
                                                        {{ count($images) }} Foto
                                                    </button>

                                                    <!-- Photo Modal -->
                                                    <dialog id="viewPhotos{{ $pelaporan->pelaporan_id }}"
                                                        class="modal">
                                                        <div class="modal-box max-w-4xl">
                                                            <div class="flex justify-between items-center mb-4">
                                                                <h3 class="font-bold text-lg">Foto Laporan -
                                                                    {{ $pelaporan->pelaporan_kode }}</h3>
                                                                <form method="dialog">
                                                                    <button
                                                                        class="btn btn-sm btn-circle btn-ghost">✕</button>
                                                                </form>
                                                            </div>

                                                            @if (count($images) > 1)
                                                                <!-- Carousel for multiple images -->
                                                                <div class="carousel w-full rounded-lg">
                                                                    @foreach ($images as $index => $image)
                                                                        <div id="slide{{ $pelaporan->pelaporan_id }}_{{ $index }}"
                                                                            class="carousel-item relative w-full">
                                                                            <img src="{{ asset('storage/' . $image) }}"
                                                                                class="w-full max-h-96 object-contain mx-auto"
                                                                                alt="Foto laporan {{ $index + 1 }}"
                                                                                onerror="this.src='{{ asset('images/no-image.png') }}'" />
                                                                            <div
                                                                                class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                                                                                <a href="#slide{{ $pelaporan->pelaporan_id }}_{{ $index > 0 ? $index - 1 : count($images) - 1 }}"
                                                                                    class="btn btn-circle btn-sm">❮</a>
                                                                                <a href="#slide{{ $pelaporan->pelaporan_id }}_{{ $index < count($images) - 1 ? $index + 1 : 0 }}"
                                                                                    class="btn btn-circle btn-sm">❯</a>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>

                                                                <!-- Carousel indicators -->
                                                                <div class="flex justify-center w-full py-2 gap-2">
                                                                    @foreach ($images as $index => $image)
                                                                        <a href="#slide{{ $pelaporan->pelaporan_id }}_{{ $index }}"
                                                                            class="btn btn-xs">{{ $index + 1 }}</a>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <!-- Single image -->
                                                                <div class="flex justify-center">
                                                                    <img src="{{ asset('storage/' . $images[0]) }}"
                                                                        class="max-w-full max-h-96 object-contain rounded-lg"
                                                                        alt="Foto laporan"
                                                                        onerror="this.src='{{ asset('images/no-image.png') }}'" />
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <form method="dialog" class="modal-backdrop">
                                                            <button>close</button>
                                                        </form>
                                                    </dialog>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Recommendation Form -->
                        <div class="border-t pt-4">
                            @if($userSubmissions[$facility->fasilitas_id])
                                <!-- Already Submitted State -->
                                <div class="alert alert-info">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>Anda sudah memberikan rekomendasi biaya untuk fasilitas ini.</span>
                                </div>
                            @else
                                <!-- Active Form -->
                                <form wire:submit.prevent="submitRekomendasi({{ $facility->fasilitas_id }})">
                                    <label class="form-control w-full">
                                        <div class="label">
                                            <span class="label-text font-medium">Rekomendasi Biaya Perbaikan (Rp)</span>
                                        </div>
                                        <div class="flex gap-2">
                                            <input type="number"
                                                wire:model="biayaRekomendasi.{{ $facility->fasilitas_id }}"
                                                placeholder="Masukkan estimasi biaya perbaikan"
                                                class="input input-bordered flex-1 @error('biayaRekomendasi.' . $facility->fasilitas_id) input-error @enderror"
                                                min="1" step="1" />
                                            <button type="submit" class="btn btn-primary text-white" wire:loading.attr="disabled"
                                                wire:target="submitRekomendasi({{ $facility->fasilitas_id }})">
                                                <span wire:loading.remove
                                                    wire:target="submitRekomendasi({{ $facility->fasilitas_id }})">
                                                    <i class="bi bi-check-circle-fill mr-1"></i>
                                                    Kirim
                                                </span>
                                                <span wire:loading
                                                    wire:target="submitRekomendasi({{ $facility->fasilitas_id }})">
                                                    <span class="loading loading-spinner loading-sm"></span>
                                                    Loading...
                                                </span>
                                            </button>
                                        </div>
                                        @error('biayaRekomendasi.' . $facility->fasilitas_id)
                                            <div class="label">
                                                <span class="label-text-alt text-error">{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </label>
                                </form>
                            @endif
                        </div>

                        <!-- Current Recommendations Display -->
                        @php
                            $currentRecommendations = \App\Models\SkorAltModel::whereIn(
                                'pelaporan_id',
                                $facility->pelaporan->pluck('pelaporan_id'),
                            )
                                ->where('kriteria_id', 4)
                                ->get();
                        @endphp
                        @if ($currentRecommendations->count() > 0)
                            <div class="mt-4 pt-4 border-t">
                                <h5 class="text-sm font-medium text-gray-700 mb-2">Rekomendasi Biaya Saat Ini
                                    <span class="text-gray-400"> (semua teknisi) </span> :
                                    <span class="badge badge-info">Rp
                                        {{ number_format($currentRecommendations[0]->nilai_skor, 0, ',', '.') }}
                                    </span>
                                </h5>
                                {{-- currentRecommendations[0] buat ambil salah 1 saja --}}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <div class="max-w-md mx-auto">
                <div class="mb-4">
                    <i class="bi bi-clipboard-check text-6xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Fasilitas</h3>
                <p class="text-gray-500">
                    Saat ini tidak ada fasilitas yang memerlukan rekomendasi biaya perbaikan.
                </p>
                <p class="text-sm text-gray-400 mt-2">
                    Fasilitas akan muncul ketika ada laporan dengan status "Menunggu" atau "Diterima".
                </p>
            </div>
        </div>
    @endif
</div>
<script>
    document.addEventListener('livewire:initialized', () => {
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
