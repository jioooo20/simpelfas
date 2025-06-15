<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PelaporanModel;
use App\Models\StatusPelaporanModel;
use App\Models\FasilitasModel;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dasbor()
    {
        $laporan_total = PelaporanModel::count();
        $status_laporan = PelaporanModel::join('t_status_pelaporan', function ($join) {
            $join->on('m_pelaporan.pelaporan_id', '=', 't_status_pelaporan.pelaporan_id')
                ->whereIn('t_status_pelaporan.status_pelaporan_id', function ($query) {
                    $query->select(DB::raw('MAX(status_pelaporan_id)'))
                        ->from('t_status_pelaporan as tsp')
                        ->whereColumn('tsp.pelaporan_id', 't_status_pelaporan.pelaporan_id');
                });
        })
            ->selectRaw('t_status_pelaporan.status_pelaporan, COUNT(*) as count')
            ->groupBy('t_status_pelaporan.status_pelaporan')
            ->pluck('count', 'status_pelaporan');

        return view('pages.admin.dasbor.index', compact('laporan_total', 'status_laporan'));
    }


    public function user()
    {
        $table = UserModel::with('role');
        $roles = RoleModel::all();
        return view('pages.admin.manage-user.index', compact('table', 'roles'));
    }

    public function laporan_statistik (){
        $table = PelaporanModel::with('fasilitas','user', 'statusPelaporan')->paginate(10);
        $fasilitas = FasilitasModel::all();
        $user = UserModel::all();
        $status = StatusPelaporanModel::all();
        return view('pages.admin.laporan-statistik.index', compact('table', 'fasilitas', 'user','status'));
    }

    public function laporan_dan_statistik(Request $request)
    {
        $start = $request->input('start_date') ?? Carbon::now()->startOfMonth();
        $end = $request->input('end_date') ?? Carbon::now()->endOfMonth();

        $table = PelaporanModel::with(['fasilitas', 'fasilitas.barang', 'user', 'statusPelaporan',
        'skorAlternatif.kriteria'])
            ->whereBetween('created_at', [$start, $end])
            ->paginate(10);

        //  statistik laporan - buat ringkasan
            $totalLaporan = PelaporanModel::whereBetween('created_at', [$start, $end])->count();

            $selesai = StatusPelaporanModel::where('status_pelaporan', 'selesai')
                ->whereHas('pelaporan', function ($q) use ($start, $end) {
                    $q->whereBetween('created_at', [$start, $end]);
                })->count();

            $proses = StatusPelaporanModel::where('status_pelaporan', 'dalam_proses')
                ->whereHas('pelaporan', function ($q) use ($start, $end) {
                    $q->whereBetween('created_at', [$start, $end]);
                })->count();

            $ditolak = StatusPelaporanModel::where('status_pelaporan', 'ditolak')
                ->whereHas('pelaporan', function ($q) use ($start, $end) {
                    $q->whereBetween('created_at', [$start, $end]);
                })->count();

        // referensi diambil semua
        $fasilitas = FasilitasModel::all();
        $user = UserModel::all();
        $status = StatusPelaporanModel::all();

        // Grafik Tren Laporan (jumlah laporan per bulan)
        $laporanPerBulan = PelaporanModel::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"),
            DB::raw("COUNT(*) as jumlah")
        )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $kerusakanPerBarang = PelaporanModel::join('t_fasilitas', 'm_pelaporan.fasilitas_id', '=', 't_fasilitas.fasilitas_id')
            ->join('m_barang', 't_fasilitas.barang_id', '=', 'm_barang.barang_id')
            ->whereBetween('m_pelaporan.created_at', [$start, $end])
            ->select('m_barang.barang_nama', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('m_barang.barang_id', 'm_barang.barang_nama')
            ->pluck('jumlah', 'barang_nama');

        return view('pages.admin.laporan-statistik.index', compact(
            'table',
            'totalLaporan',
            'selesai',
            'proses',
            'ditolak',
            'start',
            'end',
            'table',
            'fasilitas',
            'user',
            'status',
            'laporanPerBulan',
            'kerusakanPerBarang'
        ));
    }

    public function user_add(Request $request)
    {
        try {
            $existingIdentitas = UserModel::where('identitas', $request->identitas)->first();
            if ($existingIdentitas) {
                return redirect()->route('admin.user')
                    ->withInput()
                    ->with('error', 'Gagal membuat akun, Identitas sudah digunakan!');
            }

            $existingEmail = UserModel::where('email', $request->email)->first();
            if ($existingEmail) {
                return redirect()->route('admin.user')
                    ->withInput()
                    ->with('error', 'Gagal membuat akun, Email sudah digunakan!');
            }

            $validator = Validator::make($request->all(), [
                'nama' => 'required|max:50',
                'identitas' => 'required|max:20|unique:m_user,identitas',
                'email' => 'required|email|max:60|unique:m_user,email',
                'password' => 'required|min:5|max:20',
                'role_id' => 'required|exists:m_role,role_id',
            ]);

            if ($validator->fails()) {
                return redirect()->route('admin.user')
                    ->withInput()
                    ->with('error', $validator->errors()->first());
            }

            UserModel::create([
                'nama' => $request->nama,
                'identitas' => $request->identitas,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => $request->role_id,
            ]);

            return redirect()->route('admin.user')
                ->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->route('admin.user')
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function gedung()
    {
        return view('pages.admin.gedung.index');
    }
    public function fasilitas()
    {
        return view('pages.admin.fasilitas.index');
    }

    // public function laporan_statistik() {
    //     $laporan = [
    //         ['judul' => 'Perbaikan AC', 'status' => 'Selesai', 'tanggal' => '2025-05-01'],
    //         ['judul' => 'Kerusakan Internet', 'status' => 'Diproses', 'tanggal' => '2025-05-20'],
    //     ];

    //     return view('pages.admin.laporan-statistik.index', compact('laporan'));
    // }
}
