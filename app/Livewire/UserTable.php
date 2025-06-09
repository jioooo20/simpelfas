<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UserModel;
use App\Models\RoleModel;

class UserTable extends Component
{
    use WithPagination;

    public $search = '';
    public $showEditModal = false;

    public $userId;
    public $nama;
    public $email;
    public $identitas;
    public $role_id;

    public $confirmingUserDeletion = false;
    public $userToDelete = null;

    protected $rules = [
        'nama' => 'required|string|max:255',
        'email' => 'required|email',
        'identitas' => 'nullable|string',
        'role_id' => 'required|exists:m_role,role_id',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    public function editUser($userId)
    {
        try {
            $user = UserModel::find($userId);

            if (!$user && is_numeric($userId)) {
                $user = UserModel::where('user_id', $userId)->first();
            }

            if (!$user) {
                $this->dispatch('showErrorToast', 'Pengguna tidak ditemukan.');
                return;
            }

            $this->userId = $user->user_id ?? $user->id;
            $this->nama = $user->nama;
            $this->email = $user->email;
            $this->identitas = $user->identitas;
            $this->role_id = $user->role_id;

            $this->showEditModal = true;
        } catch (\Exception $e) {
            $this->dispatch('showErrorToast', 'Error loading user: ' . $e->getMessage());
        }
    }

    public function updateUser()
    {
        $this->validate();

        try {
            $user = UserModel::find($this->userId);

            if (!$user && is_numeric($this->userId)) {
                $user = UserModel::where('user_id', $this->userId)->first();
            }

            if (!$user) {
                throw new \Exception('Pengguna tidak ditemukan.');
            }

            $existingEmail = UserModel::where('email', $this->email)
                ->where('user_id', '!=', $this->userId)
                ->exists();

            $existingIdentitas = false;
            if ($this->identitas) {
                $existingIdentitas = UserModel::where('identitas', $this->identitas)
                    ->where('user_id', '!=', $this->userId)
                    ->exists();
            }

            if ($existingEmail || $existingIdentitas) {
                $this->dispatch('showErrorToast', 'Email atau identitas sudah digunakan oleh pengguna lain.');
                $this->showEditModal = false;
                return;
            }

            $user->update([
                'nama' => $this->nama,
                'email' => $this->email,
                'identitas' => $this->identitas,
                'role_id' => $this->role_id,
            ]);

            $this->dispatch('showSuccessToast', 'Pengguna berhasil diperbarui.');
            $this->showEditModal = false;
            $this->resetForm();
        } catch (\Exception $e) {
            $this->dispatch('showErrorToast', 'Error updating user: ' . $e->getMessage());
            $this->showEditModal = false;
        }
    }

    public function confirmDelete($userId)
    {
        $user = UserModel::findOrFail($userId);
        $this->nama = $user->nama;
        $this->userToDelete = $userId;
        $this->confirmingUserDeletion = true;
    }

    public function deleteUser()
    {
        try {
            $user = UserModel::find($this->userToDelete);

            if (!$user && is_numeric($this->userToDelete)) {
                $user = UserModel::where('user_id', $this->userToDelete)->first();
            }

            if (!$user) {
                throw new \Exception('User not found.');
            }

            $user->delete();

            $this->dispatch('showSuccessToast', 'Pengguna berhasil dihapus.');
            $this->confirmingUserDeletion = false;
            $this->userToDelete = null;
        } catch (\Exception $e) {
            $this->dispatch('showErrorToast', 'Error deleting user: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->userId = null;
        $this->nama = '';
        $this->email = '';
        $this->identitas = '';
        $this->role_id = '';
    }

    public function render()
    {
        $users = UserModel::query()
            ->with('role')
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('identitas', 'like', '%' . $this->search . '%');
            })
            ->paginate(6); // Adjust pagination as needed

        return view('livewire.user-table', [
            'table' => $users,
            'roles' => RoleModel::all(),
        ]);
    }
}
