<div>
    @if ($facilities->count() > 0)
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            @foreach ($facilities as $facility)
                <div class="card bg-white shadow-lg border border-gray-200 flex flex-col">
                    <div class="card-body p-6 flex-grow">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 pb-4 border-b">
                            <div>
                                <h3 class="card-title text-lg font-bold text-gray-800">
                                    {{ $facility->barang->barang_nama }} - {{ substr($facility->fasilitas_kode, -2) }}
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="bi bi-geo-alt-fill mr-1 text-red-500"></i>
                                    {{ $facility->ruang->ruang_nama }} - {{ $facility->ruang->lantai->lantai_nama }} -
                                    {{ $facility->ruang->lantai->gedung->gedung_nama }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Kode: {{ $facility->fasilitas_kode }}
                                </p>
                            </div>
                            <div class="badge badge-outline mt-2 sm:mt-0 flex-shrink-0">
                                {{ $facility->pelaporan->count() }} Laporan
                            </div>
                        </div>

                        <div class="mb-4">
                            <h4 class="font-medium text-gray-700 mb-3">Detail Laporan Terkait:</h4>
                            <div class="space-y-4 max-h-72 overflow-y-auto pr-2">
                                @foreach ($facility->pelaporan as $pelaporan)
                                    {{-- Setiap item laporan kini menjadi blok vertikal --}}
                                    <div class="bg-slate-50 rounded-lg p-3 border-l-4 border-blue-400 space-y-3">
                                        <div class="flex justify-between items-start gap-2">
                                            <span class="text-sm font-semibold text-gray-800 break-all">
                                                {{ $pelaporan->pelaporan_kode }}
                                            </span>
                                            @php
                                                $latestStatus = $pelaporan->statusPelaporan->last();
                                                $statusClass = ['Menunggu' => 'badge-warning', 'Diterima' => 'badge-success', 'Ditolak' => 'badge-error'][$latestStatus->status_pelaporan] ?? 'badge-ghost';
                                            @endphp
                                            <span class="badge badge-sm {{ $statusClass }} text-white flex-shrink-0">
                                                {{ $latestStatus->status_pelaporan }}
                                            </span>
                                        </div>

                                        <p class="text-sm text-gray-700 break-words">
                                            {{ $pelaporan->pelaporan_deskripsi }}
                                        </p>

                                        <div
                                            class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 text-xs text-gray-500 pt-3 border-t">
                                            <div>
                                                <p>Oleh: {{ $pelaporan->user->nama }}</p>
                                                <p>{{ $pelaporan->created_at->translatedFormat('d M Y, H:i') }}</p>
                                            </div>

                                            @if ($pelaporan->pelaporan_gambar)
                                                @php
                                                    $images = is_string($pelaporan->pelaporan_gambar) ? json_decode($pelaporan->pelaporan_gambar, true) : $pelaporan->pelaporan_gambar;
                                                    $images = is_array($images) ? $images : [$pelaporan->pelaporan_gambar];
                                                @endphp
                                                <button class="btn btn-xs btn-outline btn-primary mt-2 sm:mt-0 self-end"
                                                        onclick="document.getElementById('viewPhotos{{ $pelaporan->pelaporan_id }}').showModal()">
                                                    <i class="bi bi-image mr-1"></i>
                                                    Lihat {{ count($images) }} Foto
                                                </button>

                                                <dialog id="viewPhotos{{ $pelaporan->pelaporan_id }}"
                                                        class="modal modal-middle">
                                                    <div
                                                        class="modal-box w-11/12 max-w-4xl p-0 flex flex-col max-h-[90vh]">
                                                        <div
                                                            class="flex justify-between items-center p-4 border-b flex-shrink-0">
                                                            <h3 class="font-bold text-lg">
                                                                Foto: {{ $pelaporan->pelaporan_kode }}</h3>
                                                            <form method="dialog">
                                                                <button class="btn btn-sm btn-circle btn-ghost">✕
                                                                </button>
                                                            </form>
                                                        </div>
                                                        <div
                                                            class="p-4 flex-grow flex justify-center items-center bg-slate-50 min-h-0">
                                                            @if (count($images) > 1)
                                                                <div class="w-full">
                                                                    <div class="carousel w-full rounded-lg">
                                                                        @foreach ($images as $index => $image)
                                                                            <div
                                                                                id="slide{{ $pelaporan->pelaporan_id }}_{{ $index }}"
                                                                                class="carousel-item relative w-full">
                                                                                <img
                                                                                    src="{{ asset('storage/' . $image) }}"
                                                                                    class="w-full max-h-[65vh] object-contain mx-auto"
                                                                                    alt="Foto {{ $index + 1 }}"
                                                                                    onerror="this.src='{{ asset('images/no-image.png') }}'"/>
                                                                                <div
                                                                                    class="absolute flex justify-between transform -translate-y-1/2 left-2 right-2 top-1/2">
                                                                                    <a href="#slide{{ $pelaporan->pelaporan_id }}_{{ $index > 0 ? $index - 1 : count($images) - 1 }}"
                                                                                       class="btn btn-circle btn-ghost btn-sm">❮</a>
                                                                                    <a href="#slide{{ $pelaporan->pelaporan_id }}_{{ $index < count($images) - 1 ? $index + 1 : 0 }}"
                                                                                       class="btn btn-circle btn-ghost btn-sm">❯</a>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <div class="flex justify-center w-full py-2 gap-2">
                                                                        @foreach ($images as $index => $image)
                                                                            <a href="#slide{{ $pelaporan->pelaporan_id }}_{{ $index }}"
                                                                               class="btn btn-xs">{{ $index + 1 }}</a>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <img src="{{ asset('storage/' . $images[0]) }}"
                                                                     class="max-w-full max-h-[75vh] object-contain rounded-lg"
                                                                     alt="Foto laporan"
                                                                     onerror="this.src='{{ asset('images/no-image.png') }}'"/>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <form method="dialog" class="modal-backdrop">
                                                        <button>close</button>
                                                    </form>
                                                </dialog>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="p-6 pt-4 border-t">
                        @if($userSubmissions[$facility->fasilitas_id])
                            <div class="alert alert-info">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Anda sudah memberikan rekomendasi biaya untuk fasilitas ini.</span>
                            </div>
                        @else
                            <form wire:submit.prevent="submitRekomendasi({{ $facility->fasilitas_id }})">
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-medium">Rekomendasi Biaya Perbaikan (Rp)</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <input type="number"
                                               wire:model="biayaRekomendasi.{{ $facility->fasilitas_id }}"
                                               placeholder="Masukkan estimasi biaya"
                                               class="input input-bordered flex-1 @error('biayaRekomendasi.' . $facility->fasilitas_id) input-error @enderror"
                                               min="1" step="1"/>
                                        <button type="submit" class="btn btn-primary text-white"
                                                wire:loading.attr="disabled"
                                                wire:target="submitRekomendasi({{ $facility->fasilitas_id }})">
                                            <span wire:loading.remove
                                                  wire:target="submitRekomendasi({{ $facility->fasilitas_id }})">
                                                <i class="bi bi-check-circle-fill mr-1"></i>Kirim
                                            </span>
                                            <span wire:loading
                                                  wire:target="submitRekomendasi({{ $facility->fasilitas_id }})">
                                                <span class="loading loading-spinner loading-sm"></span>Loading...
                                            </span>
                                        </button>
                                    </div>
                                    @error('biayaRekomendasi.' . $facility->fasilitas_id)
                                    <div class="label"><span class="label-text-alt text-error">{{ $message }}</span>
                                    </div>
                                    @enderror
                                </label>
                            </form>
                        @endif

                        @php
                            $currentRecommendations = \App\Models\SkorAltModel::whereIn('pelaporan_id', $facility->pelaporan->pluck('pelaporan_id'))->where('kriteria_id', 4)->get();
                        @endphp
                        @if ($currentRecommendations->count() > 0)
                            <div class="mt-4 pt-4 border-t">
                                <h5 class="text-sm font-medium text-gray-700">Rekomendasi Terakhir:
                                    <span
                                        class="badge badge-info">Rp {{ number_format($currentRecommendations->last()->nilai_skor, 0, ',', '.') }}</span>
                                </h5>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Tampilan state kosong (fungsionalitas asli dipertahankan) --}}
        <div class="text-center py-12">
            <div class="max-w-md mx-auto">
                <div class="mb-4"><i class="bi bi-clipboard-check text-6xl text-gray-300"></i></div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Fasilitas</h3>
                <p class="text-gray-500">Saat ini tidak ada fasilitas yang memerlukan rekomendasi biaya perbaikan.</p>
                <p class="text-sm text-gray-400 mt-2">Fasilitas akan muncul ketika ada laporan dengan status "Menunggu"
                    atau "Diterima".</p>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:initialized', () => {

        const showResponsiveToast = (message, type = 'success') => {
            const settings = {
                success: {
                    icon: 'bi bi-check-circle-fill',
                    background: 'linear-gradient(to right, #00b09b, #96c93d)',
                },
                error: {
                    icon: 'bi bi-exclamation-circle-fill',
                    background: 'linear-gradient(to right, #ff5f6d, #ffc371)',
                }
            };
            const currentSetting = settings[type];
            let options = {
                text: `<div class="flex items-center gap-3">
                          <i class="${currentSetting.icon} text-xl"></i>
                          <span>${message}</span>
                       </div>`,
                duration: 3000,
                gravity: "top",
                position: "right", // Default untuk desktop
                backgroundColor: currentSetting.background,
                className: "rounded-lg shadow-md",
                stopOnFocus: true,
                escapeMarkup: false,
                style: {
                    padding: "12px 20px",
                    fontWeight: "500",
                    minWidth: "300px"
                }
            };

            if (window.innerWidth < 768) {
                options.position = 'center';
                options.style.width = '90%';
                options.style.minWidth = '0';
                options.style.textAlign = 'center';
            }

            Toastify(options).showToast();
        };

        Livewire.on('showSuccessToast', (message) => {
            showResponsiveToast(message, 'success');
        });

        Livewire.on('showErrorToast', (message) => {
            showResponsiveToast(message, 'error');
        });
    });
</script>
