<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PelaporanModel;
use App\Models\SkorAltModel;
use App\Models\KriteriaModel;
use App\Models\GdssResultModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RekomendasiPrioritasPerbaikan extends Component
{
    use WithPagination;

    public $showDetailModal = false;
    public $showDssResultModal = false;
    public $selectedLaporan = null;
    public $bobotKriteria = [];
    public $detailSkor = [];
    public $dssResults = [];
    public $dssSteps = []; // Add this property for processing steps
    public $activeTab = 'dosen'; // Default tab

    public function showDetail($fasilitasKode)
    {
        // Get facility details with related reports
        $fasilitas = DB::table('t_fasilitas as f')
            ->leftJoin('m_barang as b', 'f.barang_id', '=', 'b.barang_id')
            ->leftJoin('m_ruang as r', 'f.ruang_id', '=', 'r.ruang_id')
            ->leftJoin('m_lantai as l', 'r.lantai_id', '=', 'l.lantai_id')
            ->leftJoin('m_gedung as g', 'l.gedung_id', '=', 'g.gedung_id')
            ->select(
                'f.*',
                'b.barang_nama',
                'r.ruang_nama',
                'l.lantai_nama',
                'g.gedung_nama'
            )
            ->where('f.fasilitas_kode', $fasilitasKode)
            ->first();

        if ($fasilitas) {
            // Generate label fasilitas
            $label = '-';
            if ($fasilitas->gedung_nama && $fasilitas->lantai_nama && $fasilitas->ruang_nama && $fasilitas->barang_nama) {
                $label = $fasilitas->gedung_nama . ' - ' .
                    $fasilitas->lantai_nama . ' - ' .
                    $fasilitas->ruang_nama . ' - ' .
                    $fasilitas->barang_nama;
            }

            // Get reports for this facility
            $reports = DB::table('m_pelaporan as p')
                ->join('m_user as u', 'p.user_id', '=', 'u.user_id')
                ->join('m_role as r', 'u.role_id', '=', 'r.role_id')
                ->leftJoin('t_status_pelaporan as sp', function($join) {
                    $join->on('sp.pelaporan_id', '=', 'p.pelaporan_id')
                         ->whereRaw('sp.created_at = (SELECT MAX(created_at) FROM t_status_pelaporan WHERE pelaporan_id = p.pelaporan_id)');
                })
                ->where('p.fasilitas_id', $fasilitas->fasilitas_id)
                ->select(
                    'p.*',
                    'u.nama as user_nama',
                    'r.role_nama',
                    'sp.status_pelaporan'
                )
                ->orderBy('p.created_at', 'desc')
                ->get();

            // Get average scores for this facility
            $avgScores = DB::table('m_skor_alt as sa')
                ->join('m_pelaporan as p', 'sa.pelaporan_id', '=', 'p.pelaporan_id')
                ->where('p.fasilitas_id', $fasilitas->fasilitas_id)
                ->select(
                    'sa.kriteria_id',
                    DB::raw('AVG(sa.nilai_skor) as avg_skor')
                )
                ->groupBy('sa.kriteria_id')
                ->get()
                ->pluck('avg_skor', 'kriteria_id');

            $this->selectedLaporan = (object) [
                'fasilitas' => $fasilitas,
                'fasilitas_label' => $label,
                'reports' => $reports,
                'avg_scores' => $avgScores,
                'total_reports' => $reports->count()
            ];

            // Ambil bobot kriteria berdasarkan tab yang aktif
            $bobotColumn = 'w1_mhs'; // default for mahasiswa

            if ($this->activeTab == 'dosen') {
                $bobotColumn = 'w2_dsn'; // Dosen
            } elseif ($this->activeTab == 'staff') {
                $bobotColumn = 'w3_stf'; // Staff
            }

            $this->bobotKriteria = KriteriaModel::pluck($bobotColumn, 'kriteria_kode')->toArray();

            $this->showDetailModal = true;
        }
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedLaporan = null;
        $this->bobotKriteria = [];
        $this->detailSkor = [];
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage(); // Reset pagination when switching tabs
    }

    public function render()
    {
        // Filter berdasarkan tab yang aktif
        $roleFilter = null;
        switch ($this->activeTab) {
            case 'staff':
                $roleFilter = 4; // role_id untuk staff
                break;
            case 'dosen':
                $roleFilter = 5; // role_id untuk dosen
                break;
            case 'mahasiswa':
                $roleFilter = 6; // role_id untuk mahasiswa
                break;
        }

        // Query untuk mendapatkan data fasilitas dengan agregasi laporan dan skor
        $query = DB::table('t_fasilitas as f')
            ->leftJoin('m_barang as b', 'f.barang_id', '=', 'b.barang_id')
            ->leftJoin('m_pelaporan as p', 'p.fasilitas_id', '=', 'f.fasilitas_id')
            ->where('f.fasilitas_status', '=', 'Rusak')
            ->leftJoin('m_user as u', function($join) use ($roleFilter) {
                $join->on('u.user_id', '=', 'p.user_id');
                if ($roleFilter) {
                    $join->where('u.role_id', '=', $roleFilter);
                }
            })
            ->leftJoin('m_skor_alt as skor1', function($join) {
                $join->on('skor1.pelaporan_id', '=', 'p.pelaporan_id')
                     ->where('skor1.kriteria_id', '=', 1); // C1
            })
            ->leftJoin('m_skor_alt as skor2', function($join) {
                $join->on('skor2.pelaporan_id', '=', 'p.pelaporan_id')
                     ->where('skor2.kriteria_id', '=', 2); // C2
            })
            ->leftJoin('m_skor_alt as skor3', function($join) {
                $join->on('skor3.pelaporan_id', '=', 'p.pelaporan_id')
                     ->where('skor3.kriteria_id', '=', 3); // C3
            })
            ->leftJoin('m_skor_alt as skor4', function($join) {
                $join->on('skor4.pelaporan_id', '=', 'p.pelaporan_id')
                     ->where('skor4.kriteria_id', '=', 4); // C4
            })
            ->select(
                'f.barang_id',
                'f.fasilitas_kode',
                'b.barang_nama',
                DB::raw('COUNT(DISTINCT p.pelaporan_id) as total_pelaporan'),
                DB::raw('AVG(COALESCE(skor1.nilai_skor, 0)) as c1'),
                DB::raw('AVG(COALESCE(skor2.nilai_skor, 0)) as c2'),
                DB::raw('AVG(COALESCE(skor3.nilai_skor, 0)) as c3'),
                DB::raw('AVG(COALESCE(skor4.nilai_skor, 0)) as c4')
            )
            ->groupBy('f.barang_id', 'f.fasilitas_kode', 'b.barang_nama');

        // Apply filter hanya untuk fasilitas yang memiliki laporan dari role tertentu
        if ($roleFilter) {
            $query->whereExists(function($subquery) use ($roleFilter) {
                $subquery->select(DB::raw(1))
                    ->from('m_pelaporan as p2')
                    ->join('m_user as u2', 'u2.user_id', '=', 'p2.user_id')
                    ->whereColumn('p2.fasilitas_id', 'f.fasilitas_id')
                    ->where('u2.role_id', '=', $roleFilter);
            });
        }

        $laporanData = $query->orderBy('total_pelaporan', 'desc')
            ->paginate(10);

        // Load bobot kriteria berdasarkan tab yang aktif
        $bobotColumn = 'w1_mhs'; // default for mahasiswa

        if ($this->activeTab == 'dosen') {
            $bobotColumn = 'w2_dsn'; // Dosen
        } elseif ($this->activeTab == 'staff') {
            $bobotColumn = 'w3_stf'; // Staff
        }

        $this->bobotKriteria = KriteriaModel::pluck($bobotColumn, 'kriteria_kode')->toArray();

        return view('livewire.rekomendasi-prioritas-perbaikan', [
            'laporanData' => $laporanData
        ]);
    }

    protected function processGdssBorda($mabacMhsRanking, $mabacDsnRanking, $edasStfRanking)
    {
        // Menghitung jumlah alternatif untuk bobot ranking
        $alternativeCount = count($mabacMhsRanking);
        $rankWeights = range($alternativeCount - 1, 0);

        // Menyiapkan array untuk menghitung skor Borda
        $bordaScores = [];

        // Iterasi setiap alternatif
        foreach ($mabacMhsRanking as $facilityCode => $mhsRank) {
            $dsnRank = $mabacDsnRanking[$facilityCode];
            $stfRank = $edasStfRanking[$facilityCode];

            // Menghitung skor Borda untuk setiap alternatif
            $bordaScores[$facilityCode] =
                $rankWeights[$mhsRank - 1] + // -1 karena ranking mulai dari 1
                $rankWeights[$dsnRank - 1] +
                $rankWeights[$stfRank - 1];
        }

        // Mengurutkan skor dari tertinggi ke terendah
        arsort($bordaScores);

        // Membuat ranking final
        $finalRanking = [];
        $rank = 1;
        foreach ($bordaScores as $facilityCode => $score) {
            $finalRanking[$facilityCode] = $rank++;
        }

        return [
            'scores' => $bordaScores,
            'ranking' => $finalRanking
        ];
    }

    protected function processMabac($data, $weights, $roleId)
    {
        $steps = []; // Store processing steps

        // Step 1: Find max and min values for each criterion
        $maxValues = [
            'c1' => max(array_column($data, 'c1')),
            'c2' => max(array_column($data, 'c2')),
            'c3' => max(array_column($data, 'c3')),
            'c4' => max(array_column($data, 'c4'))
        ];

        $minValues = [
            'c1' => min(array_column($data, 'c1')),
            'c2' => min(array_column($data, 'c2')),
            'c3' => min(array_column($data, 'c3')),
            'c4' => min(array_column($data, 'c4'))
        ];

        $steps['original_matrix'] = $data;
        $steps['max_values'] = $maxValues;
        $steps['min_values'] = $minValues;

        // Step 2: Calculate normalized matrix
        $normalized = [];
        foreach ($data as $facilityCode => $values) {
            $normalized[$facilityCode] = [
                'c1' => $this->normalizeValue($values['c1'], $maxValues['c1'], $minValues['c1'], false),
                'c2' => $this->normalizeValue($values['c2'], $maxValues['c2'], $minValues['c2'], false),
                'c3' => $this->normalizeValue($values['c3'], $maxValues['c3'], $minValues['c3'], false),
                'c4' => $this->normalizeValue($values['c4'], $maxValues['c4'], $minValues['c4'], true) // C4 is cost
            ];
        }
        $steps['normalized_matrix'] = $normalized;

        // Step 3: Calculate weighted matrix V
        $weightedMatrix = [];
        foreach ($normalized as $facilityCode => $values) {
            $weightedMatrix[$facilityCode] = [
                'c1' => ($weights['C1'] * $values['c1']) + $weights['C1'],
                'c2' => ($weights['C2'] * $values['c2']) + $weights['C2'],
                'c3' => ($weights['C3'] * $values['c3']) + $weights['C3'],
                'c4' => ($weights['C4'] * $values['c4']) + $weights['C4']
            ];
        }
        $steps['weighted_matrix'] = $weightedMatrix;
        $steps['weights'] = $weights;

        // Step 4: Calculate border approximation area G
        $g = [];
        foreach (['c1', 'c2', 'c3', 'c4'] as $criterion) {
            $product = 1;
            foreach ($weightedMatrix as $values) {
                $product *= $values[$criterion];
            }
            $g[$criterion] = pow($product, 1/count($weightedMatrix));
        }
        $steps['border_approximation'] = $g;

        // Step 5: Calculate distance matrix Q
        $distanceMatrix = [];
        foreach ($weightedMatrix as $facilityCode => $values) {
            $distanceMatrix[$facilityCode] = [
                'c1' => $values['c1'] - $g['c1'],
                'c2' => $values['c2'] - $g['c2'],
                'c3' => $values['c3'] - $g['c3'],
                'c4' => $values['c4'] - $g['c4']
            ];
        }
        $steps['distance_matrix'] = $distanceMatrix;

        // Step 6: Calculate final scores and ranking
        $scores = [];
        foreach ($distanceMatrix as $facilityCode => $values) {
            $scores[$facilityCode] = array_sum($values);
        }

        // Sort scores descending and create ranking
        arsort($scores);
        $ranking = [];
        $rank = 1;
        foreach ($scores as $facilityCode => $score) {
            $ranking[$facilityCode] = $rank++;
        }

        return [
            'scores' => $scores,
            'ranking' => $ranking,
            'steps' => $steps
        ];
    }

    protected function processEdas($data, $weights)
    {
        $steps = []; // Store processing steps

        // Step 1: Calculate average of each criterion
        $avgValues = [
            'c1' => array_sum(array_column($data, 'c1')) / count($data),
            'c2' => array_sum(array_column($data, 'c2')) / count($data),
            'c3' => array_sum(array_column($data, 'c3')) / count($data),
            'c4' => array_sum(array_column($data, 'c4')) / count($data)
        ];

        $steps['original_matrix'] = $data;
        $steps['average_values'] = $avgValues;
        $steps['weights'] = $weights;

        // Step 2 & 3: Calculate PDA and NDA
        $pda = [];
        $nda = [];
        foreach ($data as $facilityCode => $values) {
            // PDA calculation
            $pda[$facilityCode] = [
                'c1' => max(0, ($values['c1'] - $avgValues['c1'])) / $avgValues['c1'],
                'c2' => max(0, ($values['c2'] - $avgValues['c2'])) / $avgValues['c2'],
                'c3' => max(0, ($values['c3'] - $avgValues['c3'])) / $avgValues['c3'],
                'c4' => max(0, ($avgValues['c4'] - $values['c4'])) / $avgValues['c4'] // C4 is cost
            ];

            // NDA calculation
            $nda[$facilityCode] = [
                'c1' => max(0, ($avgValues['c1'] - $values['c1'])) / $avgValues['c1'],
                'c2' => max(0, ($avgValues['c2'] - $values['c2'])) / $avgValues['c2'],
                'c3' => max(0, ($avgValues['c3'] - $values['c3'])) / $avgValues['c3'],
                'c4' => max(0, ($values['c4'] - $avgValues['c4'])) / $avgValues['c4'] // C4 is cost
            ];
        }

        $steps['pda_matrix'] = $pda;
        $steps['nda_matrix'] = $nda;

        // Step 5 & 6: Calculate SP and SN
        $sp = [];
        $sn = [];
        foreach ($pda as $facilityCode => $pdaValues) {
            $sp[$facilityCode] =
                $pdaValues['c1'] * $weights['C1'] +
                $pdaValues['c2'] * $weights['C2'] +
                $pdaValues['c3'] * $weights['C3'] +
                $pdaValues['c4'] * $weights['C4'];

            $sn[$facilityCode] =
                $nda[$facilityCode]['c1'] * $weights['C1'] +
                $nda[$facilityCode]['c2'] * $weights['C2'] +
                $nda[$facilityCode]['c3'] * $weights['C3'] +
                $nda[$facilityCode]['c4'] * $weights['C4'];
        }

        $steps['sp_values'] = $sp;
        $steps['sn_values'] = $sn;

        // Step 7 & 8: Calculate NSP and NSN
        $maxSp = max($sp);
        $maxSn = max($sn);

        $steps['max_sp'] = $maxSp;
        $steps['max_sn'] = $maxSn;

        $nsp = [];
        $nsn = [];
        foreach ($sp as $facilityCode => $value) {
            $nsp[$facilityCode] = $value / $maxSp;
            $nsn[$facilityCode] = 1 - ($sn[$facilityCode] / $maxSn);
        }

        $steps['nsp_values'] = $nsp;
        $steps['nsn_values'] = $nsn;

        // Step 9: Calculate final scores and ranking
        $scores = [];
        foreach ($nsp as $facilityCode => $value) {
            $scores[$facilityCode] = ($value + $nsn[$facilityCode]) / 2;
        }

        // Sort scores descending and create ranking
        arsort($scores);
        $ranking = [];
        $rank = 1;
        foreach ($scores as $facilityCode => $score) {
            $ranking[$facilityCode] = $rank++;
        }

        return [
            'scores' => $scores,
            'ranking' => $ranking,
            'steps' => $steps
        ];
    }

    protected function normalizeValue($value, $max, $min, $isCost)
    {
        if ($max == $min) return 0;
        return $isCost ?
            ($value - $max) / ($min - $max) :
            ($value - $min) / ($max - $min);
    }

    public function olahDss()
    {
        // Get facility data with scores for each role
        $facilityData = $this->getFacilityData();

        // Get weights for each role
        $weightsMhs = KriteriaModel::pluck('w1_mhs', 'kriteria_kode')->toArray();
        $weightsDsn = KriteriaModel::pluck('w2_dsn', 'kriteria_kode')->toArray();
        $weightsStf = KriteriaModel::pluck('w3_stf', 'kriteria_kode')->toArray();

        // Process using MABAC for students
        $mabacMhsResult = $this->processMabac($facilityData[6], $weightsMhs, 6);

        // Process using MABAC for lecturers
        $mabacDsnResult = $this->processMabac($facilityData[5], $weightsDsn, 5);

        // Process using EDAS for staff
        $edasStfResult = $this->processEdas($facilityData[4], $weightsStf);

        // Process final GDSS Borda ranking
        $finalResult = $this->processGdssBorda(
            $mabacMhsResult['ranking'],
            $mabacDsnResult['ranking'],
            $edasStfResult['ranking']
        );

        // Store or display results
        $this->dssResults = [
            'mahasiswa' => $mabacMhsResult,
            'dosen' => $mabacDsnResult,
            'staff' => $edasStfResult,
            'final' => $finalResult
        ];

        // Store processing steps
        $this->dssSteps = [
            'mahasiswa' => $mabacMhsResult['steps'],
            'dosen' => $mabacDsnResult['steps'],
            'staff' => $edasStfResult['steps']
        ];

        // Generate GDSS code based on current timestamp
        $gdssKode = now()->format('sHidmy');

        // Get all fasilitas_kode from the final result
        foreach ($finalResult['scores'] as $fasilitasKode => $score) {
            // Get pelaporan_ids for this fasilitas_kode
            $pelaporanIds = DB::table('m_pelaporan')
                ->join('t_fasilitas', 't_fasilitas.fasilitas_id', '=', 'm_pelaporan.fasilitas_id')
                ->where('t_fasilitas.fasilitas_kode', $fasilitasKode)
                ->pluck('m_pelaporan.pelaporan_id');

            // Insert a record for each pelaporan_id
            foreach ($pelaporanIds as $pelaporanId) {
                GdssResultModel::create([
                    'gdss_kode' => $gdssKode,
                    'pelaporan_id' => $pelaporanId,
                    'nilai_skor' => $score,
                    'rank' => $finalResult['ranking'][$fasilitasKode]
                ]);
            }
        }

        // Show results modal
        $this->showDssResultModal = true;
    }

    protected function getFacilityData()
    {
        // Get data for each role
        $roles = [4 => 'staff', 5 => 'dosen', 6 => 'mahasiswa'];
        $facilityData = [];

        foreach ($roles as $roleId => $roleName) {
            $data = DB::table('t_fasilitas as f')
                ->where('f.fasilitas_status', '=', 'Rusak') // Filter by facility status
                ->leftJoin('m_pelaporan as p', 'p.fasilitas_id', '=', 'f.fasilitas_id')
                ->leftJoin('m_user as u', function($join) use ($roleId) {
                    $join->on('u.user_id', '=', 'p.user_id')
                         ->where('u.role_id', '=', $roleId);
                })
                ->leftJoin('m_skor_alt as skor1', function($join) {
                    $join->on('skor1.pelaporan_id', '=', 'p.pelaporan_id')
                         ->where('skor1.kriteria_id', '=', 1);
                })
                ->leftJoin('m_skor_alt as skor2', function($join) {
                    $join->on('skor2.pelaporan_id', '=', 'p.pelaporan_id')
                         ->where('skor2.kriteria_id', '=', 2);
                })
                ->leftJoin('m_skor_alt as skor3', function($join) {
                    $join->on('skor3.pelaporan_id', '=', 'p.pelaporan_id')
                         ->where('skor3.kriteria_id', '=', 3);
                })
                ->leftJoin('m_skor_alt as skor4', function($join) {
                    $join->on('skor4.pelaporan_id', '=', 'p.pelaporan_id')
                         ->where('skor4.kriteria_id', '=', 4);
                })
                ->select(
                    'f.fasilitas_kode',
                    DB::raw('AVG(COALESCE(skor1.nilai_skor, 0)) as c1'),
                    DB::raw('AVG(COALESCE(skor2.nilai_skor, 0)) as c2'),
                    DB::raw('AVG(COALESCE(skor3.nilai_skor, 0)) as c3'),
                    DB::raw('AVG(COALESCE(skor4.nilai_skor, 0)) as c4')
                )
                ->groupBy('f.fasilitas_kode')
                ->having('c1', '>', 0) // Only include facilities with scores
                ->get()
                ->keyBy('fasilitas_kode')
                ->map(function($item) {
                    return [
                        'c1' => (float)$item->c1,
                        'c2' => (float)$item->c2,
                        'c3' => (float)$item->c3,
                        'c4' => (float)$item->c4
                    ];
                })
                ->toArray();

            $facilityData[$roleId] = $data;
        }

        return $facilityData;
    }
}
