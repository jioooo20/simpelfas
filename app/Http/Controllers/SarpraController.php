<?php

namespace App\Http\Controllers;

use App\Repositories\FasilitasRepository;
use Illuminate\Http\Request;
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
        return view('pages.sarpra.dasbor.index');
    }

    public function laporan_kerusakan_fasilitas()
    {
        return view('pages.sarpra.laporan-kerusakan-fasilitas.index');
    }

    public function rekomendasi_prioritas_perbaikan()
    {
        return view('pages.sarpra.rekomendasi-prioritas-perbaikan.index');
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
                'facilities' => $this->getFormattedFacilityRatings(),
                'yearlyRatingsData' => $this->feedbackRepository->getYearlyAverageRatings(),
                'averageResponseDays' => $this->pelaporanRepository->getAverageResponseDays(),

                // data tab frekuensi
                'fasilitasBerisiko' => $this->fasilitasRepository->getFasilitasBerisikoTinggi(),

                // data tab perencanaan
                'statistikKerusakan' => $this->pelaporanRepository->getStatistikLaporanPerBulan(),
                'statistikKerusakanHarian' => $this->pelaporanRepository->getStatistikLaporanPerHari(),
            ]
        );
    }


    private function getStatistikUmum(): array
    {
        return [
            'laporan_pending_hari_ini' => $this->pelaporanRepository->countTodayPendingReports(),
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

    protected array $statusColors = [
        'green' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
        'blue' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
        'yellow' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
        'red' => ['bg' => 'bg-red-100', 'text' => 'text-red-700'],
    ];
}
