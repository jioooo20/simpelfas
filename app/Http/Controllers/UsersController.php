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

    public function UmpanBalik()
    {
        $userId = auth()->id(); // ambil id user yang sedang login
        $perbaikans = PelaporanModel::with('fasilitas', 'statusPelaporan') // eager load relasi
        ->where('user_id', $userId)
            ->get();
        return view('pages.users.feedback.index', compact('perbaikans'));
    }

    public function UmpanBalik_Create()
    {
        return view('pages.users.feedback.create');
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

        $gambar = [
            'Gambar Laporan' => json_decode($laporan->pelaporan_gambar ?? '[]'),
            'Gambar Perbaikan' => json_decode($laporan->gambar_perbaikan ?? '[]'),
            'Gambar Selesai' => json_decode($laporan->gambar_selesai ?? '[]'),
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
}
