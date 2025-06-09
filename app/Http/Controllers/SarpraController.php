<?php

namespace App\Http\Controllers;

use App\Repositories\FasilitasRepository;
use Illuminate\Http\Request;
use App\Models\PelaporanModel;
use App\Models\PerbaikanModel;
use App\Models\FasilitasModel;
use App\Models\StatusPelaporanModel;
use App\Models\StatusPerbaikanModel;
use App\Models\FeedbackModel;
use Illuminate\Support\Facades\DB;
use App\Repositories\PelaporanRepository;
use App\Repositories\FeedbackRepository;
use Illuminate\Support\Collection;

class SarpraController extends Controller
{
    protected PelaporanRepository $pelaporanRepository;
    protected FeedbackRepository $feedbackRepository;
    protected FasilitasRepository $fasilitasRepository;

    public function __construct(PelaporanRepository $pelaporanRepository, FeedbackRepository $feedbackRepository, FasilitasRepository $fasilitasRepository)
    {
        $this->pelaporanRepository = $pelaporanRepository;
        $this->feedbackRepository = $feedbackRepository;
        $this->fasilitasRepository = $fasilitasRepository;
    }

    public function dasbor()
    {
        // Total fasilitas
        $totalFasilitas = FasilitasModel::count();

        // Total laporan
        $totalLaporan = PelaporanModel::count();

        // Total perbaikan
        $totalPerbaikan = PerbaikanModel::count();

        // Laporan menunggu verifikasi (status Menunggu)
        $laporanMenungguVerifikasi = StatusPelaporanModel::where('status_pelaporan', 'Menunggu')->count();

        // Laporan menunggu penugasan (status Diterima)
        $laporanMenungguPenugasan = StatusPelaporanModel::where('status_pelaporan', 'Diterima')->count();

        // Perbaikan dalam proses
        $perbaikanProses = StatusPerbaikanModel::where('perbaikan_status', 'Diproses')->count();

        // Perbaikan selesai bulan ini
        $perbaikanSelesaiBulanIni = StatusPerbaikanModel::where('perbaikan_status', 'Selesai')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();

        // Fasilitas berdasarkan status
        $fasilitasPerStatus = FasilitasModel::select('fasilitas_status', DB::raw('count(*) as total'))
            ->groupBy('fasilitas_status')
            ->orderByRaw("
            CASE fasilitas_status
                WHEN 'Baik' THEN 1
                WHEN 'Rusak' THEN 2
                WHEN 'Dalam Perbaikan' THEN 3
                ELSE 4
            END
            ")
            ->get();

        // Laporan per bulan (6 bulan terakhir)
        $laporanPerBulan = PelaporanModel::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        // Rating rata-rata feedback
        $ratingRataRata = FeedbackModel::avg('rating') ?? 0;

        // Laporan terbaru
        $laporanTerbaru = PelaporanModel::with(['user', 'fasilitas.barang', 'fasilitas.ruang'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('pages.sarpra.dasbor.index', compact(
            'totalFasilitas',
            'totalLaporan',
            'totalPerbaikan',
            'laporanMenungguVerifikasi',
            'laporanMenungguPenugasan',
            'perbaikanProses',
            'perbaikanSelesaiBulanIni',
            'fasilitasPerStatus',
            'laporanPerBulan',
            'ratingRataRata',
            'laporanTerbaru'
        ));
    }

    public function laporan_kerusakan_fasilitas()
    {
        return view('pages.sarpra.laporan-kerusakan-fasilitas.index');
    }

    public function rekomendasi_prioritas_perbaikan()
    {
        return view('pages.sarpra.rekomendasi-prioritas-perbaikan.index');
    }
  
    public function penugasan_perbaikan()
    {
        return view('pages.sarpra.penugasan-perbaikan.index');
    }

    public function statistikFasilitas()
    {
        $data = $this->collectStatistikData();
        return view('pages.sarpra.analisis-laporan.index', $data);
    }

    private function collectStatistikData(): array
    {
        return array_merge(
            $this->getStatistikUmum(),
            [
                // data tab analisis
                'reportTrendData' => $this->pelaporanRepository->getReportTrends(),
                'facilitiesPerformance' => $this->prepareFacilitiesPerformanceData(),
                'statusColors' => $this->statusColors,

                // data tab kepuasan
                'yearlyRatingsData' => $this->feedbackRepository->getYearlyAverageRatings(),
                'facilities' => $this->getFormattedFacilityRatings(),

                // data tab frekuensi
                'fasilitasBerisiko' => $this->getFormattedFasilitasBerisiko(),

                // data tab perencanaan
                'rekomendasiMaintenance' => $this->mapPerformanceToRecommendation($this->prepareFacilitiesPerformanceData()),
            ]
        );
    }

    private function getStatistikUmum(): array
    {
        return [
            'laporan_pending_hari_ini' => $this->pelaporanRepository->countTodayPendingReports(),
            'averageResponseDays' => $this->pelaporanRepository->getAverageResponseDays(),
            'total' => $this->pelaporanRepository->getTotalPelaporan(),
            'pending' => $this->pelaporanRepository->countLaporanDenganStatusTerakhir('Menunggu'),
            'selesai' => $this->pelaporanRepository->countLaporanDenganStatusTerakhir('Diterima'),
            'kepuasan' => $this->feedbackRepository->getAverageRating(),
        ];
    }

    private function getFormattedFacilityRatings(): Collection
    {
        return $this->feedbackRepository
            ->getFacilityRatings()
            ->transform(fn($facility) => $this->formatFacilityItem($facility));
    }

    private function getFormattedFasilitasBerisiko(): Collection
    {
        return $this->fasilitasRepository
            ->getFasilitasBerisikoTinggi()
            ->transform(fn($facility) => $this->formatFacilityItem($facility));
    }

    private function formatFacilityItem($facility)
    {
        $rawKode = $facility->original_fasilitas_kode ?? ($facility['original_fasilitas_kode'] ?? null);
        $itemCode = $this->formatFasilitasKodeHelper($rawKode);

        if (is_object($facility)) {
            $facility->item_code = $itemCode;
        } elseif (is_array($facility)) {
            $facility['item_code'] = $itemCode;
        }

        return $facility;
    }

    private function formatFasilitasKodeHelper($rawKode): string
    {
        if (!is_string($rawKode) || empty(trim($rawKode))) {
            return 'N/A';
        }

        $rawKode = strtoupper(trim($rawKode));

        preg_match_all('/[A-Z]+[0-9]{1,3}/', $rawKode, $matches);

        $segments = $matches[0];

        if (count($segments) >= 2) {
            return implode('-', array_slice($segments, 0, 3));
        }

        if (count($segments) === 1) {
            return $segments[0];
        }

        return $rawKode;
    }

    private function hitungSkorPerFasilitas(): array
    {
        $laporan = $this->pelaporanRepository->getStatistikLaporanPerFasilitas()->keyBy('fasilitas_id');
        $interval = $this->pelaporanRepository->getStatistikIntervalPerFasilitas()->keyBy('fasilitas_id');
        $rating = $this->feedbackRepository->getFacilityRatings()->keyBy('fasilitas_id');
        $fasilitasIds = $rating->keys();
        $maxLaporan = $laporan->max('jumlah_laporan') ?? 1;
        $minLaporan = $laporan->min('jumlah_laporan') ?? 1;
        $maxInterval = $interval->max('average_interval_days') ?? 1;
        $minInterval = $interval->min('average_interval_days') ?? 1;
        $maxRating = $rating->max('rata_rata_rating') ?? 5;
        $minRating = $rating->min('rata_rata_rating') ?? 0;

        $hasil = [];

        foreach ($fasilitasIds as $id) {
            $jumlahLaporan = $laporan[$id]->jumlah_laporan ?? 0;
            $intervalHari = $interval[$id]->average_interval_days ?? 0;
            $rataRating = $rating[$id]->rata_rata_rating;
            $skorLaporan = $maxLaporan == $minLaporan ? 1 : 1 - (($jumlahLaporan - $minLaporan) / ($maxLaporan - $minLaporan));
            $skorRating = $maxRating == $minRating ? 1 : ($rataRating - $minRating) / ($maxRating - $minRating);

            $skorInterval = 0;
            if ($jumlahLaporan <= 1) {
                $skorInterval = 1.0;
            } else {
                $skorInterval = $maxInterval == $minInterval ? 1 : ($intervalHari - $minInterval) / ($maxInterval - $minInterval);
            }

            $totalSkorFloat = ($skorLaporan * 0.4 + $skorRating * 0.3 + $skorInterval * 0.3) * 100;
            $totalSkor = round($totalSkorFloat, 0, PHP_ROUND_HALF_UP);

            $hasil[] = [
                'fasilitas_id' => $id,
                'jumlah_laporan' => $jumlahLaporan,
                'average_interval_days' => $intervalHari,
                'rata_rata_rating' => round($rataRating, 2),
                'skor' => $totalSkor,
            ];
        }

        return $hasil;
    }

    private function prepareFacilitiesPerformanceData(): array
    {
        $skorPerFasilitas = $this->hitungSkorPerFasilitas();
        $detailFasilitas = $this->getFormattedFacilityRatings()->keyBy('fasilitas_id');
        $performanceData = [];

        foreach ($skorPerFasilitas as $dataSkor) {
            $fasilitasId = $dataSkor['fasilitas_id'];
            $detail = $detailFasilitas->get($fasilitasId);

            if (!$detail) {
                continue;
            }

            $skor = $dataSkor['skor'];
            $status = 'Berisiko';
            $statusColor = 'red';
            if ($skor >= 85) {
                $status = 'Baik';
                $statusColor = 'green';
            } elseif ($skor >= 70) {
                $status = 'Cukup';
                $statusColor = 'blue';
            } elseif ($skor >= 50) {
                $status = 'Waspada';
                $statusColor = 'yellow';
            }

            $performanceData[] = [
                'title' => $detail->item_name,
                'subtitle' => $detail->building . ', ' . $detail->floor . ', ' . $detail->room,
                'reports' => $dataSkor['jumlah_laporan'],
                'satisfaction' => (float) $dataSkor['rata_rata_rating'],
                'interval' => $dataSkor['average_interval_days'],
                'score' => $skor,
                'status' => $status,
                'status_color' => $statusColor,
            ];
        }

        return collect($performanceData)->sortBy('score')->values()->all();
    }

    private function mapPerformanceToRecommendation(array $facilitiesPerformance): array
    {
        // Gunakan collect() dan map() untuk transformasi data
        return collect($facilitiesPerformance)->map(function ($facility) {

            // Logika untuk menentukan label aksi berdasarkan status
            switch ($facility['status']) {
                case 'Berisiko':
                    $facility['action_label'] = 'Tindakan Segera';
                    break;
                case 'Waspada':
                    $facility['action_label'] = 'Perlu Dijadwalkan';
                    break;
                case 'Cukup':
                    $facility['action_label'] = 'Observasi Rutin';
                    break;
                case 'Baik':
                    $facility['action_label'] = 'Kondisi Optimal';
                    break;
                default:
                    $facility['action_label'] = 'Periksa';
            }

            return $facility; // Kembalikan data fasilitas yang sudah dimodifikasi

        })->all();
    }

    protected array $statusColors = [
        'green' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
        'blue' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
        'yellow' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
        'red' => ['bg' => 'bg-red-100', 'text' => 'text-red-700'],
    ];
}
