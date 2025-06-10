<?php

namespace App\Repositories;

use App\Models\KriteriaModel;
use App\Models\PelaporanModel;
use App\Models\SkorAltModel;
use App\Models\StatusPelaporanModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class PelaporanRepository
{
    public function StorePelaporan(array $data): PelaporanModel
    {
        $kode = 'PLP-' . strtoupper(Str::random(6));

        $pelaporan = PelaporanModel::create([
            'user_id' => Auth::id() ?? 1,
            'fasilitas_id' => $data['fasilitas_id'],
            'pelaporan_kode' => $kode,
            'pelaporan_deskripsi' => $data['deskripsi'],
            'pelaporan_gambar' => isset($data['gambar']) ? json_encode($data['gambar'], JSON_UNESCAPED_SLASHES) : null,
        ]);

        StatusPelaporanModel::create([
            'pelaporan_id' => $pelaporan->pelaporan_id,
            'status_pelaporan' => 'Menunggu'
        ]);

        return $pelaporan;
    }

    public function simpanSkorAlternatif(int $pelaporanId, string $skala, string $frekuensi): void
    {
        $skalaBobot = [
            'Ringan' => 1,
            'Sedang' => 2,
            'Berat' => 3,
        ];

        $frekuensiBobot = [
            'Jarang' => 1,
            'Sedang' => 2,
            'Sering' => 3,
        ];

        $kodeMap = [
            'skala' => 'C2',
            'frekuensi' => 'C3',
        ];

        $kriteriaSkala = KriteriaModel::where('kriteria_kode', $kodeMap['skala'])->first();
        $kriteriaFrekuensi = KriteriaModel::where('kriteria_kode', $kodeMap['frekuensi'])->first();

        SkorAltModel::create([
            'pelaporan_id' => $pelaporanId,
            'kriteria_id' => $kriteriaSkala->kriteria_id,
            'nilai_skor' => $skalaBobot[$skala],
            'skor_alt_kode' => $pelaporanId . '-C2',
        ]);

        SkorAltModel::create([
            'pelaporan_id' => $pelaporanId,
            'kriteria_id' => $kriteriaFrekuensi->kriteria_id,
            'nilai_skor' => $frekuensiBobot[$frekuensi],
            'skor_alt_kode' => $pelaporanId . '-C3',
        ]);
    }

    public function getFormattedLaporanData()
    {
        $userId = auth()->id();

        $laporan = PelaporanModel::with([
            'fasilitas.ruang.lantai.gedung',
            'fasilitas.barang',
            'statusPelaporan' => function ($query) {
                $query->latest('created_at');
            }
        ])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return $laporan->map(function ($item) {
            $latestStatus = $item->statusPelaporan->first();
            $fasilitas = $item->fasilitas;

            $fasilitasLabel = data_get($fasilitas, 'ruang.ruang_nama') && data_get($fasilitas, 'barang.barang_nama')
                ? data_get($fasilitas, 'ruang.ruang_nama') . ' - ' .
                data_get($fasilitas, 'barang.barang_nama') . ' - ' .
                data_get($fasilitas, 'barang.barang_kode')
                : 'Informasi Fasilitas Tidak Lengkap';

            return [
                'id' => $item->pelaporan_id,
                'kode' => $item->pelaporan_kode,
                'fasilitas' => $fasilitasLabel,
                'tanggal' => $item->created_at->format('d M Y'),
                'status' => $latestStatus ? $latestStatus->status_pelaporan : 'Belum Ada Status',
            ];
        });
    }

    public function getLaporanDetailById($id): PelaporanModel
    {
        // Tambahkan 'perbaikan.statusPerbaikan' untuk memuat semua data yang kita butuhkan
        $laporan = PelaporanModel::with([
            'fasilitas.ruang.lantai.gedung',
            'fasilitas.barang',
            'statusPelaporan' => function ($q) {
                $q->latest('created_at');
            },
            'perbaikan.statusPerbaikan', // <-- [PERUBAHAN] Eager load relasi perbaikan dan statusnya
            'feedback',
        ])->findOrFail($id);

        // Bagian kode untuk membuat fasilitas_label tetap sama
        $fasilitas = $laporan->fasilitas;
        $label = '-';
        if ($fasilitas && $fasilitas->ruang
            && $fasilitas->ruang->lantai
            && $fasilitas->ruang->lantai->gedung
            && $fasilitas->barang) {
            $label = $fasilitas->ruang->lantai->gedung->gedung_nama . ' - ' .
                $fasilitas->ruang->lantai->lantai_nama . ' - ' .
                $fasilitas->ruang->ruang_nama . ' - ' .
                $fasilitas->barang->barang_nama; // Kode barang bisa ditambahkan jika perlu
        }
        $laporan->fasilitas_label = $label;

        return $laporan;
    }

    public function getSkorKriteriaByPelaporanId($pelaporanId)
    {
        return SkorAltModel::with('kriteria')
            ->where('pelaporan_id', $pelaporanId)
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->kriteria->kriteria_nama => $item->nilai_skor];
            });
    }

    public function getTotalPelaporan(): int
    {
        return DB::table('m_pelaporan')->count('pelaporan_id');
    }

    public function countLaporanDenganStatusTerakhir(string $status): int
    {
        return DB::table('t_status_pelaporan as sp')
            ->join(
                DB::raw('(
                SELECT pelaporan_id, MAX(created_at) AS latest_status_time
                FROM t_status_pelaporan
                GROUP BY pelaporan_id
            ) as latest'),
                function ($join) {
                    $join->on('sp.pelaporan_id', '=', 'latest.pelaporan_id')
                        ->on('sp.created_at', '=', 'latest.latest_status_time');
                }
            )
            ->where('sp.status_pelaporan', $status)
            ->count();
    }

    public function getAverageResponseDays(): float
    {
        $avg = DB::table('t_status_pelaporan as s')
            ->selectRaw('ROUND(AVG(TIMESTAMPDIFF(HOUR, m.created_at, s.created_at)) / 24, 1) as rata_rata_respon_hari')
            ->joinSub(
                DB::table('t_status_pelaporan')
                    ->select('pelaporan_id', DB::raw('MIN(created_at) as created_at'))
                    ->where('status_pelaporan', 'Menunggu')
                    ->groupBy('pelaporan_id'),
                'm',
                fn($join) => $join->on('s.pelaporan_id', '=', 'm.pelaporan_id')
            )
            ->where('s.status_pelaporan', 'Diproses')
            ->whereColumn('s.created_at', '>', 'm.created_at')
            ->value('rata_rata_respon_hari');

        return (float)$avg;
    }

    public function countTodayPendingReports(): int
    {
        $totalPendingToday = DB::table('m_pelaporan as p')
            ->joinSub(
                DB::table('t_status_pelaporan as sp')
                    ->joinSub(
                        DB::table('t_status_pelaporan')
                            ->select('pelaporan_id', DB::raw('MAX(created_at) as latest_status_time'))
                            ->groupBy('pelaporan_id'),
                        'latest_status',
                        function ($join) {
                            $join->on('sp.pelaporan_id', '=', 'latest_status.pelaporan_id')
                                ->on('sp.created_at', '=', 'latest_status.latest_status_time');
                        }
                    )
                    ->where('sp.status_pelaporan', 'Menunggu')
                    ->select('sp.pelaporan_id'),
                'filtered_latest_status',
                'filtered_latest_status.pelaporan_id',
                '=',
                'p.pelaporan_id'
            )
            ->whereDate('p.created_at', now()->toDateString())
            ->distinct('p.pelaporan_id')
            ->count('p.pelaporan_id');

        return $totalPendingToday;
    }

    public function getStatistikLaporanPerBulan(): Collection
    {
        return PelaporanModel::select(
            DB::raw('YEAR(created_at) as tahun'),
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw("
                    CASE MONTH(created_at)
                        WHEN 1 THEN 'Januari'
                        WHEN 2 THEN 'Februari'
                        WHEN 3 THEN 'Maret'
                        WHEN 4 THEN 'April'
                        WHEN 5 THEN 'Mei'
                        WHEN 6 THEN 'Juni'
                        WHEN 7 THEN 'Juli'
                        WHEN 8 THEN 'Agustus'
                        WHEN 9 THEN 'September'
                        WHEN 10 THEN 'Oktober'
                        WHEN 11 THEN 'November'
                        WHEN 12 THEN 'Desember'
                    END as nama_bulan
                "),

            DB::raw('COUNT(*) as total_pelaporan')
        )
            ->groupBy('tahun', 'bulan', 'nama_bulan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();
    }

    public function getStatistikLaporanPerHari(): Collection
    {
        return PelaporanModel::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw("DATE_FORMAT(created_at, '%d %M %Y') as formatted_tanggal"),
            DB::raw('COUNT(*) as total_pelaporan')
        )
            ->groupBy('tanggal', 'formatted_tanggal')
            ->orderBy('tanggal', 'desc')
            ->get();
    }

    public function getStatistikLaporanPerFasilitas(): Collection
    {
        return PelaporanModel::query()
            ->select(
                'fasilitas_id',
                DB::raw('COUNT(pelaporan_id) as jumlah_laporan')
            )
            ->groupBy('fasilitas_id')
            ->get();
    }

    public function getStatistikIntervalPerFasilitas(): Collection
    {
        // Langkah 1: Subquery untuk mendapatkan semua laporan yang statusnya 'Selesai',
        // beserta waktu kapan laporan dibuat dan kapan diselesaikan.
        $laporanSelesai = PelaporanModel::query()
            ->join('t_status_pelaporan as s', 'm_pelaporan.pelaporan_id', '=', 's.pelaporan_id')
            ->where('s.status_pelaporan', 'Selesai')
            ->select(
                'm_pelaporan.fasilitas_id',
                'm_pelaporan.created_at as waktu_dibuat',
                's.created_at as waktu_selesai'
            )
            ->orderBy('fasilitas_id')
            ->orderBy('waktu_dibuat');

        $intervalPerLaporan = DB::query()
            ->fromSub($laporanSelesai, 'lapsel')
            ->select(
                'fasilitas_id',
                'waktu_dibuat',
                'waktu_selesai',
                DB::raw('LAG(waktu_selesai, 1) OVER (PARTITION BY fasilitas_id ORDER BY waktu_dibuat) as waktu_selesai_sebelumnya')
            );

        return DB::query()
            ->fromSub($intervalPerLaporan, 'interval_data')
            ->select(
                'fasilitas_id',
                DB::raw('ROUND(AVG(DATEDIFF(waktu_dibuat, waktu_selesai_sebelumnya))) as average_interval_days')
            )
            ->whereNotNull('waktu_selesai_sebelumnya')
            ->whereColumn('waktu_dibuat', '>', 'waktu_selesai_sebelumnya')
            ->groupBy('fasilitas_id')
            ->get();
    }

    public function getReportTrends(): array
    {
        // --- Query untuk Laporan Masuk ---
        $laporanMasuk = PelaporanModel::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('year', 'month')
            ->get();

        // --- Query untuk Laporan Selesai ---
        $laporanSelesai = PelaporanModel::select(
            DB::raw('YEAR(t_status_pelaporan.created_at) as year'),
            DB::raw('MONTH(t_status_pelaporan.created_at) as month'),
            DB::raw('COUNT(DISTINCT m_pelaporan.pelaporan_id) as total')
        )
            ->join('t_status_pelaporan', 'm_pelaporan.pelaporan_id', '=', 't_status_pelaporan.pelaporan_id')
            ->where('t_status_pelaporan.status_pelaporan', 'Selesai')
            ->groupBy('year', 'month')
            ->get();

        $yearlyData = [];

        // Proses data laporan masuk
        foreach ($laporanMasuk as $data) {
            // Inisialisasi array tahun jika belum ada
            if (!isset($yearlyData[$data->year])) {
                $yearlyData[$data->year] = [
                    'laporanMasuk' => array_fill(1, 12, 0),
                    'laporanSelesai' => array_fill(1, 12, 0),
                ];
            }
            $yearlyData[$data->year]['laporanMasuk'][$data->month] = $data->total;
        }

        // Proses data laporan selesai
        foreach ($laporanSelesai as $data) {
            if (!isset($yearlyData[$data->year])) {
                $yearlyData[$data->year] = [
                    'laporanMasuk' => array_fill(1, 12, 0),
                    'laporanSelesai' => array_fill(1, 12, 0),
                ];
            }
            $yearlyData[$data->year]['laporanSelesai'][$data->month] = $data->total;
        }

        // Ubah dari associative array (index bulan) ke indexed array
        foreach ($yearlyData as $year => &$data) {
            $data['laporanMasuk'] = array_values($data['laporanMasuk']);
            $data['laporanSelesai'] = array_values($data['laporanSelesai']);
        }

        // Urutkan berdasarkan tahun (kunci array) secara menurun
        krsort($yearlyData);

        return $yearlyData;
    }
}
