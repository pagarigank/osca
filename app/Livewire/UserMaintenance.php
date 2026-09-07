<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Office;
use App\Models\AccessLevel;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;

#[Layout('layouts.app')]
class UserMaintenance extends Component
{
    public $users;
    public $offices;
    public $access_levels;

    // Form fields
    public $user_id;
    public $name = '';
    public $username = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $office_id = '';
    public $access_level_id = '';
    public $is_active = true;

    // Modal state
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Show all users — pending first, then alphabetically
        $this->users = User::with(['office', 'accessLevel'])
            ->orderByRaw("FIELD(approval_status, 'PENDING', 'APPROVED', 'REJECTED')")
            ->orderBy('name')
            ->get();
        $this->offices       = Office::where('is_active', true)->get();
        $this->access_levels = AccessLevel::where('is_active', true)->get();
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
        $this->isEditMode = false;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->user_id = null;
        $this->name = '';
        $this->username = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->office_id = '';
        $this->access_level_id = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function store()
    {
        $rules = [
            'name'             => 'required|string|max:255',
            'username'         => 'required|unique:users,username,' . $this->user_id,
            'email'            => 'required|email|unique:users,email,' . $this->user_id,
            'office_id'        => 'nullable|exists:offices,id',
            'access_level_id'  => 'nullable|exists:access_levels,id',
        ];

        if (!$this->isEditMode) {
            $rules['password'] = 'required|min:6|confirmed';
        } elseif ($this->password) {
            $rules['password'] = 'min:6|confirmed';
        }

        $this->validate($rules);

        $data = [
            'name'            => $this->name,
            'username'        => $this->username,
            'email'           => $this->email,
            'office_id'       => $this->office_id ?: null,
            'access_level_id' => $this->access_level_id ?: null,
            'is_active'       => $this->is_active,
        ];

        // Admin-created users are immediately approved
        if (!$this->user_id) {
            $data['approval_status'] = 'APPROVED';
        }

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(['id' => $this->user_id], $data);

        $this->dispatch('notify', message: $this->user_id ? 'User updated successfully.' : 'User created successfully.');
        $this->closeModal();
        $this->loadData();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->user_id        = $id;
        $this->name           = $user->name;
        $this->username       = $user->username;
        $this->email          = $user->email;
        $this->office_id      = $user->office_id;
        $this->access_level_id = $user->access_level_id;
        $this->is_active      = $user->is_active;
        $this->password       = '';
        $this->password_confirmation = '';
        $this->isEditMode     = true;
        $this->isModalOpen    = true;
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->approval_status = 'APPROVED';
        $user->is_active       = true;
        $user->save();

        $this->dispatch('notify', message: "User '{$user->name}' has been approved and can now log in.");
        $this->loadData();
    }

    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->approval_status = 'REJECTED';
        $user->is_active       = false;
        $user->save();

        $this->dispatch('notify', message: "User '{$user->name}' registration has been rejected.");
        $this->loadData();
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = false;
        $user->save();

        $this->dispatch('notify', message: 'User has been deactivated (soft delete).');
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.user-maintenance');
    }
}
