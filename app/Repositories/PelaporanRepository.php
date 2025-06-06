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
        $laporan = PelaporanModel::with([
            'fasilitas.ruang.lantai.gedung',
            'fasilitas.barang',
            'statusPelaporan' => function ($query) {
                $query->latest('created_at');
            }
        ])
            ->orderBy('created_at', 'desc')
            ->get();

        return $laporan->map(function ($item) {
            $latestStatus = $item->statusPelaporan->first();

            // Ambil data fasilitas jika tersedia
            $fasilitas = $item->fasilitas;
            if ($fasilitas && $fasilitas->ruang && $fasilitas->ruang->lantai && $fasilitas->ruang->lantai->gedung && $fasilitas->barang) {
                $fasilitasLabel =
                    $fasilitas->ruang->ruang_nama . ' - ' .
                    $fasilitas->barang->barang_nama . ' - ' .
                    $fasilitas->barang->barang_kode;
            } else {
                $fasilitasLabel = 'Informasi Fasilitas Tidak Lengkap';
            }

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
        $laporan = PelaporanModel::with([
            'fasilitas.ruang.lantai.gedung',
            'fasilitas.barang',
            'statusPelaporan' => function ($q) {
                $q->latest('created_at');
            }
        ])->findOrFail($id);

        // Buat label fasilitas
        $fasilitas = $laporan->fasilitas;
        $label = '-';
        if ($fasilitas && $fasilitas->ruang
            && $fasilitas->ruang->lantai
            && $fasilitas->ruang->lantai->gedung
            && $fasilitas->barang) {
            $label = $fasilitas->ruang->lantai->gedung->gedung_nama . ' - ' .
                $fasilitas->ruang->lantai->lantai_nama . ' - ' .
                $fasilitas->ruang->ruang_nama . ' - ' .
                $fasilitas->barang->barang_nama . ' - ' .
                $fasilitas->barang->barang_kode;
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
                fn ($join) => $join->on('s.pelaporan_id', '=', 'm.pelaporan_id')
            )
            ->where('s.status_pelaporan', 'Diproses')
            ->whereColumn('s.created_at', '>', 'm.created_at')
            ->value('rata_rata_respon_hari');

        return (float) $avg;
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
}
