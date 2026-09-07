<div>
    <x-slot:title>Dashboard | OSCA Panel</x-slot:title>

    <div class="page-container">
        <div class="page-header">
            <h2 class="page-title">Dashboard Overview</h2>
        </div>

        {{-- ── Stat Cards ──────────────────────────────────────────── --}}
        <div class="stat-cards" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 1.75rem;">

            {{-- Total Senior Citizens --}}
            <div class="stat-card" style="background: white; border-radius: 0.875rem; padding: 1.5rem; box-shadow: var(--shadow-md); border-left: 4px solid #3b82f6; display: flex; align-items: center; gap: 1rem;">
                <div style="background: #eff6ff; border-radius: 0.625rem; padding: 0.875rem; flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="1.75"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <div>
                    <div style="font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Total Senior Citizens</div>
                    <div style="font-size: 2rem; font-weight: 700; color: #1e3a5f; line-height: 1.2; margin-top: 0.25rem;">{{ number_format($totalSeniorCitizens) }}</div>
                </div>
            </div>

            {{-- Active Senior Citizens --}}
            <div class="stat-card" style="background: white; border-radius: 0.875rem; padding: 1.5rem; box-shadow: var(--shadow-md); border-left: 4px solid #22c55e; display: flex; align-items: center; gap: 1rem;">
                <div style="background: #f0fdf4; border-radius: 0.625rem; padding: 0.875rem; flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="1.75"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                <div>
                    <div style="font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Active Senior Citizens</div>
                    <div style="font-size: 2rem; font-weight: 700; color: #1e3a5f; line-height: 1.2; margin-top: 0.25rem;">{{ number_format($activeSeniorCitizens) }}</div>
                </div>
            </div>

            {{-- Pending User Registrations --}}
            <div class="stat-card" style="background: white; border-radius: 0.875rem; padding: 1.5rem; box-shadow: var(--shadow-md); border-left: 4px solid {{ $pendingCount > 0 ? '#f59e0b' : '#94a3b8' }}; display: flex; align-items: center; gap: 1rem;">
                <div style="background: {{ $pendingCount > 0 ? '#fffbeb' : '#f8fafc' }}; border-radius: 0.625rem; padding: 0.875rem; flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="{{ $pendingCount > 0 ? '#f59e0b' : '#94a3b8' }}" stroke-width="1.75"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
                </div>
                <div style="flex: 1;">
                    <div style="font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Pending Registrations</div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.25rem;">
                        <span style="font-size: 2rem; font-weight: 700; color: #1e3a5f; line-height: 1.2;">{{ $pendingCount }}</span>
                        @if($pendingCount > 0)
                        <span style="background: #fef3c7; color: #92400e; font-size: 0.7rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 999px; border: 1px solid #fcd34d;">NEEDS ACTION</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Pending Support Messages --}}
            <div class="stat-card" style="background: white; border-radius: 0.875rem; padding: 1.5rem; box-shadow: var(--shadow-md); border-left: 4px solid {{ $unreadMessagesCount > 0 ? '#ef4444' : '#9ca3af' }}; display: flex; align-items: center; gap: 1rem;">
                <div style="background: {{ $unreadMessagesCount > 0 ? '#fef2f2' : '#f3f4f6' }}; border-radius: 0.625rem; padding: 0.875rem; flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="{{ $unreadMessagesCount > 0 ? '#ef4444' : '#9ca3af' }}" stroke-width="1.75"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                </div>
                <div style="flex: 1;">
                    <div style="font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Pending Support Chats</div>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 0.25rem;">
                        <span style="font-size: 2rem; font-weight: 700; color: #1e3a5f; line-height: 1.2;">{{ $unreadMessagesCount }}</span>
                        <a href="/messages" wire:navigate style="font-size: 0.75rem; background: {{ $unreadMessagesCount > 0 ? '#ef4444' : '#e5e7eb' }}; color: {{ $unreadMessagesCount > 0 ? 'white' : '#6b7280' }}; padding: 0.25rem 0.5rem; border-radius: 0.25rem; text-decoration: none; font-weight: 600;">Open Chat</a>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── Pending Registrations Panel ────────────────────────── --}}
        @if($pendingCount > 0)
        <div style="background: white; border-radius: 0.875rem; box-shadow: var(--shadow-md); margin-bottom: 1.75rem; overflow: hidden;">
            {{-- Panel Header --}}
            <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; background: linear-gradient(135deg, #fffbeb 0%, #fef9ee 100%);">
                <div style="display: flex; align-items: center; gap: 0.625rem;">
                    <div style="width: 10px; height: 10px; background: #f59e0b; border-radius: 50%; animation: pulse 2s infinite;"></div>
                    <h3 style="margin: 0; font-size: 0.975rem; font-weight: 700; color: #92400e;">
                        Pending User Registrations
                        <span style="background: #f59e0b; color: white; font-size: 0.72rem; font-weight: 700; padding: 0.15rem 0.55rem; border-radius: 999px; margin-left: 0.4rem;">{{ $pendingCount }}</span>
                    </h3>
                </div>
                <a href="/users" wire:navigate
                   style="display: inline-flex; align-items: center; gap: 0.375rem; background: #1e3a5f; color: white; font-size: 0.8rem; font-weight: 600; padding: 0.5rem 1rem; border-radius: 0.5rem; text-decoration: none; transition: background 0.2s;"
                   onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#1e3a5f'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    Manage Users
                </a>
            </div>

            {{-- Pending Users Table --}}
            <div style="overflow-x: auto;">
                <table class="data-table" style="font-size: 0.875rem;">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Office Requested</th>
                            <th>Registered At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingUsers as $pending)
                        <tr class="table-row">
                            <td style="font-weight: 600; color: #1e3a5f;">{{ $pending->name }}</td>
                            <td style="color: var(--text-secondary);">{{ $pending->username ?? '—' }}</td>
                            <td style="color: var(--text-secondary);">{{ $pending->email }}</td>
                            <td>
                                @if($pending->office)
                                    <span style="background: #eff6ff; color: #1d4ed8; font-size: 0.78rem; font-weight: 600; padding: 0.2rem 0.6rem; border-radius: 0.375rem;">{{ $pending->office->name }}</span>
                                @else
                                    <span style="color: var(--text-secondary);">—</span>
                                @endif
                            </td>
                            <td style="color: var(--text-secondary); font-size: 0.8rem;">{{ $pending->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <a href="/users" wire:navigate
                                   style="display: inline-flex; align-items: center; gap: 0.25rem; background: #f0fdf4; color: #16a34a; font-size: 0.78rem; font-weight: 600; padding: 0.3rem 0.75rem; border-radius: 0.375rem; text-decoration: none; border: 1px solid #bbf7d0;">
                                    Review →
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        {{-- No Pending — Clean State --}}
        <div style="background: white; border-radius: 0.875rem; box-shadow: var(--shadow-md); padding: 2rem 1.5rem; text-align: center; margin-bottom: 1.75rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="1.5" style="margin: 0 auto 0.75rem;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <p style="margin: 0; color: var(--text-secondary); font-size: 0.9rem;">No pending user registrations. All accounts are up to date.</p>
        </div>
        @endif

        {{-- ── Quick Links ─────────────────────────────────────────── --}}
        <div style="background: white; border-radius: 0.875rem; box-shadow: var(--shadow-md); padding: 1.5rem;">
            <h3 style="color: #1e3a5f; font-size: 0.975rem; font-weight: 700; margin-bottom: 1rem;">Quick Links</h3>
            <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
                <a href="/personal-information" wire:navigate
                   style="display: inline-flex; align-items: center; gap: 0.5rem; background: #eff6ff; color: #1d4ed8; font-size: 0.85rem; font-weight: 600; padding: 0.625rem 1.125rem; border-radius: 0.5rem; text-decoration: none; border: 1px solid #bfdbfe; transition: background 0.2s;"
                   onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    Personal Information
                </a>
                <a href="/users" wire:navigate
                   style="display: inline-flex; align-items: center; gap: 0.5rem; background: #f0fdf4; color: #16a34a; font-size: 0.85rem; font-weight: 600; padding: 0.625rem 1.125rem; border-radius: 0.5rem; text-decoration: none; border: 1px solid #bbf7d0; transition: background 0.2s;"
                   onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fdf4'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    User Management
                    @if($pendingCount > 0)
                    <span style="background: #f59e0b; color: white; font-size: 0.65rem; font-weight: 700; padding: 0.1rem 0.45rem; border-radius: 999px;">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="/barangay" wire:navigate
                   style="display: inline-flex; align-items: center; gap: 0.5rem; background: #faf5ff; color: #7c3aed; font-size: 0.85rem; font-weight: 600; padding: 0.625rem 1.125rem; border-radius: 0.5rem; text-decoration: none; border: 1px solid #ddd6fe; transition: background 0.2s;"
                   onmouseover="this.style.background='#ede9fe'" onmouseout="this.style.background='#faf5ff'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    Barangay Maintenance
                </a>
            </div>
        </div>

    </div>

    <style>
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.4; }
        }
    </style>
</div>
