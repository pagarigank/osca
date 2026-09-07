<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

#[Layout('layouts.guest')]
class Login extends Component
{
    public string $email    = '';
    public string $password = '';
    public bool   $remember = false;

    public function login()
    {
        $this->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Find the user
        $user = User::where('email', $this->email)->first();

        if (!$user || !Hash::check($this->password, $user->password)) {
            $this->addError('email', 'Invalid email or password.');
            return;
        }

        if ($user->approval_status === 'PENDING') {
            $this->addError('email', 'Your registration is pending administrator approval. Please wait for confirmation.');
            return;
        }

        if ($user->approval_status === 'REJECTED') {
            $this->addError('email', 'Your registration was rejected. Please contact the administrator for assistance.');
            return;
        }

        if (!$user->is_active) {
            $this->addError('email', 'Your account has been deactivated. Please contact your administrator.');
            return;
        }

        // Generate a JWT token — TTL is set in config/jwt.php (default: 60 min)
        // If "remember me" is checked, set a longer TTL (7 days)
        $ttl = $this->remember ? 60 * 24 * 7 : null; // null = use config default

        $token = $ttl
            ? JWTAuth::customClaims(['exp' => now()->addMinutes($ttl)->timestamp])->fromUser($user)
            : JWTAuth::fromUser($user);

        // Set the JWT token in a secure, HTTP-only cookie (protects against XSS)
        // Cookie expires matching the TTL
        $cookieMinutes = $this->remember ? 60 * 24 * 7 : 120;
        // Queue the JWT cookie (HTTP-only, protects against XSS)
        // Cookie::queue() attaches it to the next response automatically.
        \Illuminate\Support\Facades\Cookie::queue('jwt_token', $token, $cookieMinutes, '/', null, false, true);

        // Use native session auth for flawless Livewire compatibility
        auth()->login($user, $this->remember);

        return redirect()->to('/');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
