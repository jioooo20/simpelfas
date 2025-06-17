<div>
    <!-- First Row: Table Info & OLAH DSS Button -->
    <div class="flex justify-between items-center mb-6 p-4 bg-gray-50 rounded-lg border">
        <div class="flex flex-col">
            <h3 class="text-lg font-semibold text-gray-900">Rekomendasi Prioritas Perbaikan</h3>
            <p class="text-gray-500 text-sm mt-1">Tabel skor alternatif laporan perbaikan berdasarkan kriteria</p>
            <p class="text-gray-500 text-sm mt-1">Pastikan data yang akan diolah sudah diterima pada menu <a
                    href="{{ route('sarpra.laporan-kerusakan-fasilitas') }}" class="text-blue-500">Laporan Kerusakan
                    Fasilitas</a></p>
            <p class="text-gray-400 text-xs mt-1">Total data: {{ $laporanData->total() }} laporan</p>
        </div>
        <div>
            <button wire:click="olahDss"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg transition duration-200">
                OLAH DSS
            </button>
        </div>
    </div>

    <!-- Second Row: Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button wire:click="switchTab('dosen')"
                    class="py-2 px-4 border-b-2 font-medium text-sm transition duration-200 {{ $activeTab === 'dosen' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Dosen
                </button>
                <button wire:click="switchTab('staff')"
                    class="py-2 px-4 border-b-2 font-medium text-sm transition duration-200 {{ $activeTab === 'staff' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Staff
                </button>
                <button wire:click="switchTab('mahasiswa')"
                    class="py-2 px-4 border-b-2 font-medium text-sm transition duration-200 {{ $activeTab === 'mahasiswa' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Mahasiswa
                </button>
            </nav>
        </div>
    </div>

    <!-- Tabel -->
    <div class="">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead class="bg-base-200">
                    <tr>
                        <th class="text-center font-bold text-sm uppercase tracking-wide">No</th>
                        <th class="text-left font-bold text-sm uppercase tracking-wide">Fasilitas</th>
                        <th class="text-center font-bold text-sm uppercase tracking-wide">Total Laporan</th>
                        <th class="text-center font-bold text-sm uppercase tracking-wide">C1</th>
                        <th class="text-center font-bold text-sm uppercase tracking-wide">C2</th>
                        <th class="text-center font-bold text-sm uppercase tracking-wide">C3</th>
                        <th class="text-center font-bold text-sm uppercase tracking-wide">C4</th>
                        <th class="text-center font-bold text-sm uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporanData as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="text-center">
                                {{ ($laporanData->currentPage() - 1) * $laporanData->perPage() + $loop->iteration }}
                            </td>
                            <td class="text-left">
                                <div class="flex flex-col">
                                    <span class="text-black text-sm font-medium">
                                        {{ $item->barang_nama ?? '-' }}
                                    </span>
                                    <span class="text-gray-500 text-xs">
                                        {{ $item->fasilitas_kode ?? '-' }}
                                    </span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="px-2 py-1 rounded-md text-sm bg-blue-100 text-blue-800 font-medium">
                                    {{ $item->total_pelaporan }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span
                                    class="px-2 py-1 rounded-md text-sm {{ $item->c1 > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $item->c1 > 0 ? number_format($item->c1, 2) : '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span
                                    class="px-2 py-1 rounded-md text-sm {{ $item->c2 > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $item->c2 > 0 ? number_format($item->c2, 2) : '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span
                                    class="px-2 py-1 rounded-md text-sm {{ $item->c3 > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $item->c3 > 0 ? number_format($item->c3, 2) : '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span
                                    class="px-2 py-1 rounded-md text-sm {{ $item->c4 > 0 ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $item->c4 > 0 ? number_format($item->c4, 2) : 'Tanpa Biaya' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button wire:click="showDetail('{{ $item->fasilitas_kode }}')"
                                    class="btn btn-sm btn-outline btn-primary">
                                    <i class="bi bi-eye"></i>
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="bi bi-file-earmark-x text-4xl mb-2"></i>
                                    <p>Tidak ada data fasilitas ditemukan untuk {{ ucfirst($activeTab) }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($laporanData->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $laporanData->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Detail -->
    @if ($showDetailModal && $selectedLaporan)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <!-- Header Modal -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">Detail Fasilitas</h3>
                    <button wire:click="closeDetail" class="text-gray-400 hover:text-gray-600">
                        <i class="bi bi-x-lg text-xl"></i>
                    </button>
                </div>

                <!-- Content Modal -->
                <div class="p-6 space-y-6">
                    <!-- Informasi Fasilitas -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-lg font-medium text-gray-900 mb-3">Informasi Fasilitas</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kode Fasilitas</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $selectedLaporan->fasilitas->fasilitas_kode }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Total Laporan</label>
                                <p class="mt-1">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $selectedLaporan->total_reports }} laporan
                                    </span>
                                </p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Lokasi Fasilitas</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $selectedLaporan->fasilitas_label }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Rata-rata Skor Kriteria -->
                    <div class="bg-green-50 rounded-lg p-4">
                        <h4 class="text-lg font-medium text-gray-900 mb-3">Rata-rata Skor Kriteria</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach ([1 => 'C1', 2 => 'C2', 3 => 'C3', 4 => 'C4'] as $kriteriaId => $kode)
                                <div class="text-center">
                                    <div
                                        class="text-2xl font-bold {{ isset($selectedLaporan->avg_scores[$kriteriaId]) ? 'text-green-600' : 'text-gray-400' }}">
                                        {{ isset($selectedLaporan->avg_scores[$kriteriaId]) ? number_format($selectedLaporan->avg_scores[$kriteriaId], 2) : '-' }}
                                    </div>
                                    <div class="text-sm text-gray-600">{{ $kode }}</div>
                                    @if ($kode === 'C1')
                                        <div class="text-xs text-gray-500">Banyak Laporan</div>
                                    @elseif($kode === 'C2')
                                        <div class="text-xs text-gray-500">Skala Kerusakan</div>
                                    @elseif($kode === 'C3')
                                        <div class="text-xs text-gray-500">Frekuensi Penggunaan</div>
                                    @elseif($kode === 'C4')
                                        <div class="text-xs text-gray-500">Biaya Perbaikan</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Daftar Laporan -->
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <h4 class="text-lg font-medium text-gray-900 mb-3">Daftar Laporan Terkait</h4>
                        <div class="max-h-60 overflow-y-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kode
                                        </th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Pelapor</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Status</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($selectedLaporan->reports as $report)
                                        <tr>
                                            <td class="px-3 py-2 text-sm text-gray-900">{{ $report->pelaporan_kode }}
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                {{ $report->user_nama }}
                                                <span class="text-xs text-gray-500">({{ $report->role_nama }})</span>
                                            </td>
                                            <td class="px-3 py-2">
                                                @php
                                                    $statusColor = match ($report->status_pelaporan) {
                                                        'Menunggu' => 'bg-yellow-100 text-yellow-800',
                                                        'Diterima' => 'bg-blue-100 text-blue-800',
                                                        'Diproses' => 'bg-purple-100 text-purple-800',
                                                        'Selesai' => 'bg-green-100 text-green-800',
                                                        'Ditolak' => 'bg-red-100 text-red-800',
                                                        default => 'bg-gray-100 text-gray-800',
                                                    };
                                                @endphp
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                                    {{ $report->status_pelaporan ?? 'Belum Ada Status' }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($report->created_at)->format('d M Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Bobot Kriteria -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h4 class="text-lg font-medium text-gray-900 mb-3">Bobot Kriteria

                        </h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach (['C1' => 'Banyak Laporan', 'C2' => 'Skala Kerusakan', 'C3' => 'Frekuensi Penggunaan', 'C4' => 'Biaya Perbaikan'] as $kode => $nama)
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600">
                                        {{ isset($bobotKriteria[$kode]) ? number_format($bobotKriteria[$kode], 2) : '0.00' }}
                                    </div>
                                    <div class="text-sm text-gray-600">{{ $kode }}</div>
                                    <div class="text-xs text-gray-500">{{ $nama }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="flex justify-end p-6 border-t border-gray-200">
                    <button wire:click="closeDetail" class="btn btn-sm btn-ghost">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal DSS Result -->
    @if ($showDssResultModal && $dssResults)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <!-- Header Modal -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">Hasil Pengolahan DSS - Langkah Perhitungan & Hasil
                    </h3>
                    <a href="{{ route('sarpra.rekomendasi-prioritas-perbaikan') }}" wire:click="$set('showDssResultModal', false)" class="text-gray-400 hover:text-gray-600">
                        <i class="bi bi-x-lg text-xl"></i>
                    </a>
                </div>

                <!-- Content Modal -->
                <div class="p-6 space-y-8">
                    <!-- Processing Steps for MABAC Mahasiswa -->
                    @if (isset($dssSteps['mahasiswa']))
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Langkah Perhitungan MABAC - Mahasiswa
                            </h4>

                            <!-- Original Matrix -->
                            <div class="mb-4">
                                <h5 class="text-md font-medium text-gray-800 mb-2">1. Matriks Keputusan Awal</h5>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-xs border border-gray-300">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border px-2 py-1">Alternatif</th>
                                                <th class="border px-2 py-1">C1</th>
                                                <th class="border px-2 py-1">C2</th>
                                                <th class="border px-2 py-1">C3</th>
                                                <th class="border px-2 py-1">C4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dssSteps['mahasiswa']['original_matrix'] as $code => $values)
                                                <tr>
                                                    <td class="border px-2 py-1">{{ $code }}</td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c1']) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c2']) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c3']) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c4']) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Normalized Matrix -->
                            <div class="mb-4">
                                <h5 class="text-md font-medium text-gray-800 mb-2">2. Matriks Normalisasi</h5>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-xs border border-gray-300">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border px-2 py-1">Alternatif</th>
                                                <th class="border px-2 py-1">C1</th>
                                                <th class="border px-2 py-1">C2</th>
                                                <th class="border px-2 py-1">C3</th>
                                                <th class="border px-2 py-1">C4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dssSteps['mahasiswa']['normalized_matrix'] as $code => $values)
                                                <tr>
                                                    <td class="border px-2 py-1">{{ $code }}</td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c1'], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c2'], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c3'], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c4'], 4) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Weighted Matrix -->
                            <div class="mb-4">
                                <h5 class="text-md font-medium text-gray-800 mb-2">3. Matriks Terbobot (V)</h5>
                                <p class="text-xs text-gray-600 mb-2">
                                    Bobot: C1={{ number_format($dssSteps['mahasiswa']['weights']['C1'], 4) }},
                                    C2={{ number_format($dssSteps['mahasiswa']['weights']['C2'], 4) }},
                                    C3={{ number_format($dssSteps['mahasiswa']['weights']['C3'], 4) }},
                                    C4={{ number_format($dssSteps['mahasiswa']['weights']['C4'], 4) }}
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-xs border border-gray-300">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border px-2 py-1">Alternatif</th>
                                                <th class="border px-2 py-1">C1</th>
                                                <th class="border px-2 py-1">C2</th>
                                                <th class="border px-2 py-1">C3</th>
                                                <th class="border px-2 py-1">C4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dssSteps['mahasiswa']['weighted_matrix'] as $code => $values)
                                                <tr>
                                                    <td class="border px-2 py-1">{{ $code }}</td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c1'], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c2'], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c3'], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c4'], 4) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Matriks Area Perkiraan Batas -->
                            <div class="mb-4">
                                <h5 class="text-md font-medium text-gray-800 mb-2">4. Matriks Area Perkiraan Batas (G)
                                </h5>
                                <div class="grid grid-cols-4 gap-2 text-xs">
                                    @foreach ($dssSteps['mahasiswa']['border_approximation'] as $criterion => $value)
                                        <div class="text-center p-2 bg-white rounded border">
                                            <div class="font-medium">{{ strtoupper($criterion) }}</div>
                                            <div>{{ number_format($value, 4) }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Distance Matrix -->
                            <div class="mb-4">
                                <h5 class="text-md font-medium text-gray-800 mb-2">5. Matriks Jarak (Q)</h5>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-xs border border-gray-300">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border px-2 py-1">Alternatif</th>
                                                <th class="border px-2 py-1">C1</th>
                                                <th class="border px-2 py-1">C2</th>
                                                <th class="border px-2 py-1">C3</th>
                                                <th class="border px-2 py-1">C4</th>
                                                <th class="border px-2 py-1 bg-yellow-100">Skor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dssSteps['mahasiswa']['distance_matrix'] as $code => $values)
                                                <tr>
                                                    <td class="border px-2 py-1">{{ $code }}</td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c1'], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c2'], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c3'], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c4'], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1 bg-yellow-100 font-medium">
                                                        {{ number_format($dssResults['mahasiswa']['scores'][$code], 4) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Processing Steps for MABAC Dosen -->
                    @if (isset($dssSteps['dosen']))
                        <div class="bg-purple-50 rounded-lg p-4">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Langkah Perhitungan MABAC - Dosen</h4>

                            <!-- Original Matrix -->
                            <div class="mb-4">
                                <h5 class="text-md font-medium text-gray-800 mb-2">1. Matriks Keputusan Awal</h5>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-xs border border-gray-300">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border px-2 py-1">Alternatif</th>
                                                <th class="border px-2 py-1">C1</th>
                                                <th class="border px-2 py-1">C2</th>
                                                <th class="border px-2 py-1">C3</th>
                                                <th class="border px-2 py-1">C4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dssSteps['dosen']['original_matrix'] as $code => $values)
                                                <tr>
                                                    <td class="border px-2 py-1">{{ $code }}</td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c1']) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c2']) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c3']) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c4']) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Normalized Matrix -->
                            <div class="mb-4">
                                <h5 class="text-md font-medium text-gray-800 mb-2">2. Matriks Normalisasi</h5>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-xs border border-gray-300">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border px-2 py-1">Alternatif</th>
                                                <th class="border px-2 py-1">C1</th>
                                                <th class="border px-2 py-1">C2</th>
                                                <th class="border px-2 py-1">C3</th>
                                                <th class="border px-2 py-1">C4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dssSteps['dosen']['normalized_matrix'] as $code => $values)
                                                <tr>
                                                    <td class="border px-2 py-1">{{ $code }}</td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c1'], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c2'], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c3'], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c4'], 4) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Weighted Matrix -->
                            <div class="mb-4">
                                <h5 class="text-md font-medium text-gray-800 mb-2">3. Matriks Terbobot (V)</h5>
                                <p class="text-xs text-gray-600 mb-2">
                                    Bobot: C1={{ number_format($dssSteps['dosen']['weights']['C1'], 4) }},
                                    C2={{ number_format($dssSteps['dosen']['weights']['C2'], 4) }},
                                    C3={{ number_format($dssSteps['dosen']['weights']['C3'], 4) }},
                                    C4={{ number_format($dssSteps['dosen']['weights']['C4'], 4) }}
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-xs border border-gray-300">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border px-2 py-1">Alternatif</th>
                                                <th class="border px-2 py-1">C1</th>
                                                <th class="border px-2 py-1">C2</th>
                                                <th class="border px-2 py-1">C3</th>
                                                <th class="border px-2 py-1">C4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dssSteps['dosen']['weighted_matrix'] as $code => $values)
                                                <tr>
                                                    <td class="border px-2 py-1">{{ $code }}</td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c1'], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c2'], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c3'], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c4'], 4) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Matriks Area Perkiraan Batas -->
                            <div class="mb-4">
                                <h5 class="text-md font-medium text-gray-800 mb-2">4. Matriks Area Perkiraan Batas (G)
                                </h5>
                                <div class="grid grid-cols-4 gap-2 text-xs">
                                    @foreach ($dssSteps['dosen']['border_approximation'] as $criterion => $value)
                                        <div class="text-center p-2 bg-white rounded border">
                                            <div class="font-medium">{{ strtoupper($criterion) }}</div>
                                            <div>{{ number_format($value, 4) }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Distance Matrix -->
                            <div class="mb-4">
                                <h5 class="text-md font-medium text-gray-800 mb-2">5. Matriks Jarak (Q)</h5>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-xs border border-gray-300">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border px-2 py-1">Alternatif</th>
                                                <th class="border px-2 py-1">C1</th>
                                                <th class="border px-2 py-1">C2</th>
                                                <th class="border px-2 py-1">C3</th>
                                                <th class="border px-2 py-1">C4</th>
                                                <th class="border px-2 py-1 bg-yellow-100">Skor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dssSteps['dosen']['distance_matrix'] as $code => $values)
                                                <tr>
                                                    <td class="border px-2 py-1">{{ $code }}</td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c1'], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c2'], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c3'], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c4'], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1 bg-yellow-100 font-medium">
                                                        {{ number_format($dssResults['dosen']['scores'][$code], 4) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Processing Steps for EDAS Staff -->
                    @if (isset($dssSteps['staff']))
                        <div class="bg-green-50 rounded-lg p-4">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Langkah Perhitungan EDAS - Staff</h4>

                            <!-- Original Matrix -->
                            <div class="mb-4">
                                <h5 class="text-md font-medium text-gray-800 mb-2">1. Matriks Keputusan Awal</h5>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-xs border border-gray-300">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border px-2 py-1">Alternatif</th>
                                                <th class="border px-2 py-1">C1</th>
                                                <th class="border px-2 py-1">C2</th>
                                                <th class="border px-2 py-1">C3</th>
                                                <th class="border px-2 py-1">C4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dssSteps['staff']['original_matrix'] as $code => $values)
                                                <tr>
                                                    <td class="border px-2 py-1">{{ $code }}</td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c1']) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c2']) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c3']) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c4']) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Average Values -->
                            <div class="mb-4">
                                <h5 class="text-md font-medium text-gray-800 mb-2">2. Nilai Rata-rata (AV)</h5>
                                <div class="grid grid-cols-4 gap-2 text-xs">
                                    @foreach ($dssSteps['staff']['average_values'] as $criterion => $value)
                                        <div class="text-center p-2 bg-white rounded border">
                                            <div class="font-medium">{{ strtoupper($criterion) }}</div>
                                            <div>
                                                @if (str_contains(strtoupper($criterion), 'C4'))
                                                    {{ number_format($value, 0) }}
                                                @else
                                                    {{ number_format($value, 4) }}
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- PDA Matrix -->
                            <div class="mb-4">
                                <h5 class="text-md font-medium text-gray-800 mb-2">3. Positive Distance from Average
                                    (PDA)</h5>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-xs border border-gray-300">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border px-2 py-1">Alternatif</th>
                                                <th class="border px-2 py-1">C1</th>
                                                <th class="border px-2 py-1">C2</th>
                                                <th class="border px-2 py-1">C3</th>
                                                <th class="border px-2 py-1">C4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dssSteps['staff']['pda_matrix'] as $code => $values)
                                                <tr>
                                                    <td class="border px-2 py-1">{{ $code }}</td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c1'], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1">{{ number_format($values['c2'], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1">
                                                        {{ number_format($values['c3'], 4) }}</td>
                                                    <td class="border px-2 py-1">
                                                        {{ number_format($values['c4'], 4) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- NDA Matrix -->
                            <div class="mb-4">
                                <h5 class="text-md font-medium text-gray-800 mb-2">4. Negative Distance from Average
                                    (NDA)</h5>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-xs border border-gray-300">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border px-2 py-1">Alternatif</th>
                                                <th class="border px-2 py-1">C1</th>
                                                <th class="border px-2 py-1">C2</th>
                                                <th class="border px-2 py-1">C3</th>
                                                <th class="border px-2 py-1">C4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dssSteps['staff']['nda_matrix'] as $code => $values)
                                                <tr>
                                                    <td class="border px-2 py-1">{{ $code }}</td>
                                                    <td class="border px-2 py-1">
                                                        {{ number_format($values['c1'], 4) }}</td>
                                                    <td class="border px-2 py-1">
                                                        {{ number_format($values['c2'], 4) }}</td>
                                                    <td class="border px-2 py-1">
                                                        {{ number_format($values['c3'], 4) }}</td>
                                                    <td class="border px-2 py-1">
                                                        {{ number_format($values['c4'], 4) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- SP and SN Values -->
                            <div class="mb-4">
                                <h5 class="text-md font-medium text-gray-800 mb-2">5. Weighted Sum SP dan SN</h5>
                                <p class="text-xs text-gray-600 mb-2">
                                    Bobot: C1={{ number_format($dssSteps['staff']['weights']['C1'], 4) }},
                                    C2={{ number_format($dssSteps['staff']['weights']['C2'], 4) }},
                                    C3={{ number_format($dssSteps['staff']['weights']['C3'], 4) }},
                                    C4={{ number_format($dssSteps['staff']['weights']['C4'], 4) }}
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-xs border border-gray-300">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border px-2 py-1">Alternatif</th>
                                                <th class="border px-2 py-1 bg-green-100">SP</th>
                                                <th class="border px-2 py-1 bg-red-100">SN</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dssSteps['staff']['sp_values'] as $code => $spValue)
                                                <tr>
                                                    <td class="border px-2 py-1">{{ $code }}</td>
                                                    <td class="border px-2 py-1 bg-green-100">
                                                        {{ number_format($spValue, 4) }}</td>
                                                    <td class="border px-2 py-1 bg-red-100">
                                                        {{ number_format($dssSteps['staff']['sn_values'][$code], 4) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Max SP and SN -->
                            <div class="mb-4">
                                <h5 class="text-md font-medium text-gray-800 mb-2">6. Nilai Maksimum SP dan SN</h5>
                                <div class="grid grid-cols-2 gap-4 text-xs">
                                    <div class="text-center p-2 bg-green-100 rounded border">
                                        <div class="font-medium">Max SP</div>
                                        <div>{{ number_format($dssSteps['staff']['max_sp'], 4) }}</div>
                                    </div>
                                    <div class="text-center p-2 bg-red-100 rounded border">
                                        <div class="font-medium">Max SN</div>
                                        <div>{{ number_format($dssSteps['staff']['max_sn'], 4) }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- NSP and NSN Values -->
                            <div class="mb-4">
                                <h5 class="text-md font-medium text-gray-800 mb-2">7. Normalized SP dan SN (NSP & NSN)
                                </h5>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-xs border border-gray-300">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border px-2 py-1">Alternatif</th>
                                                <th class="border px-2 py-1 bg-green-100">NSP</th>
                                                <th class="border px-2 py-1 bg-red-100">NSN</th>
                                                <th class="border px-2 py-1 bg-yellow-100">Skor Final</th>
                                                <th class="border px-2 py-1 bg-blue-100">Ranking</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dssSteps['staff']['nsp_values'] as $code => $nspValue)
                                                <tr>
                                                    <td class="border px-2 py-1">{{ $code }}</td>
                                                    <td class="border px-2 py-1 bg-green-100">
                                                        {{ number_format($nspValue, 4) }}</td>
                                                    <td class="border px-2 py-1 bg-red-100">
                                                        {{ number_format($dssSteps['staff']['nsn_values'][$code], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1 bg-yellow-100 font-medium">
                                                        {{ number_format($dssResults['staff']['scores'][$code], 4) }}
                                                    </td>
                                                    <td class="border px-2 py-1 bg-blue-100 font-medium text-center">
                                                        {{ $dssResults['staff']['ranking'][$code] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- MABAC Results Mahasiswa -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Hasil MABAC - Mahasiswa</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Kode Fasilitas</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Gedung</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Lantai</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Ruangan</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Nama Barang</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">
                                            Skor</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">
                                            Ranking</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($dssResults['mahasiswa']['scores'] as $facilityCode => $score)
                                        @php
                                            $facilityInfo = DB::table('t_fasilitas as f')
                                                ->leftJoin('m_barang as b', 'f.barang_id', '=', 'b.barang_id')
                                                ->leftJoin('m_ruang as r', 'f.ruang_id', '=', 'r.ruang_id')
                                                ->leftJoin('m_lantai as l', 'r.lantai_id', '=', 'l.lantai_id')
                                                ->leftJoin('m_gedung as g', 'l.gedung_id', '=', 'g.gedung_id')
                                                ->where('f.fasilitas_kode', $facilityCode)
                                                ->select(
                                                    'f.fasilitas_kode',
                                                    'b.barang_nama',
                                                    'g.gedung_nama',
                                                    'l.lantai_nama',
                                                    'r.ruang_nama',
                                                )
                                                ->first();
                                            $lastTwoDigits = substr($facilityCode, -2);
                                        @endphp
                                        <tr>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                {{ $facilityCode }}
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                {{ $facilityInfo->gedung_nama ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                {{ $facilityInfo->lantai_nama ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                {{ $facilityInfo->ruang_nama ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                {{ $facilityInfo->barang_nama ?? 'Unknown' }}
                                                <span>{{ $lastTwoDigits }}</span>
                                            </td>
                                            <td class="px-3 py-2 text-center text-sm text-gray-900">
                                                {{ number_format($score, 4) }}</td>
                                            <td class="px-3 py-2 text-center">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $dssResults['mahasiswa']['ranking'][$facilityCode] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- MABAC Results Dosen -->
                    <div class="bg-purple-50 rounded-lg p-4">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Hasil MABAC - Dosen</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Kode Fasilitas</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Gedung</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Lantai</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Ruangan</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Nama Barang</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">
                                            Skor</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">
                                            Ranking</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($dssResults['dosen']['scores'] as $facilityCode => $score)
                                        @php
                                            $facilityInfo = DB::table('t_fasilitas as f')
                                                ->leftJoin('m_barang as b', 'f.barang_id', '=', 'b.barang_id')
                                                ->leftJoin('m_ruang as r', 'f.ruang_id', '=', 'r.ruang_id')
                                                ->leftJoin('m_lantai as l', 'r.lantai_id', '=', 'l.lantai_id')
                                                ->leftJoin('m_gedung as g', 'l.gedung_id', '=', 'g.gedung_id')
                                                ->where('f.fasilitas_kode', $facilityCode)
                                                ->select(
                                                    'f.fasilitas_kode',
                                                    'b.barang_nama',
                                                    'g.gedung_nama',
                                                    'l.lantai_nama',
                                                    'r.ruang_nama',
                                                )
                                                ->first();
                                            $lastTwoDigits = substr($facilityCode, -2);
                                        @endphp
                                        <tr>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                {{ $facilityCode }}
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                {{ $facilityInfo->gedung_nama ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                {{ $facilityInfo->lantai_nama ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                {{ $facilityInfo->ruang_nama ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                {{ $facilityInfo->barang_nama ?? 'Unknown' }}
                                                <span>{{ $lastTwoDigits }}</span>
                                            </td>
                                            <td class="px-3 py-2 text-center text-sm text-gray-900">
                                                {{ number_format($score, 4) }}</td>
                                            <td class="px-3 py-2 text-center">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    {{ $dssResults['dosen']['ranking'][$facilityCode] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- EDAS Results Staff -->
                    <div class="bg-green-50 rounded-lg p-4">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Hasil EDAS - Staff</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Kode Fasilitas</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Gedung</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Lantai</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Ruangan</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Nama Barang</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">
                                            Skor</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">
                                            Ranking</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($dssResults['staff']['scores'] as $facilityCode => $score)
                                        @php
                                            $facilityInfo = DB::table('t_fasilitas as f')
                                                ->leftJoin('m_barang as b', 'f.barang_id', '=', 'b.barang_id')
                                                ->leftJoin('m_ruang as r', 'f.ruang_id', '=', 'r.ruang_id')
                                                ->leftJoin('m_lantai as l', 'r.lantai_id', '=', 'l.lantai_id')
                                                ->leftJoin('m_gedung as g', 'l.gedung_id', '=', 'g.gedung_id')
                                                ->where('f.fasilitas_kode', $facilityCode)
                                                ->select(
                                                    'f.fasilitas_kode',
                                                    'b.barang_nama',
                                                    'g.gedung_nama',
                                                    'l.lantai_nama',
                                                    'r.ruang_nama',
                                                )
                                                ->first();
                                            $lastTwoDigits = substr($facilityCode, -2);
                                        @endphp
                                        <tr>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                {{ $facilityCode }}
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                {{ $facilityInfo->gedung_nama ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                {{ $facilityInfo->lantai_nama ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                {{ $facilityInfo->ruang_nama ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                {{ $facilityInfo->barang_nama ?? 'Unknown' }}
                                                <span>{{ $lastTwoDigits }}</span>
                                            </td>
                                            <td class="px-3 py-2 text-center text-sm text-gray-900">
                                                {{ number_format($score, 4) }}</td>
                                            <td class="px-3 py-2 text-center">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $dssResults['staff']['ranking'][$facilityCode] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Final GDSS Borda Results -->
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Hasil Final GDSS Borda</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Kode Fasilitas</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Gedung</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Lantai</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Ruangan</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Nama Barang</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">
                                            Skor Borda</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">
                                            Ranking Final</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($dssResults['final']['scores'] as $facilityCode => $score)
                                        @php
                                            $facilityInfo = DB::table('t_fasilitas as f')
                                                ->leftJoin('m_barang as b', 'f.barang_id', '=', 'b.barang_id')
                                                ->leftJoin('m_ruang as r', 'f.ruang_id', '=', 'r.ruang_id')
                                                ->leftJoin('m_lantai as l', 'r.lantai_id', '=', 'l.lantai_id')
                                                ->leftJoin('m_gedung as g', 'l.gedung_id', '=', 'g.gedung_id')
                                                ->where('f.fasilitas_kode', $facilityCode)
                                                ->select(
                                                    'f.fasilitas_kode',
                                                    'b.barang_nama',
                                                    'g.gedung_nama',
                                                    'l.lantai_nama',
                                                    'r.ruang_nama',
                                                )
                                                ->first();
                                            $lastTwoDigits = substr($facilityCode, -2);
                                        @endphp
                                        <tr class="{{ $loop->first ? 'bg-yellow-100' : '' }}">
                                            <td
                                                class="px-3 py-2 text-sm {{ $loop->first ? 'text-yellow-900' : 'text-gray-900' }}">
                                                {{ $facilityCode }}
                                                @if ($loop->first)
                                                    <span class="text-xs text-yellow-600 ml-1">(Prioritas
                                                        Tertinggi)</span>
                                                @endif
                                            </td>
                                            <td
                                                class="px-3 py-2 text-sm {{ $loop->first ? 'text-yellow-900' : 'text-gray-900' }}">
                                                {{ $facilityInfo->gedung_nama ?? '-' }}
                                            </td>
                                            <td
                                                class="px-3 py-2 text-sm {{ $loop->first ? 'text-yellow-900' : 'text-gray-900' }}">
                                                {{ $facilityInfo->lantai_nama ?? '-' }}
                                            </td>
                                            <td
                                                class="px-3 py-2 text-sm {{ $loop->first ? 'text-yellow-900' : 'text-gray-900' }}">
                                                {{ $facilityInfo->ruang_nama ?? '-' }}
                                            </td>
                                            <td
                                                class="px-3 py-2 text-sm {{ $loop->first ? 'text-yellow-900' : 'text-gray-900' }}">
                                                {{ $facilityInfo->barang_nama ?? 'Unknown' }}
                                                <span
                                                    class="{{ $loop->first ? 'text-yellow-600' : 'text-black' }} font-medium">{{ $lastTwoDigits }}</span>
                                            </td>
                                            <td
                                                class="px-3 py-2 text-center text-sm {{ $loop->first ? 'text-yellow-900 font-medium' : 'text-gray-900' }}">
                                                {{ number_format($score, 2) }}
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $loop->first ? 'bg-yellow-200 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ $dssResults['final']['ranking'][$facilityCode] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="flex justify-end p-6 border-t border-gray-200 gap-3">
                    <a href="{{ route('penugasan-perbaikan') }}" class="btn btn-sm btn-primary text-white"
                        wire:click="$set('showDssResultModal', false)">Penugasan</a>
                    <a href="{{ route('sarpra.rekomendasi-prioritas-perbaikan') }}" wire:click="$set('showDssResultModal', false)" class="btn btn-sm btn-ghost">
                        Tutup
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
