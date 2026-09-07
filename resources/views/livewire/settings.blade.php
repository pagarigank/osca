<div class="page-container">
    <div class="page-header">
        <h2 class="page-title">Application Settings</h2>
    </div>

    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; box-shadow: var(--shadow-sm); border-left: 4px solid var(--primary-blue-light);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 style="color: var(--primary-blue-dark); margin-bottom: 0.25rem;">Email Notifications</h3>
                    <p style="color: var(--text-secondary); font-size: 0.875rem;">Receive daily activity reports via email.</p>
                </div>
                <button wire:click="toggleNotifications" style="background: {{ $notifications ? 'var(--primary-blue)' : 'var(--border-color)' }}; color: {{ $notifications ? 'white' : 'var(--text-secondary)' }}; border: none; padding: 0.5rem 1rem; border-radius: 0.5rem; cursor: pointer; transition: all 0.3s; font-weight: 500;">
                    {{ $notifications ? 'Enabled' : 'Disabled' }}
                </button>
            </div>
        </div>

        <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; box-shadow: var(--shadow-sm); border-left: 4px solid var(--primary-blue-light);">
            <h3 style="color: var(--primary-blue-dark); margin-bottom: 1rem;">Profile Settings</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div>
                    <label style="display: block; color: var(--text-secondary); margin-bottom: 0.5rem; font-size: 0.875rem;">Name</label>
                    <input type="text" value="{{ auth()->user()->name }}" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.5rem; outline: none; font-family: inherit; background: #f9fafb; color: #6b7280;" disabled>
                </div>
                <div>
                    <label style="display: block; color: var(--text-secondary); margin-bottom: 0.5rem; font-size: 0.875rem;">Username</label>
                    <input type="text" value="{{ auth()->user()->username }}" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.5rem; outline: none; font-family: inherit; background: #f9fafb; color: #6b7280;" disabled>
                </div>
                <div>
                    <label style="display: block; color: var(--text-secondary); margin-bottom: 0.5rem; font-size: 0.875rem;">Email Address</label>
                    <input type="text" value="{{ auth()->user()->email }}" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.5rem; outline: none; font-family: inherit; background: #f9fafb; color: #6b7280;" disabled>
                </div>
                <div>
                    <span style="font-size: 0.8rem; color: var(--text-secondary);">* Contact your system administrator to change your profile details.</span>
                </div>
            </div>
        </div>

        @if(auth()->user()->accessLevel && auth()->user()->accessLevel->name !== 'Admin')
        <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; box-shadow: var(--shadow-sm); border-left: 4px solid var(--primary-blue-light);">
            <h3 style="color: var(--primary-blue-dark); margin-bottom: 1rem;">Change Password</h3>
            <form wire:submit="updatePassword" style="display: flex; flex-direction: column; gap: 1rem;">
                <div>
                    <label style="display: block; color: var(--text-secondary); margin-bottom: 0.5rem; font-size: 0.875rem;">Current Password</label>
                    <input type="password" wire:model="current_password" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.5rem; outline: none; font-family: inherit;" required>
                    @error('current_password') <span style="color: red; font-size: 0.75rem; display: block; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label style="display: block; color: var(--text-secondary); margin-bottom: 0.5rem; font-size: 0.875rem;">New Password</label>
                    <input type="password" wire:model="new_password" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.5rem; outline: none; font-family: inherit;" required>
                    @error('new_password') <span style="color: red; font-size: 0.75rem; display: block; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label style="display: block; color: var(--text-secondary); margin-bottom: 0.5rem; font-size: 0.875rem;">Confirm New Password</label>
                    <input type="password" wire:model="new_password_confirmation" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.5rem; outline: none; font-family: inherit;" required>
                </div>
                <div>
                    <button type="submit" style="background: var(--primary-blue-dark); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 0.5rem; cursor: pointer; transition: all 0.3s; font-weight: 600;">Update Password</button>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>
