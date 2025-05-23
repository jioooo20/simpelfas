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

    public function laporanDetail()
    {
        return view('pages.users.status-laporan.laporan-detail-laporan');
    }

    public function storePelaporan(StorePelaporanRequest $request, PelaporanRepository $repo): JsonResponse
    {
        try {
            $gambarPaths = $this->validateImage($request);

            $repo->StorePelaporan([
                'fasilitas_id' => $request->input('lokasi'),
                'deskripsi' => $request->input('deskripsi'),
                'gambar' => $gambarPaths,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil dikirim.'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors()
            ], 422);
        } catch (\Exception $e) {
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
        $laporan = PelaporanModel::with(['fasilitas', 'statusPelaporan' => function ($q) {
            $q->latest('created_at');
        }])->findOrFail($id);

        $latestStatus = $laporan->statusPelaporan->first();

        return view('pages.users.status-laporan.laporan-detail-modal', [
            'laporan' => $laporan,
            'status' => $latestStatus ? $latestStatus->status_pelaporan : 'Belum Ada Status',
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

}
