<?php

namespace App\Http\Controllers;

use App\Models\PelaporanModel;
use App\Models\RoleModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

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

    public function import_user(Request $request)
    {
        $file = $request->file('excel_file');

        if (!$file) {
            return redirect()->route('admin.user')
                ->with('error', 'File tidak ditemukan');
        }

        try {

            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Remove header row
            array_shift($rows);

            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 because we removed header and arrays are 0-indexed
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }
                
                $roleId = $row[0] ?? null;
                $identitas = $row[1] ?? null;
                $nama = $row[2] ?? null;
                $email = $row[3] ?? null;
                $password = $row[4] ?? null;
                
                // Validate required fields
                if (empty($roleId) || empty($identitas) || empty($nama) || empty($email) || empty($password)) {
                    $errors[] = "Baris {$rowNumber}: Data tidak lengkap";
                    $errorCount++;
                    continue;
                }
                
                // Check duplicate identitas
                $existingIdentitas = UserModel::where('identitas', $identitas)->first();
                if ($existingIdentitas) {
                    $errors[] = "Baris {$rowNumber}: Identitas '{$identitas}' sudah digunakan";
                    $errorCount++;
                    continue;
                }
                
                // Check duplicate email
                $existingEmail = UserModel::where('email', $email)->first();
                if ($existingEmail) {
                    $errors[] = "Baris {$rowNumber}: Email '{$email}' sudah digunakan";
                    $errorCount++;
                    continue;
                }
                
                // Validate role exists
                $roleExists = RoleModel::where('role_id', $roleId)->exists();
                if (!$roleExists) {
                    $errors[] = "Baris {$rowNumber}: Role ID '{$roleId}' tidak ditemukan";
                    $errorCount++;
                    continue;
                }
                
                // Validate email format
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Baris {$rowNumber}: Format email tidak valid";
                    $errorCount++;
                    continue;
                }
                
                try {
                    UserModel::create([
                        'role_id' => $roleId,
                        'identitas' => $identitas,
                        'nama' => $nama,
                        'email' => $email,
                        'password' => bcrypt($password),
                    ]);
                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Baris {$rowNumber}: Gagal menyimpan data - " . $e->getMessage();
                    $errorCount++;
                }
            }

            $message = "Import selesai. {$successCount} user berhasil ditambahkan";
            if ($errorCount > 0) {
                $message .= ", {$errorCount} user gagal ditambahkan";
                if (!empty($errors)) {
                    $message .= ". Error: " . implode('; ', array_slice($errors, 0, 5));
                    if (count($errors) > 5) {
                        $message .= " dan " . (count($errors) - 5) . " error lainnya";
                    }
                }
            }

            return redirect()->route('admin.user')
                ->with($errorCount > 0 ? 'warning' : 'success', $message);

        } catch (\Exception $e) {
            return redirect()->route('admin.user')
                ->with('error', 'Gagal membaca file: ' . $e->getMessage());
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
}
