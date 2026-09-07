<div class="auth-container">
    <!-- Left Panel: Branding -->
    <div class="auth-branding">
        <div class="auth-branding-content">
            <div class="auth-logo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <h1 class="auth-brand-title">OSCA Panel</h1>
            <p class="auth-brand-subtitle">Office for Senior Citizens Affairs</p>
            <p class="auth-brand-desc">Join the OSCA management system to help administer and support our senior citizens community.</p>
            <div class="auth-features">
                <div class="auth-feature-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Secure Records Management</span>
                </div>
                <div class="auth-feature-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Payroll &amp; Benefits Processing</span>
                </div>
                <div class="auth-feature-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Real-time Reports &amp; Analytics</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Panel: Register Form -->
    <div class="auth-form-panel">
        <div class="auth-form-container">
            <div class="auth-form-header">
                <h2>Create Account</h2>
                <p>Fill in your details to request access</p>
            </div>

            <form wire:submit="register" class="auth-form">
                <!-- Row 1: Full Name & Username -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="reg_name" class="form-label">Full Name <span style="color:red;">*</span></label>
                        <div class="input-with-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <input type="text" id="reg_name" wire:model="name" class="form-input" placeholder="Juan Dela Cruz">
                        </div>
                        @error('name') <span class="field-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="reg_username" class="form-label">Username <span style="color:red;">*</span></label>
                        <div class="input-with-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <input type="text" id="reg_username" wire:model="username" class="form-input" placeholder="jdelacruz">
                        </div>
                        @error('username') <span class="field-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Row 2: Email -->
                <div class="form-group">
                    <label for="reg_email" class="form-label">Email Address <span style="color:red;">*</span></label>
                    <div class="input-with-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <input type="email" id="reg_email" wire:model="email" class="form-input" placeholder="jdelacruz@osca.gov.ph">
                    </div>
                    @error('email') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <!-- Row 3: Office -->
                <div class="form-group">
                    <label for="reg_office_id" class="form-label">Office / Department <span style="color:red;">*</span></label>
                    <div class="input-with-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                        <select id="reg_office_id" wire:model="office_id" class="form-input" style="padding-left: 2.5rem;">
                            <option value="">— Select Your Office —</option>
                            @foreach($offices as $office)
                                <option value="{{ $office->id }}">{{ $office->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('office_id') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <!-- Row 4: Password & Confirm -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="reg_password" class="form-label">Password <span style="color:red;">*</span></label>
                        <div class="input-with-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            <input type="password" id="reg_password" wire:model="password" class="form-input" placeholder="••••••••">
                        </div>
                        @error('password') <span class="field-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="reg_password_confirmation" class="form-label">Confirm Password <span style="color:red;">*</span></label>
                        <div class="input-with-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            <input type="password" id="reg_password_confirmation" wire:model="password_confirmation" class="form-input" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <!-- Notice Banner -->
                <div style="background: #fffbeb; border: 1px solid #f59e0b; border-radius: 0.5rem; padding: 0.75rem 1rem; display: flex; align-items: flex-start; gap: 0.5rem; margin-top: 0.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" style="flex-shrink:0; margin-top:1px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <p style="margin:0; font-size: 0.8rem; color: #92400e; line-height: 1.4;">Your registration will require <strong>administrator approval</strong> before you can log in.</p>
                </div>

                <button type="submit" id="reg_submit_btn" class="btn btn-primary auth-submit-btn" wire:loading.attr="disabled">
                    <span wire:loading.remove>Submit Registration</span>
                    <span wire:loading>Submitting...</span>
                </button>
            </form>

            <div class="auth-footer-link">
                Already have an account? <a href="/login" wire:navigate>Sign in here</a>
            </div>
        </div>
    </div>
</div>
