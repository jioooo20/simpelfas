<?php

namespace App\Http\Controllers;

use App\Models\PelaporanModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Repositories\FasilitasRepository;
use App\Repositories\PelaporanRepository;
use App\Http\Requests\StorePelaporanRequest;
use App\Models\FeedbackModel;

class UsersController extends Controller
{
    protected FasilitasRepository $fasilitasRepo;
    protected PelaporanRepository $pelaporanRepo;

    public function __construct(FasilitasRepository $fasilitasRepo, PelaporanRepository $pelaporanRepo)
    {
        $this->fasilitasRepo = $fasilitasRepo;
        $this->pelaporanRepo = $pelaporanRepo;
    }

    public function index()
    {
        return view('pages.users.laporan.index');
    }

    public function statusLaporan()
    {
        return view('pages.users.status-laporan.index');
    }

    public function storePelaporan(StorePelaporanRequest $request): JsonResponse
    {
        try {
            $gambarPaths = $this->validateImage($request);
            $pelaporan = $this->createPelaporan($request, $gambarPaths);
            $this->pelaporanRepo->simpanSkorAlternatif(
                $pelaporan->pelaporan_id,
                $request->input('skala'),
                $request->input('frekuensi')
            );

            sendRoleNotification(
                [2], //Sarpra
                'Laporan Kerusakan Baru',
                'Segera periksa laporan kerusakan baru dari pengguna.',
                route('sarpra.laporan-kerusakan-fasilitas')
            );

            sendRoleNotification(
                [1], //Admin
                'Laporan Kerusakan Baru',
                'Pantau laporan kerusakan baru yang telah dibuat oleh pengguna.',
                route('laporan.index')
            );


            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil dikirim.'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors()
            ], 422);
        } catch (Exception $e) {
            Log::error('Gagal menyimpan pelaporan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi nanti.'
            ], 500);
        }
    }

    public function getLokasiOptions(): JsonResponse
    {
        $lokasiList = $this->fasilitasRepo->getLokasiOptions();
        return response()->json($lokasiList);
    }

    public function getLaporanData(): JsonResponse
    {
        $formatted = $this->pelaporanRepo->getFormattedLaporanData();
        return response()->json($formatted);
    }

    public function getLaporanDetail($id)
    {
        $laporan = $this->pelaporanRepo->getLaporanDetailById($id);
        $latestStatus = $laporan->statusPelaporan->first();
        $skor = $this->pelaporanRepo->getSkorKriteriaByPelaporanId($laporan->pelaporan_id);

        // Gunakan collection untuk mengambil semua gambar dengan cara yang lebih bersih
        $allStatusPerbaikan = $laporan->perbaikan?->statusPerbaikan ?? collect();

        // [PERBAIKAN] Mengambil path gambar sebagai string tunggal, bukan JSON
        // 1. Ambil semua gambar 'Diproses'
        $gambarPerbaikan = $allStatusPerbaikan
            ->where('perbaikan_status', 'Diproses')
            ->pluck('perbaikan_gambar') // Langsung ambil nilai dari kolom 'perbaikan_gambar'
            ->filter()                  // Hapus item yang null atau kosong dari koleksi
            ->values()
            ->all();

        // 2. Ambil semua gambar 'Selesai'
        $gambarSelesai = $allStatusPerbaikan
            ->where('perbaikan_status', 'Selesai')
            ->pluck('perbaikan_gambar') // Langsung ambil nilai dari kolom 'perbaikan_gambar'
            ->filter()                  // Hapus item yang null atau kosong
            ->values()
            ->all();

        // Susun array gambar final
        $gambar = [
            // Untuk Gambar Laporan, kita tetap pakai json_decode karena datanya memang array
            'Gambar Laporan' => json_decode($laporan->pelaporan_gambar ?? '[]', true),
            'Gambar Perbaikan' => $gambarPerbaikan,
            'Gambar Selesai' => $gambarSelesai,
        ];

        return view('pages.users.status-laporan.laporan-detail', [
            'laporan' => $laporan,
            'status' => $latestStatus ? $latestStatus->status_pelaporan : 'Belum Ada Status',
            'gambar' => $gambar,
            'skor' => $skor,
            'frekuensiLabels' => $this->getFrekuensiLabels(),
            'skalaLabels' => $this->getSkalaLabels(),
        ]);
    }

    private function createPelaporan($request, $gambarPaths)
    {
        return $this->pelaporanRepo->StorePelaporan([
            'fasilitas_id' => $request->input('lokasi'),
            'deskripsi' => $request->input('deskripsi'),
            'gambar' => $gambarPaths,
        ]);
    }

    private function getFrekuensiLabels(): array
    {
        return [
            1 => 'Jarang',
            2 => 'Sedang',
            3 => 'Sering',
        ];
    }

    private function getSkalaLabels(): array
    {
        return [
            1 => 'Ringan',
            2 => 'Sedang',
            3 => 'Berat',
        ];
    }

    protected function validateImage(Request $request): ?array
    {
        $gambarPaths = [];

        if ($request->hasFile('foto')) {
            try {
                $totalSize = 0; // total ukuran dalam KB

                foreach ($request->file('foto') as $file) {
                    $totalSize += $file->getSize() / 1024; // convert dari byte ke KB

                    $imageInfo = getimagesize($file);
                    if ($imageInfo === false) {
                        throw ValidationException::withMessages(['foto' => 'Salah satu file bukan gambar yang valid.']);
                    }

                    $width = $imageInfo[0];
                    $height = $imageInfo[1];

                    if ($width > 5000 || $height > 5000) {
                        throw ValidationException::withMessages(['foto' => 'Resolusi salah satu gambar melebihi 5000x5000 piksel.']);
                    }
                }

                if ($totalSize > 10240) {
                    throw ValidationException::withMessages(['foto' => 'Total ukuran gambar tidak boleh lebih dari 10MB.']);
                }

                // Jika lolos semua validasi, simpan file
                foreach ($request->file('foto') as $file) {
                    $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('pelaporan/menunggu', $filename, 'public');
                    $gambarPaths[] = $path;
                }

                return $gambarPaths;
            } catch (Exception $e) {
                Log::error($e);
                throw ValidationException::withMessages(['foto' => 'Gagal memproses gambar, silakan coba lagi.']);
            }
        }

        return null;
    }

    public function UmpanBalik()
{
    $userId = auth()->id();

    // Tambahkan eager loading untuk relasi barang
    $perbaikan = PelaporanModel::with([
        'fasilitas.barang', // Tambahkan ini
        'statusPelaporan' => function ($query) {
            $query->orderBy('created_at');
        }
    ])
    ->where('user_id', $userId)
    ->get();

    // Filter laporan yang status terakhirnya adalah SELESAI
    $perbaikan = $perbaikan->filter(function ($item) {
        $lastStatus = $item->statusPelaporan->sortBy('created_at')->last();
        return $lastStatus && strtoupper($lastStatus->status_pelaporan) === 'SELESAI';
    });

    $fasilitasOptions = $this->fasilitasRepo->getLokasiOptions()->keyBy('id');

    $perbaikan->transform(function ($item) use ($fasilitasOptions) {
        $item['fasilitas_label'] = $fasilitasOptions[$item->fasilitas_id]['label'] ?? null;
        
        $fotoTeknisi = [];
        if ($item->perbaikan && $item->perbaikan->perbaikan_gambar) {
            $fotoTeknisi = json_decode($item->perbaikan->perbaikan_gambar, true) ?? [];
        }
        $item['foto_teknisi'] = $fotoTeknisi;
        // Tambahkan format nama barang dengan kode
        $item['barang_with_code'] = 
            ($item->fasilitas->barang->barang_nama ?? 'Unknown') . 
            ' - ' . 
            substr($item->pelaporan_kode, -2);
            
        return $item;
    });

    return view('pages.users.feedback.index', compact('perbaikan'));
}


    public function UmpanBalik_Create($perbaikan_id)
{
    $laporan = PelaporanModel::with([
        'fasilitas.barang',
        'perbaikan.statusPerbaikan' => function ($query) {
            $query->orderBy('created_at', 'desc');
        },
        'feedback' // Tambahkan relasi ke tabel feedback
    ])->findOrFail($perbaikan_id);

    $fasilitasOptions = $this->fasilitasRepo->getLokasiOptions()->keyBy('id');
    $laporan->fasilitas_label = $fasilitasOptions[$laporan->fasilitas_id]['label'] ?? null;

    // Ambil foto teknisi dari status perbaikan - PERBAIKAN
    $fotoTeknisi = [];
    if ($laporan->perbaikan && $laporan->perbaikan->statusPerbaikan) {
        // Metode 1: Mengambil record pertama (terbaru karena sudah diurutkan)
        $statusTerbaru = $laporan->perbaikan->statusPerbaikan->first();
        if ($statusTerbaru && $statusTerbaru->perbaikan_gambar) {
            $fotoTeknisi = json_decode($statusTerbaru->perbaikan_gambar, true) ?? [];
        }
    }

    return view('pages.users.feedback.create', [
        'laporan' => $laporan,
        'fotoTeknisi' => $fotoTeknisi,
        'hasFeedback' => $laporan->feedback()->exists() //  flag  pengecekan
    ]);
}

public function show($pelaporan_id)
{
    $feedback = FeedbackModel::with('pelaporan')
                ->where('pelaporan_id', $pelaporan_id)
                ->firstOrFail();
    
    return view('pages.users.feedback.detail', compact('feedback'));
}

    public function storeFeedback(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'report_id' => 'required|exists:m_pelaporan,pelaporan_id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        try {
            // Cek apakah feedback untuk laporan ini sudah ada
            $existingFeedback = FeedbackModel::where('pelaporan_id', $validated['report_id'])->first();

            if ($existingFeedback) {
                // Jika request AJAX, return JSON response
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'message' => 'Anda sudah memberikan umpan balik untuk laporan ini sebelumnya.'
                    ], 409); // 409 Conflict status code
                }

                // Jika bukan AJAX, return redirect seperti biasa
                return redirect()->back()
                    ->with('error', 'Anda sudah memberikan umpan balik untuk laporan ini sebelumnya.')
                    ->withInput();
            }

            // Buat feedback baru
            FeedbackModel::create([
                'pelaporan_id' => $validated['report_id'],
                'feedback_text' => $validated['comment'],
                'rating' => $validated['rating'],
            ]);

            // Jika request AJAX, return JSON response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Umpan balik berhasil dikirim! Terima kasih atas masukan Anda.',
                    'status' => 'success'
                ], 200);
            }

            // Jika bukan AJAX, return redirect seperti biasa
            return redirect()->route('users.feedback')
                ->with('success', 'Umpan balik berhasil dikirim! Terima kasih atas masukan Anda.');
        } catch (ValidationException $e) {
            // Handle validation errors
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Data yang dikirim tidak valid.',
                    'errors' => $e->validator->errors()
                ], 422); // 422 Unprocessable Entity
            }

            // Jika bukan AJAX, throw exception seperti biasa
            throw $e;
        } catch (Exception $e) {
            // Handle general errors
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Terjadi kesalahan saat mengirim umpan balik: ' . $e->getMessage()
                ], 500); // 500 Internal Server Error
            }

            // Jika bukan AJAX, return redirect seperti biasa
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengirim umpan balik: ' . $e->getMessage())
                ->withInput();
        }
    }
}
