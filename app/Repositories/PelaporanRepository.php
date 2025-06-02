<?php

namespace App\Repositories;

use App\Models\KriteriaModel;
use App\Models\PelaporanModel;
use App\Models\SkorAltModel;
use App\Models\StatusPelaporanModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
            'skor_alt_kode' => 'SKR-' . strtoupper(Str::random(6)),
        ]);

        SkorAltModel::create([
            'pelaporan_id' => $pelaporanId,
            'kriteria_id' => $kriteriaFrekuensi->kriteria_id,
            'nilai_skor' => $frekuensiBobot[$frekuensi],
            'skor_alt_kode' => 'SKR-' . strtoupper(Str::random(6)),
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

}
