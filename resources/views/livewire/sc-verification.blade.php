<div style="width: 100%; max-width: 560px; margin: 0 auto;">

    {{-- ── OSCA Header ─────────────────────────────────────── --}}
    <div style="text-align: center; margin-bottom: 1.5rem;">
        <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; background: linear-gradient(135deg, #1e3a5f, #2563eb); border-radius: 1rem; margin-bottom: 0.75rem; box-shadow: 0 4px 15px rgba(30,58,95,0.3);">
            <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <h1 style="font-size: 1.25rem; font-weight: 800; color: #1e3a5f; margin: 0;">Office for Senior Citizens Affairs</h1>
        <p style="font-size: 0.8rem; color: #64748b; margin: 0.25rem 0 0;">Official Senior Citizen ID Verification</p>
    </div>

    @if(!$found)
    {{-- ── Not Found ─────────────────────────────────────── --}}
    <div style="background: white; border-radius: 1.25rem; box-shadow: 0 8px 32px rgba(0,0,0,0.1); padding: 2.5rem; text-align: center;">
        <div style="width: 72px; height: 72px; background: #fef2f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="1.75"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
        </div>
        <h2 style="color: #dc2626; font-size: 1.25rem; font-weight: 700; margin: 0 0 0.5rem;">Invalid QR Code</h2>
        <p style="color: #64748b; font-size: 0.9rem; margin: 0;">This QR code is not recognized or may have been tampered with. Please contact the OSCA office directly.</p>
    </div>

    @else
    {{-- ── Status Banner ─────────────────────────────────── --}}
    @php
        $isActive = $sc->is_active && $sc->status === 'ACTIVE';
        $age = \Carbon\Carbon::parse($sc->date_of_birth)->age;
    @endphp

    <div style="border-radius: 0.875rem; padding: 0.875rem 1.25rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem;
                background: {{ $isActive ? 'linear-gradient(135deg, #f0fdf4, #dcfce7)' : 'linear-gradient(135deg, #fef2f2, #fee2e2)' }};
                border: 2px solid {{ $isActive ? '#22c55e' : '#ef4444' }};">
        <div style="flex-shrink: 0;">
            @if($isActive)
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            @else
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            @endif
        </div>
        <div>
            <div style="font-size: 1rem; font-weight: 800; color: {{ $isActive ? '#15803d' : '#dc2626' }};">
                {{ $isActive ? '✓ VERIFIED ACTIVE OSCA MEMBER' : '⚠ INACTIVE / RECORD FLAGGED' }}
            </div>
            <div style="font-size: 0.78rem; color: {{ $isActive ? '#16a34a' : '#ef4444' }}; margin-top: 0.1rem;">
                {{ $isActive ? 'This individual is a registered and active senior citizen.' : 'This record is currently inactive. Please verify with the OSCA office.' }}
            </div>
        </div>
    </div>

    {{-- ── Main Card ─────────────────────────────────────── --}}
    <div style="background: white; border-radius: 1.25rem; box-shadow: 0 8px 32px rgba(0,0,0,0.1); overflow: hidden;">

        {{-- Card Header --}}
        <div style="background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); padding: 1.5rem 1.75rem; display: flex; align-items: center; gap: 1.25rem;">
            {{-- Photo placeholder --}}
            <div style="width: 72px; height: 72px; border-radius: 0.75rem; background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.4); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.7)" stroke-width="1.25"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </div>
            <div>
                <div style="font-size: 1.3rem; font-weight: 800; color: white; line-height: 1.2;">
                    {{ strtoupper($sc->last_name) }}, {{ $sc->first_name }} {{ $sc->middle_name ? strtoupper(substr($sc->middle_name,0,1)).'.' : '' }}
                    @if($sc->suffix) <span style="font-size: 0.9rem; font-weight: 600;">{{ $sc->suffix }}</span> @endif
                </div>
                <div style="margin-top: 0.4rem; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                    <span style="background: rgba(255,255,255,0.2); color: white; font-size: 0.78rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 999px; letter-spacing: 0.05em;">
                        ID: {{ $sc->id_number ?? 'N/A' }}
                    </span>
                    <span style="background: rgba(255,255,255,0.15); color: rgba(255,255,255,0.9); font-size: 0.78rem; padding: 0.2rem 0.6rem; border-radius: 999px;">
                        Age {{ $age }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Info Grid --}}
        <div style="padding: 1.5rem 1.75rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">

                <div>
                    <div style="font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.2rem;">Date of Birth</div>
                    <div style="font-size: 0.925rem; font-weight: 600; color: #1e3a5f;">
                        {{ $sc->date_of_birth ? $sc->date_of_birth->format('F d, Y') : '—' }}
                    </div>
                </div>

                <div>
                    <div style="font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.2rem;">Gender</div>
                    <div style="font-size: 0.925rem; font-weight: 600; color: #1e3a5f;">
                        {{ $sc->gender?->name ?? '—' }}
                    </div>
                </div>

                <div>
                    <div style="font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.2rem;">Civil Status</div>
                    <div style="font-size: 0.925rem; font-weight: 600; color: #1e3a5f;">
                        {{ $sc->civilStatus?->name ?? '—' }}
                    </div>
                </div>

                <div>
                    <div style="font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.2rem;">Barangay</div>
                    <div style="font-size: 0.925rem; font-weight: 600; color: #1e3a5f;">
                        {{ $sc->barangay?->name ?? $sc->city_municipality ?? '—' }}
                    </div>
                </div>

                <div>
                    <div style="font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.2rem;">City / Municipality</div>
                    <div style="font-size: 0.925rem; font-weight: 600; color: #1e3a5f;">
                        {{ $sc->city_municipality ?? '—' }}
                    </div>
                </div>

                <div>
                    <div style="font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.2rem;">Registration Status</div>
                    <div>
                        <span style="background: {{ $isActive ? '#f0fdf4' : '#fef2f2' }}; color: {{ $isActive ? '#166534' : '#991b1b' }}; border: 1px solid {{ $isActive ? '#bbf7d0' : '#fecaca' }}; font-size: 0.78rem; font-weight: 700; padding: 0.2rem 0.7rem; border-radius: 999px;">
                            {{ $sc->status ?? 'ACTIVE' }}
                        </span>
                    </div>
                </div>

            </div>

            {{-- Effective Date --}}
            @if($sc->effective_date)
            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #f1f5f9;">
                <div style="font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.2rem;">OSCA Member Since</div>
                <div style="font-size: 0.925rem; font-weight: 600; color: #1e3a5f;">{{ $sc->effective_date->format('F d, Y') }}</div>
            </div>
            @endif

            {{-- NCSC RRN --}}
            @if($sc->ncsc_rrn)
            <div style="margin-top: 0.75rem;">
                <div style="font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.2rem;">NCSC Reference No.</div>
                <div style="font-size: 0.925rem; font-weight: 600; color: #1e3a5f; font-family: monospace;">{{ $sc->ncsc_rrn }}</div>
            </div>
            @endif
        </div>

        {{-- Footer Disclaimer --}}
        <div style="padding: 1rem 1.75rem; background: #f8fafc; border-top: 1px solid #f1f5f9; text-align: center;">
            <p style="margin: 0; font-size: 0.72rem; color: #94a3b8; line-height: 1.5;">
                This information is for <strong>verification purposes only</strong> and is provided by the OSCA system. Verified on {{ now()->format('M d, Y h:i A') }}.
            </p>
        </div>
    </div>

    {{-- Scan Again Note --}}
    <div style="text-align: center; margin-top: 1.25rem;">
        <p style="font-size: 0.75rem; color: #94a3b8; margin: 0;">
            🔒 Secure verification powered by OSCA Management System
        </p>
    </div>
    @endif

</div>
