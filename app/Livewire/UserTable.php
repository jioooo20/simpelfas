<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UserModel;
use App\Models\RoleModel; // Adjusted based on your model name

class UserTable extends Component
{
    use WithPagination;

    public $search = '';
    public $showEditModal = false;

    // User fields for edit form
    public $userId;
    public $nama;
    public $email;
    public $identitas;
    public $role_id;

    // Confirmation before delete
    public $confirmingUserDeletion = false;
    public $userToDelete = null;

    protected $rules = [
        'nama' => 'required|string|max:255',
        'email' => 'required|email',
        'identitas' => 'nullable|string',
        'role_id' => 'required|exists:m_role,role_id',
    ];

    // Update search results in real-time
    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination when search changes
    }

    // Edit user - opens the modal with user data
    public function editUser($userId)
    {
        try {
            // Find user by either id or user_id
            $user = UserModel::find($userId);

            if (!$user && is_numeric($userId)) {
                $user = UserModel::where('user_id', $userId)->first();
            }

            if (!$user) {
                session()->flash('error', 'Pengguna tidak ditemukan.');
                return;
            }

            // Set form data
            $this->userId = $user->user_id ?? $user->id;
            $this->nama = $user->nama;
            $this->email = $user->email;
            $this->identitas = $user->identitas;
            $this->role_id = $user->role_id;

            // Open modal
            $this->showEditModal = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Error loading user: ' . $e->getMessage());
        }
    }

    // Update user
    public function updateUser()
    {
        $this->validate();

        try {
            // Find user
            $user = UserModel::find($this->userId);

            if (!$user && is_numeric($this->userId)) {
                $user = UserModel::where('user_id', $this->userId)->first();
            }

            if (!$user) {
                throw new \Exception('Pengguna tidak ditemukan.');
            }

            // Check if email already exists for another user
            $existingEmail = UserModel::where('email', $this->email)
                ->where('user_id', '!=', $this->userId)
                ->exists();

            // Check if identitas already exists for another user (if not null)
            $existingIdentitas = false;
            if ($this->identitas) {
                $existingIdentitas = UserModel::where('identitas', $this->identitas)
                    ->where('user_id', '!=', $this->userId)
                    ->exists();
            }

            if ($existingEmail || $existingIdentitas) {
                session()->flash('error', 'Gagal mengubah akun, identitas atau email telah digunakan.');
                $this->showEditModal = false;
                return;
            }

            // Update the user
            $user->update([
                'nama' => $this->nama,
                'email' => $this->email,
                'identitas' => $this->identitas,
                'role_id' => $this->role_id,
            ]);

            session()->flash('message', 'Pengguna berhasil diperbarui.');
            $this->showEditModal = false;
            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash('error', 'Error updating user: ' . $e->getMessage());
            $this->showEditModal = false;
        }
    }

    // Confirm delete action
    public function confirmDelete($userId)
    {
        $this->userToDelete = $userId;
        $this->confirmingUserDeletion = true;
    }

    // Delete user
    public function deleteUser()
    {
        try {
            // Find user
            $user = UserModel::find($this->userToDelete);

            if (!$user && is_numeric($this->userToDelete)) {
                $user = UserModel::where('user_id', $this->userToDelete)->first();
            }

            if (!$user) {
                throw new \Exception('User not found.');
            }

            // Delete the user
            $user->delete();

            session()->flash('message', 'Pengguna berhasil dihapus.');
            $this->confirmingUserDeletion = false;
            $this->userToDelete = null;
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    // Reset form fields
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
            ->with('role') // Eager load the role relationship
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('identitas', 'like', '%' . $this->search . '%');
            })
            ->paginate(6); // Adjust pagination as needed

        return view('livewire.user-table', [
            'table' => $users,
            'roles' => RoleModel::all(), // For dropdown in edit modal
        ]);
    }
}
