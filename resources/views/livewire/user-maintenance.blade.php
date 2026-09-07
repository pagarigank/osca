<div>
    <x-slot:title>User Maintenance | OSCA Panel</x-slot:title>

    <div class="page-container">
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="page-title">User Management</h2>
            <button wire:click="openModal" class="btn btn-primary">Add New User</button>
        </div>

        {{-- ── Pending Approvals Section ─────────────────────────── --}}
        @php
            $pendingUsers = $users->where('approval_status', 'PENDING');
        @endphp

        @if($pendingUsers->count() > 0)
        <div style="background: #fffbeb; border: 1px solid #fcd34d; border-radius: 0.875rem; margin-bottom: 1.5rem; overflow: hidden;">
            <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #fcd34d; display: flex; align-items: center; gap: 0.625rem;">
                <div style="width: 10px; height: 10px; background: #f59e0b; border-radius: 50%; animation: pulse-dot 2s infinite;"></div>
                <h3 style="margin: 0; font-size: 0.9rem; font-weight: 700; color: #92400e;">
                    Pending Approvals
                    <span style="background: #f59e0b; color: white; font-size: 0.7rem; font-weight: 700; padding: 0.15rem 0.55rem; border-radius: 999px; margin-left: 0.35rem;">{{ $pendingUsers->count() }}</span>
                </h3>
            </div>
            <div style="overflow-x: auto;">
                <table class="data-table" style="font-size: 0.875rem; background: transparent;">
                    <thead style="background: #fef3c7;">
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Office Requested</th>
                            <th>Registered At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingUsers as $user)
                        <tr class="table-row">
                            <td style="font-weight: 600; color: #1e3a5f;">{{ $user->name }}</td>
                            <td style="color: var(--text-secondary); font-size: 0.825rem;">{{ $user->username ?? '—' }}</td>
                            <td style="font-size: 0.825rem;">{{ $user->email }}</td>
                            <td>
                                @if($user->office)
                                    <span style="background: #eff6ff; color: #1d4ed8; font-size: 0.78rem; font-weight: 600; padding: 0.2rem 0.6rem; border-radius: 0.375rem;">{{ $user->office->name }}</span>
                                @else
                                    <span style="color: var(--text-secondary);">—</span>
                                @endif
                            </td>
                            <td style="color: var(--text-secondary); font-size: 0.8rem;">{{ $user->created_at->format('M d, Y h:i A') }}</td>
                            <td style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <button wire:click="approve({{ $user->id }})"
                                        wire:confirm="Approve '{{ $user->name }}'? They will be able to log in immediately."
                                        class="btn btn-sm"
                                        style="background: #22c55e; color: white; border: none; display: inline-flex; align-items: center; gap: 0.3rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    Approve
                                </button>
                                <button wire:click="reject({{ $user->id }})"
                                        wire:confirm="Reject '{{ $user->name }}'? They will not be able to log in."
                                        class="btn btn-sm btn-danger"
                                        style="display: inline-flex; align-items: center; gap: 0.3rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    Reject
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- ── All Users Table ───────────────────────────────────── --}}
        <div style="background: white; border-radius: 0.875rem; box-shadow: var(--shadow-md); overflow: hidden;">
            <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9;">
                <h3 style="margin: 0; font-size: 0.9rem; font-weight: 700; color: #1e3a5f;">All Users</h3>
            </div>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Office</th>
                            <th>Access Level</th>
                            <th>Status</th>
                            <th>Approval</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr class="table-row">
                            <td style="font-weight: 600;">{{ $user->name }}</td>
                            <td style="color: var(--text-secondary); font-size: 0.875rem;">{{ $user->username ?? '—' }}</td>
                            <td style="font-size: 0.875rem;">{{ $user->email }}</td>
                            <td>{{ $user->office?->name ?? '—' }}</td>
                            <td>{{ $user->accessLevel?->name ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $user->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusStyles = [
                                        'PENDING'  => ['bg' => '#fef3c7', 'color' => '#92400e', 'border' => '#fcd34d'],
                                        'APPROVED' => ['bg' => '#f0fdf4', 'color' => '#166534', 'border' => '#bbf7d0'],
                                        'REJECTED' => ['bg' => '#fef2f2', 'color' => '#991b1b', 'border' => '#fecaca'],
                                    ];
                                    $st = $statusStyles[$user->approval_status ?? 'APPROVED'] ?? $statusStyles['APPROVED'];
                                @endphp
                                <span style="background: {{ $st['bg'] }}; color: {{ $st['color'] }}; border: 1px solid {{ $st['border'] }}; font-size: 0.72rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 999px; white-space: nowrap;">
                                    {{ $user->approval_status ?? 'APPROVED' }}
                                </span>
                            </td>
                            <td style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                                <button wire:click="edit({{ $user->id }})" class="btn btn-sm btn-info">Edit</button>
                                @if(($user->approval_status ?? 'APPROVED') === 'PENDING')
                                    <button wire:click="approve({{ $user->id }})" wire:confirm="Approve this user?" class="btn btn-sm" style="background: #22c55e; color: white; border: none;">Approve</button>
                                    <button wire:click="reject({{ $user->id }})"  wire:confirm="Reject this user?"  class="btn btn-sm btn-danger">Reject</button>
                                @elseif(($user->approval_status ?? 'APPROVED') === 'REJECTED')
                                    <button wire:click="approve({{ $user->id }})" wire:confirm="Approve this user?" class="btn btn-sm" style="background: #22c55e; color: white; border: none;">Approve</button>
                                @else
                                    <button wire:click="delete({{ $user->id }})" wire:confirm="Are you sure you want to deactivate this user?" class="btn btn-sm btn-danger">Deactivate</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 1.5rem; color: var(--text-secondary);">No users found. Click 'Add New User' to create one.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ── Add/Edit Modal ────────────────────────────────────────── --}}
    @if($isModalOpen)
    <div class="modal-backdrop">
        <div class="modal-box" style="max-width: 640px;">
            <div class="modal-header">
                <h3>{{ $isEditMode ? 'Edit User' : 'Add New User' }}</h3>
                <button wire:click="closeModal" class="close-btn">&times;</button>
            </div>
            <div class="modal-body">

                <!-- Row 1: Name & Username -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="um_name" class="form-label">Full Name <span style="color:red;">*</span></label>
                        <input type="text" id="um_name" wire:model="name" class="form-input" placeholder="e.g. Juan Dela Cruz">
                        @error('name') <span style="font-size: 0.8rem; color: red;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="um_username" class="form-label">Username <span style="color:red;">*</span></label>
                        <input type="text" id="um_username" wire:model="username" class="form-input" placeholder="e.g. jdelacruz">
                        @error('username') <span style="font-size: 0.8rem; color: red;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Row 2: Email -->
                <div class="form-group">
                    <label for="um_email" class="form-label">Email Address <span style="color:red;">*</span></label>
                    <input type="email" id="um_email" wire:model="email" class="form-input" placeholder="e.g. jdelacruz@osca.gov.ph">
                    @error('email') <span style="font-size: 0.8rem; color: red;">{{ $message }}</span> @enderror
                </div>

                <!-- Row 3: Password & Confirm -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="um_password" class="form-label">
                            Password {{ $isEditMode ? '(leave blank to keep current)' : '' }} {!! !$isEditMode ? '<span style="color:red;">*</span>' : '' !!}
                        </label>
                        <input type="password" id="um_password" wire:model="password" class="form-input" placeholder="••••••••">
                        @error('password') <span style="font-size: 0.8rem; color: red;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="um_password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" id="um_password_confirmation" wire:model="password_confirmation" class="form-input" placeholder="••••••••">
                    </div>
                </div>

                <!-- Row 4: Office & Access Level -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="um_office_id" class="form-label">Office</label>
                        <select id="um_office_id" wire:model="office_id" class="form-input">
                            <option value="">— Select Office —</option>
                            @foreach($offices as $office)
                                <option value="{{ $office->id }}">{{ $office->name }}</option>
                            @endforeach
                        </select>
                        @error('office_id') <span style="font-size: 0.8rem; color: red;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="um_access_level_id" class="form-label">Access Level</label>
                        <select id="um_access_level_id" wire:model="access_level_id" class="form-input">
                            <option value="">— Select Access Level —</option>
                            @foreach($access_levels as $level)
                                <option value="{{ $level->id }}">{{ $level->name }}</option>
                            @endforeach
                        </select>
                        @error('access_level_id') <span style="font-size: 0.8rem; color: red;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Row 5: Active Status -->
                <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
                    <input type="checkbox" id="um_is_active" wire:model="is_active">
                    <label for="um_is_active" style="margin: 0;">Active</label>
                </div>

            </div>
            <div class="modal-footer">
                <button wire:click="closeModal" class="btn btn-secondary">Cancel</button>
                <button wire:click="store" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
    @endif

    <style>
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.35; }
        }
    </style>
</div>
