<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; background: var(--bg);">
    <div style="width: 100%; max-width: 560px; margin: 0 auto;">

        {{-- ── OSCA Header ─────────────────────────────────────── --}}
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="display: inline-flex; align-items: center; justify-content: center; width: 72px; height: 72px; background: linear-gradient(135deg, var(--primary), var(--primary-mid)); border-radius: 1.25rem; margin-bottom: 1rem; box-shadow: var(--shadow-lg);">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin: 0; letter-spacing: -0.02em;">Office for Senior Citizens Affairs</h1>
            <p style="font-size: 0.9rem; color: var(--text-secondary); margin: 0.25rem 0 0;">Official Senior Citizen ID Verification</p>
        </div>

        @if(!$found)
        {{-- ── Not Found ─────────────────────────────────────── --}}
        <div style="background: var(--surface); border-radius: var(--radius-xl); box-shadow: var(--shadow-lg); padding: 3rem 2rem; text-align: center; border: 1px solid var(--border);">
            <div style="width: 80px; height: 80px; background: #fef2f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 4px solid #fee2e2;">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="1.75"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
            </div>
            <h2 style="color: var(--danger); font-size: 1.5rem; font-weight: 800; margin: 0 0 0.75rem; letter-spacing: -0.02em;">Invalid QR Code</h2>
            <p style="color: var(--text-secondary); font-size: 0.95rem; line-height: 1.6; margin: 0;">This QR code is not recognized or may have been tampered with. Please contact the OSCA office directly for assistance.</p>
        </div>

        @else
        {{-- ── Status Banner ─────────────────────────────────── --}}
        @php
            $isActive = $sc->is_active && $sc->status === 'ACTIVE';
            $age = \Carbon\Carbon::parse($sc->date_of_birth)->age;
        @endphp

        <div style="border-radius: var(--radius-lg); padding: 1rem 1.5rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 1rem;
                    background: {{ $isActive ? '#f0fdf4' : '#fef2f2' }};
                    border: 1px solid {{ $isActive ? '#bbf7d0' : '#fecaca' }};
                    box-shadow: var(--shadow-sm);">
            <div style="flex-shrink: 0; width: 48px; height: 48px; border-radius: 50%; background: {{ $isActive ? '#dcfce7' : '#fee2e2' }}; display: flex; align-items: center; justify-content: center;">
                @if($isActive)
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                @else
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                @endif
            </div>
            <div>
                <div style="font-size: 1.1rem; font-weight: 800; color: {{ $isActive ? '#15803d' : '#b91c1c' }}; letter-spacing: -0.01em;">
                    {{ $isActive ? 'Verified Active OSCA Member' : 'Inactive / Record Flagged' }}
                </div>
                <div style="font-size: 0.85rem; color: {{ $isActive ? '#16a34a' : '#ef4444' }}; margin-top: 0.2rem; font-weight: 500;">
                    {{ $isActive ? 'This individual is a registered and active senior citizen.' : 'This record is currently inactive. Please verify with the OSCA office.' }}
                </div>
            </div>
        </div>

        {{-- ── Main Card ─────────────────────────────────────── --}}
        <div style="background: var(--surface); border-radius: var(--radius-xl); box-shadow: var(--shadow-lg); overflow: hidden; border: 1px solid var(--border);">

            {{-- Card Header --}}
            <div style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-mid) 100%); padding: 2rem; display: flex; align-items: center; gap: 1.5rem;">
                {{-- Photo placeholder --}}
                <div style="width: 80px; height: 80px; border-radius: var(--radius-md); background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.3); display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: var(--shadow-md);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.8)" stroke-width="1.25"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: white; line-height: 1.2; letter-spacing: -0.02em;">
                        {{ strtoupper($sc->last_name) }}, {{ $sc->first_name }} {{ $sc->middle_name ? strtoupper(substr($sc->middle_name,0,1)).'.' : '' }}
                        @if($sc->suffix) <span style="font-size: 1rem; font-weight: 600;">{{ $sc->suffix }}</span> @endif
                    </div>
                    <div style="margin-top: 0.5rem; display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                        <span style="background: rgba(255,255,255,0.2); color: white; font-size: 0.8rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: var(--radius-full); letter-spacing: 0.05em; backdrop-filter: blur(4px);">
                            ID: {{ $sc->id_number ?? 'N/A' }}
                        </span>
                        <span style="background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.9); font-size: 0.8rem; padding: 0.25rem 0.75rem; border-radius: var(--radius-full); font-weight: 500;">
                            Age {{ $age }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Info Grid --}}
            <div style="padding: 2rem;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">

                    <div>
                        <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.35rem;">Date of Birth</div>
                        <div style="font-size: 0.95rem; font-weight: 600; color: var(--text-primary);">
                            {{ $sc->date_of_birth ? $sc->date_of_birth->format('F d, Y') : '—' }}
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.35rem;">Gender</div>
                        <div style="font-size: 0.95rem; font-weight: 600; color: var(--text-primary);">
                            {{ $sc->gender?->name ?? '—' }}
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.35rem;">Civil Status</div>
                        <div style="font-size: 0.95rem; font-weight: 600; color: var(--text-primary);">
                            {{ $sc->civilStatus?->name ?? '—' }}
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.35rem;">Barangay</div>
                        <div style="font-size: 0.95rem; font-weight: 600; color: var(--text-primary);">
                            {{ $sc->barangay?->name ?? $sc->city_municipality ?? '—' }}
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.35rem;">City / Municipality</div>
                        <div style="font-size: 0.95rem; font-weight: 600; color: var(--text-primary);">
                            {{ $sc->city_municipality ?? '—' }}
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.35rem;">Registration Status</div>
                        <div>
                            <span class="badge {{ $isActive ? 'badge-active' : 'badge-inactive' }}">
                                {{ $sc->status ?? 'ACTIVE' }}
                            </span>
                        </div>
                    </div>

                </div>

                {{-- Effective Date --}}
                @if($sc->effective_date)
                <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
                    <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.35rem;">OSCA Member Since</div>
                    <div style="font-size: 0.95rem; font-weight: 600; color: var(--text-primary);">{{ $sc->effective_date->format('F d, Y') }}</div>
                </div>
                @endif

                {{-- NCSC RRN --}}
                @if($sc->ncsc_rrn)
                <div style="margin-top: 1rem;">
                    <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.35rem;">NCSC Reference No.</div>
                    <div style="font-size: 0.95rem; font-weight: 600; color: var(--text-primary); font-family: monospace;">{{ $sc->ncsc_rrn }}</div>
                </div>
                @endif
            </div>

            {{-- Footer Disclaimer --}}
            <div style="padding: 1.25rem 2rem; background: #f8fafc; border-top: 1px solid var(--border); text-align: center;">
                <p style="margin: 0; font-size: 0.8rem; color: var(--text-secondary); line-height: 1.5;">
                    This information is for <strong>verification purposes only</strong> and is provided by the OSCA system. Verified on {{ now()->format('M d, Y h:i A') }}.
                </p>
            </div>
        </div>

        {{-- Scan Again Note --}}
        <div style="text-align: center; margin-top: 1.5rem;">
            <p style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500; margin: 0; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                Secure verification powered by OSCA Management System
            </p>
        </div>
        @endif

    </div>
</div>
