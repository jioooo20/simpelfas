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
use App\Models\SkorAltModel;
use App\Models\KriteriaModel;
use Illuminate\Support\Facades\Auth;

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
    public function UmpanBalik() {
        $userId = auth()->id(); // ambil id user yang sedang login
        $perbaikans = PelaporanModel::with('fasilitas', 'statusPelaporan') // eager load relasi
                    ->where('user_id', $userId)
                    ->get();
        return view('pages.users.feedback.index', compact('perbaikans'));
    }

    public function UmpanBalik_Create() {
        return view ('pages.users.feedback.create');
    }

    public function store() {
        
    }


    public function laporanDetail()
    {
        return view('pages.users.status-laporan.laporan-detail-laporan');
    }

//    public function storePelaporan(StorePelaporanRequest $request, PelaporanRepository $repo): JsonResponse
//    {
//        try {
//            $gambarPaths = $this->validateImage($request);
//
//            $repo->StorePelaporan([
//                'fasilitas_id' => $request->input('lokasi'),
//                'deskripsi' => $request->input('deskripsi'),
//                'gambar' => $gambarPaths,
//            ]);
//
//            return response()->json([
//                'success' => true,
//                'message' => 'Laporan berhasil dikirim.'
//            ]);
//        } catch (ValidationException $e) {
//            return response()->json([
//                'success' => false,
//                'errors' => $e->validator->errors()
//            ], 422);
//        } catch (\Exception $e) {
//            Log::error('Gagal menyimpan pelaporan: ' . $e->getMessage());
//            return response()->json([
//                'success' => false,
//                'message' => 'Terjadi kesalahan. Silakan coba lagi nanti.'
//            ], 500);
//        }
//    }

//    public function storePelaporan(StorePelaporanRequest $request, PelaporanRepository $repo): JsonResponse
//    {
//        try {
//            $gambarPaths = $this->validateImage($request);
//
//            $pelaporan = $repo->StorePelaporan([
//                'fasilitas_id' => $request->input('lokasi'),
//                'deskripsi' => $request->input('deskripsi'),
//                'gambar' => $gambarPaths,
//            ]);
//
//            // Nilai bobot konversi
//            $skalaBobot = [
//                'Ringan' => 1,
//                'Sedang' => 2,
//                'Berat'  => 3,
//            ];
//
//
//            $frekuensiBobot = [
//                'Jarang' => 1,
//                'Sedang' => 2,
//                'Sering' => 3,
//            ];
//
//            // Ambil ID kriteria dari DB
//            $kriteriaSkala = \App\Models\KriteriaModel::where('kriteria_kode', 'skala')->first();
//            $kriteriaFrekuensi = \App\Models\KriteriaModel::where('kriteria_kode', 'frekuensi')->first();
//
//            // Simpan skor alternatif
//            \App\Models\SkorAltModel::create([
//                'pelaporan_id' => $pelaporan->pelaporan_id,
//                'kriteria_id' => $kriteriaSkala->kriteria_id,
//                'nilai_skor' => $skalaBobot[$request->input('skala')],
//            ]);
//
//            \App\Models\SkorAltModel::create([
//                'pelaporan_id' => $pelaporan->pelaporan_id,
//                'kriteria_id' => $kriteriaFrekuensi->kriteria_id,
//                'nilai_skor' => $frekuensiBobot[$request->input('frekuensi')],
//            ]);
//
//            return response()->json([
//                'success' => true,
//                'message' => 'Laporan berhasil dikirim.'
//            ]);
//
//        } catch (ValidationException $e) {
//            return response()->json([
//                'success' => false,
//                'errors' => $e->validator->errors()
//            ], 422);
//        } catch (\Exception $e) {
//            Log::error('Gagal menyimpan pelaporan: ' . $e->getMessage());
//            return response()->json([
//                'success' => false,
//                'message' => 'Terjadi kesalahan. Silakan coba lagi nanti.'
//            ], 500);
//        }
//    }

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

    private function createPelaporan($request, $gambarPaths)
    {
        return $this->pelaporanRepo->StorePelaporan([
            'fasilitas_id' => $request->input('lokasi'),
            'deskripsi' => $request->input('deskripsi'),
            'gambar' => $gambarPaths,
        ]);
    }

    protected function validateImage(Request $request): ?array
    {
        $gambarPaths = [];

        if ($request->hasFile('foto')) {
            try {
                foreach ($request->file('foto') as $file) {
                    $imageInfo = getimagesize($file);
                    if ($imageInfo === false) {
                        throw ValidationException::withMessages(['foto' => 'Salah satu file bukan gambar yang valid.']);
                    }

                    $width = $imageInfo[0];
                    $height = $imageInfo[1];

                    if ($width > 5000 || $height > 5000) {
                        throw ValidationException::withMessages(['foto' => 'Resolusi salah satu gambar melebihi 5000x5000 piksel.']);
                    }

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

        // Anggap gambar-gambar ini disimpan sebagai JSON di DB
        $gambar = [
            'Gambar Laporan' => json_decode($laporan->pelaporan_gambar ?? '[]'),
            'Gambar Perbaikan' => json_decode($laporan->gambar_perbaikan ?? '[]'),
            'Gambar Selesai' => json_decode($laporan->gambar_selesai ?? '[]'),
        ];

        return view('pages.users.status-laporan.laporan-detail-modal', [
            'laporan' => $laporan,
            'status' => $latestStatus ? $latestStatus->status_pelaporan : 'Belum Ada Status',
            'gambar' => $gambar,
        ]);
    }

}
