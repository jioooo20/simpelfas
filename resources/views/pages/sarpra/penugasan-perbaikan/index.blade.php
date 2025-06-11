@extends('layouts.main')
@section('judul', 'Penugasan Perbaikan Teknisi')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <!-- Main Content -->
        <div class="bg-white shadow-lg border border-gray-200 rounded-xl p-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start gap-3">
                    <i class="bi bi-info-circle text-blue-600 text-xl mt-0.5"></i>
                    <div>
                        <h3 class="font-semibold text-blue-800 mb-2">Cara Menggunakan Sistem Penugasan:</h3>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• <strong>Pastikan Data</strong> yang akan diolah sudah diterima pada menu 
                                <a
                                    href="{{ route('sarpra.laporan-kerusakan-fasilitas') }}" class="text-blue-600 font-medium underline hover:text-blue-800 transition duration-300 ease-in-out hover:underline-offset-2">Laporan
                                    Kerusakan Fasilitas</a>
                            <li>• <strong>Pilih fasilitas</strong> yang memerlukan perbaikan dari daftar laporan yang
                                telah diterima</li>
                            <li>• <strong>Tugaskan teknisi</strong> dengan memilih satu atau lebih teknisi yang sesuai.</li>
                            <li>• <strong>Monitor progress</strong> melalui status yang tersedia (Menunggu, Diproses,
                                Selesai)</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Livewire Component -->
            <livewire:penugasan-perbaikan-table />
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded',
            function() { // Listen for Livewire events to update stats            window.addEventListener('stats-updated', event => {
                const stats = event.detail;
                document.getElementById('pendingCount').textContent = stats.pending || 0;
                document.getElementById('processedCount').textContent = stats.processed || 0;
                document.getElementById('completedCount').textContent = stats.completed || 0;
                document.getElementById('totalActiveTasks').textContent =
                    (stats.pending || 0) + (stats.processed || 0);
            });
            });
        </script>
    @endpush
@endsection
