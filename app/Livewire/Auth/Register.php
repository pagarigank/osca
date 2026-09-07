<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;
use App\Models\Office;
use Tymon\JWTAuth\Facades\JWTAuth;

#[Layout('layouts.guest')]
class Register extends Component
{
    public string $name                  = '';
    public string $username              = '';
    public string $email                 = '';
    public string $password              = '';
    public string $password_confirmation = '';
    public        $office_id             = '';

    public $offices = [];

    public function mount()
    {
        $this->offices = Office::where('is_active', true)->orderBy('name')->get();
    }

    public function register()
    {
        $this->validate([
            'name'      => 'required|string|max:255',
            'username'  => 'required|string|max:50|unique:users,username',
            'email'     => 'required|email|unique:users,email',
            'office_id' => 'required|exists:offices,id',
            'password'  => 'required|min:6|confirmed',
        ], [
            'office_id.required' => 'Please select your office.',
            'office_id.exists'   => 'The selected office is invalid.',
        ]);

        $user = User::create([
            'name'            => $this->name,
            'username'        => $this->username,
            'email'           => $this->email,
            'password'        => Hash::make($this->password),
            'office_id'       => $this->office_id,
            'is_active'       => false,          // locked until approved
            'approval_status' => 'PENDING',      // awaiting admin action
        ]);

        // Self-assign audit fields after creation (avoid booted event loop)
        $user->created_by = $user->id;
        $user->updated_by = $user->id;
        $user->saveQuietly();

        // Do NOT log in — redirect to login with a pending notice
        session()->flash('registration_pending',
            'Your account has been created and is pending administrator approval. You will be notified once approved.'
        );

        return redirect()->to('/login');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
