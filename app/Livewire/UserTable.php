<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\UserModel;
use App\Models\RoleModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UserTable extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $roleFilter = '';
    public $perPage = 10;

    // Modal states
    public $showAddModal = false;
    public $showEditModal = false;
    public $showImportModal = false;
    public $showDeleteModal = false;

    // Form data
    public $user_id;
    public $role_id;
    public $identitas;
    public $nama;
    public $email;
    public $password;
    public $password_confirmation;
    public $profile_image;

    // Import
    public $importFile;
    public $deleteUserId;

    protected $rules = [
        'role_id' => 'required|exists:m_role,role_id',
        'identitas' => 'required|string|min:10|max:255',
        'nama' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'password' => 'required|string|min:5',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = UserModel::with('role')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('identitas', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->roleFilter, function ($query) {
                $query->where('role_id', $this->roleFilter);
            })
            ->paginate($this->perPage);

        $roles = RoleModel::all();

        return view('livewire.user-table', compact('users', 'roles'));
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->showAddModal = true;
    }

    public function openEditModal($userId)
    {
        $user = UserModel::find($userId);
        $this->user_id = $user->user_id;
        $this->role_id = $user->role_id;
        $this->identitas = $user->identitas;
        $this->nama = $user->nama;
        $this->email = $user->email;
        $this->showEditModal = true;
    }

    public function openDeleteModal($userId)
    {
        $this->deleteUserId = $userId;
        $this->showDeleteModal = true;
    }

    public function openImportModal()
    {
        $this->showImportModal = true;
    }

    public function closeModals()
    {
        $this->showAddModal = false;
        $this->showEditModal = false;
        $this->showImportModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->user_id = '';
        $this->role_id = '';
        $this->identitas = '';
        $this->nama = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->profile_image = '';
        $this->importFile = null;
        $this->deleteUserId = null;
    }

    public function store()
    {
        $this->validate();

        try {
            UserModel::create([
                'role_id' => $this->role_id,
                'identitas' => $this->identitas,
                'nama' => $this->nama,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            $this->closeModals();
            $this->dispatch('showSuccessToast', 'User berhasil ditambahkan!');
        } catch (\Exception $e) {
            $this->dispatch('showErrorToast', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    public function update()
    {
        $rules = $this->rules;
        if (empty($this->password)) {
            unset($rules['password']);
        }
        $this->validate($rules);

        try {
            $user = UserModel::find($this->user_id);
            $updateData = [
                'role_id' => $this->role_id,
                'identitas' => $this->identitas,
                'nama' => $this->nama,
                'email' => $this->email,
            ];

            if (!empty($this->password)) {
                $updateData['password'] = Hash::make($this->password);
            }

            $user->update($updateData);

            $this->closeModals();
            $this->dispatch('showSuccessToast', 'User berhasil diperbarui!');
        } catch (\Exception $e) {
            $this->dispatch('showErrorToast', 'Gagal memperbarui user: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $user = UserModel::find($this->deleteUserId);
            if ($user->user_id == auth()->id()) {
                $this->dispatch('showErrorToast', 'Anda tidak bisa menghapus akun anda sendiri');
                return;
            }

            $user->delete();
            $this->closeModals();
            $this->dispatch('showSuccessToast', 'User berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23000') {
            $this->dispatch('showErrorToast', 'Akun ini memiliki data terkait yang tidak dapat dihapus. Silakan hapus data terkait terlebih dahulu.');
            } else {
            $this->dispatch('showErrorToast', 'Gagal menghapus user: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            $this->dispatch('showErrorToast', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    // public function downloadTemplate()
    // {
    //     $spreadsheet = new Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     // Headers
    //     $sheet->setCellValue('A1', 'role_id');
    //     $sheet->setCellValue('B1', 'identitas');
    //     $sheet->setCellValue('C1', 'nama');
    //     $sheet->setCellValue('D1', 'email');
    //     $sheet->setCellValue('E1', 'password');

    //     // Sample data
    //     $sheet->setCellValue('A2', '1');
    //     $sheet->setCellValue('B2', '12345678');
    //     $sheet->setCellValue('C2', 'John Doe');
    //     $sheet->setCellValue('D2', 'john@example.com');
    //     $sheet->setCellValue('E2', 'password123');

    //     $writer = new Xlsx($spreadsheet);
    //     $filename = 'template_user.xlsx';
    //     $path = storage_path('app/public/' . $filename);
    //     $writer->save($path);

    //     return response()->download($path)->deleteFileAfterSend(true);
    // }

    public function import()
    {
        $validator = Validator::make([
            'importFile' => $this->importFile,
        ], [
            'importFile' => 'file|mimes:xlsx,xls|max:2048',
        ]);

        if ($validator->fails()) {
            $this->dispatch('showErrorToast', $validator->errors()->first());
            return;
        }

        try {
            // Import logic here
            $file = $this->importFile;
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);
            $insert = [];
            if (count($data) > 1) {
                for ($i = 2; $i <= count($data); $i++) {
                    if (!empty($data[$i]['A']) && !empty($data[$i]['B']) && !empty($data[$i]['C']) && !empty($data[$i]['D']) && !empty($data[$i]['E'])) {
                        if (!RoleModel::where('role_id', $data[$i]['A'])->exists()) {
                            $this->dispatch('showErrorToast', "Role ID {$data[$i]['A']} tidak ditemukan pada baris {$i}");
                            return;
                        }

                        if (UserModel::where('identitas', $data[$i]['B'])->exists()) {
                            $this->dispatch('showErrorToast', "Identitas {$data[$i]['B']} sudah digunakan pada baris {$i}");
                            return;
                        }

                        if (strlen((string)$data[$i]['B']) < 10) {
                            $this->dispatch('showErrorToast', "Identitas pada baris {$i} harus minimal 10 digit.");
                            return;
                        }

                        if (UserModel::where('email', $data[$i]['D'])->exists()) {
                            $this->dispatch('showErrorToast', "Email {$data[$i]['D']} sudah digunakan pada baris {$i}");
                            return;
                        }

                        if (!filter_var($data[$i]['D'], FILTER_VALIDATE_EMAIL)) {
                            $this->dispatch('showErrorToast', "Format email tidak valid pada baris {$i}");
                            return;
                        }

                        $insert[] = [
                            'role_id' => $data[$i]['A'],
                            'identitas' => $data[$i]['B'],
                            'nama' => $data[$i]['C'],
                            'email' => $data[$i]['D'],
                            'password' => Hash::make($data[$i]['E']),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
            UserModel::insert($insert);


            $this->closeModals();
            $this->dispatch('showSuccessToast', 'Data berhasil diimport!');
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'validation.max.file')) {
                $this->dispatch('showErrorToast', 'File maksimal 2 MB!');
            } elseif (str_contains($e->getMessage(), 'validation.mimes')) {
                $this->dispatch('showErrorToast', 'File harus bertipe xlsx atau xls!');
            } else {
                $this->dispatch('showErrorToast', 'Gagal mengimport file: ' . $e->getMessage());
            }
        }
    }
}
