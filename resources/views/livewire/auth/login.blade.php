<div class="auth-container">
    <!-- Left Panel: Branding -->
    <div class="auth-branding">
        <div class="auth-branding-content">
            <div class="auth-logo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <h1 class="auth-brand-title">OSCA Panel</h1>
            <p class="auth-brand-subtitle">Office for Senior Citizens Affairs</p>
            <p class="auth-brand-desc">A comprehensive management system for senior citizens' services, records, and benefits administration.</p>
            <div class="auth-features">
                <div class="auth-feature-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Secure Records Management</span>
                </div>
                <div class="auth-feature-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Payroll & Benefits Processing</span>
                </div>
                <div class="auth-feature-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Real-time Reports & Analytics</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Panel: Login Form -->
    <div class="auth-form-panel">
        <div class="auth-form-container">
            <div class="auth-form-header">
                <h2>Welcome Back</h2>
                <p>Sign in to your account to continue</p>
            </div>

            {{-- Registration Pending Notice --}}
            @if(session('registration_pending'))
            <div style="background: #f0fdf4; border: 1px solid #22c55e; border-radius: 0.5rem; padding: 0.875rem 1rem; display: flex; align-items: flex-start; gap: 0.625rem; margin-bottom: 1.25rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" style="flex-shrink:0; margin-top:1px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <p style="margin:0; font-size: 0.85rem; color: #15803d; line-height: 1.5;">{{ session('registration_pending') }}</p>
            </div>
            @endif

            <form wire:submit="login" class="auth-form">
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-with-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <input type="email" id="email" wire:model="email" class="form-input" placeholder="admin@osca.gov.ph" autofocus>
                    </div>
                    @error('email') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-with-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <input type="password" id="password" wire:model="password" class="form-input" placeholder="••••••••">
                    </div>
                    @error('password') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group" style="display:flex; align-items:center; gap:0.5rem;">
                    <input type="checkbox" id="remember" wire:model="remember">
                    <label for="remember" style="margin:0; font-size:0.875rem; color: var(--text-secondary);">Remember me</label>
                </div>

                <button type="submit" class="btn btn-primary auth-submit-btn" wire:loading.attr="disabled">
                    <span wire:loading.remove>Sign In</span>
                    <span wire:loading>Signing in...</span>
                </button>
            </form>

            <div class="auth-footer-link">
                Don't have an account? <a href="/register" wire:navigate>Create one here</a>
            </div>
        </div>
    </div>
</div>
