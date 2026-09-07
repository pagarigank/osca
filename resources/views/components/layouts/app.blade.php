<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'OSCA Application' }}</title>

        <!-- Google Fonts: Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="theme-light-blue">
        <div class="app-container">
            <!-- Header -->
            <header class="app-header">
                <div class="logo">
                    <h1>OSCA Panel</h1>
                </div>
                <div class="user-profile">
                    <span>Admin User</span>
                    <div class="avatar">A</div>
                </div>
            </header>

            <!-- Top Menu -->
            <nav class="app-top-menu">
                <ul class="nav-menu">
                    <li>
                        <a href="/" wire:navigate class="{{ request()->is('/') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="/transactions" wire:navigate class="{{ request()->is('transactions') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                            Transactions
                        </a>
                    </li>
                    <li>
                        <a href="/payroll" wire:navigate class="{{ request()->is('payroll') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                            Payroll
                        </a>
                    </li>
                    <li>
                        <a href="/reports" wire:navigate class="{{ request()->is('reports') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                            Reports
                        </a>
                    </li>
                    <li>
                        <a href="/messages" wire:navigate class="{{ request()->is('messages') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            Messages
                        </a>
                    </li>
                    <li>
                        <a href="#" class="{{ request()->is('payroll-provider', 'funding', 'location', 'quad1-parameter', 'quad2-parameter', 'quad3-parameter', 'quad4-parameter', 'diagnosis', 'pension-source', 'regular-income', 'other-income', 'place-of-living', 'living-condition', 'barangay', 'civil-status', 'gender', 'nationality', 'office', 'position', 'access-level', 'users') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                            Master/Library Maintenance
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px;"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/payroll-provider" wire:navigate>Payroll Provider</a></li>
                            <li><a href="/funding" wire:navigate>Funding</a></li>
                            <li><a href="/location" wire:navigate>Location</a></li>
                            <li><a href="/quad1-parameter" wire:navigate>Quad1 Parameter</a></li>
                            <li><a href="/quad2-parameter" wire:navigate>Quad2 Parameter</a></li>
                            <li><a href="/quad3-parameter" wire:navigate>Quad3 Parameter</a></li>
                            <li><a href="/quad4-parameter" wire:navigate>Quad4 Parameter</a></li>
                            <li><a href="/diagnosis" wire:navigate>Diagnosis (ICD10-CM)</a></li>
                            <li><a href="/pension-source" wire:navigate>Pension Source</a></li>
                            <li><a href="/regular-income" wire:navigate>Regular Income</a></li>
                            <li><a href="/other-income" wire:navigate>Other Income</a></li>
                            <li><a href="/place-of-living" wire:navigate>Place of Living</a></li>
                            <li><a href="/living-condition" wire:navigate>Living Condition</a></li>
                            <li><a href="/barangay" wire:navigate>Barangay</a></li>
                            <li><a href="/civil-status" wire:navigate>Civil Status</a></li>
                            <li><a href="/gender" wire:navigate>Gender</a></li>
                            <li><a href="/nationality" wire:navigate>Nationality</a></li>
                            <li><a href="/office" wire:navigate>Office</a></li>
                            <li><a href="/position" wire:navigate>Position</a></li>
                            <li><a href="/access-level" wire:navigate>Access Level</a></li>
                            <li><a href="/users" wire:navigate>Users</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="/settings" wire:navigate class="{{ request()->is('settings') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                            Settings
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="app-body">
                <!-- Main Content -->
                <main class="app-main">
                    <!-- Global Toast Notification -->
                    <div x-data="{ show: false, message: '' }" 
                         x-on:notify.window="message = $event.detail.message; show = true; setTimeout(() => show = false, 3000)"
                         class="toast-notification"
                         x-show="show"
                         x-transition:enter="toast-enter"
                         x-transition:enter-start="toast-enter-start"
                         x-transition:enter-end="toast-enter-end"
                         x-transition:leave="toast-leave"
                         x-transition:leave-start="toast-leave-start"
                         x-transition:leave-end="toast-leave-end"
                         style="display: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #10b981;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span x-text="message"></span>
                    </div>

                    {{ $slot }}
                </main>
            </div>

            <!-- Footer -->
            <footer class="app-footer">
                <p>&copy; {{ date('Y') }} OSCA Web Application. All rights reserved.</p>
            </footer>
        </div>

        @livewireScripts
    </body>
</html>