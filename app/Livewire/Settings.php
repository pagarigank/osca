<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class Settings extends Component
{
    public $notifications = true;

    // Password change fields
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function toggleNotifications()
    {
        $this->notifications = !$this->notifications;
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'The provided password does not match your current password.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password)
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        
        $this->dispatch('notify', message: 'Password updated successfully!');
    }

    public function render()
    {
        return view('livewire.settings');
    }
}
