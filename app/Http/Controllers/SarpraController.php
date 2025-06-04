<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PelaporanRepository;
use App\Repositories\FeedbackRepository;
use Illuminate\Support\Collection;

class SarpraController extends Controller
{
    protected PelaporanRepository $pelaporanRepository;
    protected FeedbackRepository $feedbackRepository;

    public function __construct(PelaporanRepository $pelaporanRepository, FeedbackRepository $feedbackRepository)
    {
        $this->pelaporanRepository = $pelaporanRepository;
        $this->feedbackRepository = $feedbackRepository;
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
        $statistik = $this->getStatistikUmum();
        $facilities = $this->getFormattedFacilityRatings();

        return view('pages.sarpra.analisis-laporan.index', array_merge($statistik, [
            'facilities' => $facilities,
            'monthlyRatings' => $this->feedbackRepository->getAverageRatingByMonth(),
        ]));
    }

    private function getStatistikUmum(): array
    {
        return [
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

    private function formatFasilitasKodeHelper($rawKode)
    {
        if (!is_string($rawKode) || empty(trim($rawKode))) {
            return 'N/A';
        }

        $rawKode = strtoupper(trim($rawKode));

        // Ambil semua pasangan [HURUF]+[ANGKA max 3 digit]
        preg_match_all('/[A-Z]+[0-9]{1,3}/', $rawKode, $matches);

        $segments = $matches[0];

        // Jika ada 2 atau lebih bagian yang valid, ambil maksimal 3 dan gabungkan
        if (count($segments) >= 2) {
            return implode('-', array_slice($segments, 0, 3));
        }

        // Kemungkinan 1 bagian: tetap tampilkan seperti apa adanya
        if (count($segments) === 1) {
            return $segments[0];
        }

        // Fallback jika tidak cocok regex (tidak ada angka, dsb.)
        return $rawKode;
    }
}
