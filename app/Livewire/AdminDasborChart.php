<?php

namespace App\Livewire;

use App\Models\PelaporanModel;
use App\Models\StatusPelaporanModel;
use Livewire\Component;
use Carbon\Carbon;

class AdminDasborChart extends Component
{
    public $chartData;

    public function mount()
    {
        $this->loadChartData();
    }

    public function loadChartData()
    {
        $months = [];
        $laporanMasukData = [];
        $laporanDiterimaData = [];
        $laporanDiprosesData = [];
        $laporanSelesaiData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->locale('id')->isoFormat('MMM YYYY');

            $laporanMasuk = PelaporanModel::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $laporanMasukData[] = $laporanMasuk;

            $laporanDiterima = StatusPelaporanModel::join('m_pelaporan', 't_status_pelaporan.pelaporan_id', '=', 'm_pelaporan.pelaporan_id')
                ->where('t_status_pelaporan.status_pelaporan', 'Diterima')
                ->whereYear('t_status_pelaporan.created_at', $date->year)
                ->whereMonth('t_status_pelaporan.created_at', $date->month)
                ->count();
            $laporanDiterimaData[] = $laporanDiterima;

            $laporanDiproses = StatusPelaporanModel::join('m_pelaporan', 't_status_pelaporan.pelaporan_id', '=', 'm_pelaporan.pelaporan_id')
                ->where('t_status_pelaporan.status_pelaporan', 'Diproses')
                ->whereYear('t_status_pelaporan.created_at', $date->year)
                ->whereMonth('t_status_pelaporan.created_at', $date->month)
                ->count();
            $laporanDiprosesData[] = $laporanDiproses;

            $laporanSelesai = StatusPelaporanModel::join('m_pelaporan', 't_status_pelaporan.pelaporan_id', '=', 'm_pelaporan.pelaporan_id')
                ->where('t_status_pelaporan.status_pelaporan', 'Selesai')
                ->whereYear('t_status_pelaporan.created_at', $date->year)
                ->whereMonth('t_status_pelaporan.created_at', $date->month)
                ->count();
            $laporanSelesaiData[] = $laporanSelesai;
        }

        $this->chartData = [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'Laporan Masuk',
                    'data' => $laporanMasukData,
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
                [
                    'label' => 'Laporan Diterima',
                    'data' => $laporanDiterimaData,
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                ],
                [
                    'label' => 'Laporan Diproses',
                    'data' => $laporanDiprosesData,
                    'borderColor' => '#F59E0B',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                ],
                [
                    'label' => 'Laporan Selesai',
                    'data' => $laporanSelesaiData,
                    'borderColor' => '#8B5CF6',
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                ]
            ]
        ];
    }

    public function render()
    {
        return view('livewire.admin-dasbor-chart');
    }
}
