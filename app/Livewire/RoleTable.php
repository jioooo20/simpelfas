<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RoleModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Validator;

class RoleTable extends Component
{
    use WithPagination;

    public $role_id;
    public $role_kode;
    public $role_nama;
    public $role_deskripsi;

    public $editModal = false;
    public $deleteModal = false;

    protected $listeners = ['refreshRoles' => '$refresh'];

    public function render()
    {
        $table = RoleModel::with('user')
            ->select('m_role.*')
            ->selectRaw('COUNT(m_user.user_id) as jumlah_user')
            ->leftJoin('m_user', 'm_role.role_id', '=', 'm_user.role_id')
            ->groupBy('m_role.role_id')
            ->paginate(10);

        return view('livewire.role-table', compact('table'));
    }

    public function openEditModal($id)
    {
        $role = RoleModel::findOrFail($id);
        $this->role_id = $role->role_id;
        $this->role_kode = $role->role_kode;
        $this->role_nama = $role->role_nama;
        $this->role_deskripsi = $role->role_deskripsi;

        $this->editModal = true;
    }

    public function closeEditModal()
    {
        $this->editModal = false;
        $this->resetInputFields();
    }

    public function openDeleteModal($id)
    {
        $role = RoleModel::findOrFail($id);
        $this->role_id = $role->role_id;
        $this->role_nama = $role->role_nama;

        $this->deleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->deleteModal = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->role_id = null;
        $this->role_kode = '';
        $this->role_nama = '';
        $this->role_deskripsi = '';
    }

    public function update()
    {
                    $validator = Validator::make([
            'role_kode' => $this->role_kode,
            'role_nama' => $this->role_nama,
            'role_deskripsi' => $this->role_deskripsi,
        ], [
            'role_kode' => 'required|max:10',
            'role_nama' => 'required|max:50',
            'role_deskripsi' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            $this->dispatch('showErrorToast', $validator->errors()->first());
            return;
        }

        $existingRole = RoleModel::where('role_kode', $this->role_kode)
            ->where('role_id', '!=', $this->role_id)
            ->first();

        if ($existingRole) {
            $this->dispatch('showErrorToast', 'Kode sudah digunakan!!');
            return;
        }

        try {
            $role = RoleModel::findOrFail($this->role_id);
            $role->role_kode = $this->role_kode;
            $role->role_nama = $this->role_nama;
            $role->role_deskripsi = $this->role_deskripsi;
            $role->save();

            $this->dispatch('showSuccessToast', 'Hak akses berhasil diperbarui');
            $this->closeEditModal();
        } catch (\Exception $e) {
            $this->dispatch('showErrorToast', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $userCount = UserModel::where('role_id', $this->role_id)->count();
            if ($userCount > 0) {
                $this->dispatch('showErrorToast', 'Role ini masih memiliki pengguna');
                $this->closeDeleteModal();
                return;
            }

            RoleModel::findOrFail($this->role_id)->delete();
            $this->dispatch('showSuccessToast', 'Hak akses berhasil dihapus');
            $this->closeDeleteModal();
        } catch (\Exception $e) {
            $this->dispatch('showErrorToast', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
