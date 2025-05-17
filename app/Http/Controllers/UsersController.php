<?php

namespace App\Http\Controllers;

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

    public function storePelaporan(StorePelaporanRequest $request, PelaporanRepository $repo)
    {
        try {
            $gambarPath = $this->validateImage($request);

            $repo->StorePelaporan([
                'fasilitas_id' => $request->input('lokasi'),
                'deskripsi' => $request->input('deskripsi'),
                'gambar' => $gambarPath,
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

    protected function validateImage(Request $request): ?string
    {
        if ($request->hasFile('foto')) {
            $request->validate([
                'foto' => 'file|mimetypes:image/jpeg,image/png|max:10240',
            ]);

            try {
                $file = $request->file('foto');

                $imageInfo = getimagesize($file);
                if ($imageInfo === false) {
                    throw ValidationException::withMessages(['foto' => 'File yang diupload bukan gambar yang valid.']);
                }

                $width = $imageInfo[0];
                $height = $imageInfo[1];

                if ($width > 5000 || $height > 5000) {
                    throw ValidationException::withMessages(['foto' => 'Resolusi gambar maksimal 5000x5000 piksel.']);
                }

                $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                return $file->storeAs('pelaporan', $filename, 'public');

            } catch (Exception $e) {
                Log::error($e);
                throw ValidationException::withMessages(['foto' => 'Gagal memproses gambar, silakan coba lagi.']);
            }
        }

        return null;
    }
}
