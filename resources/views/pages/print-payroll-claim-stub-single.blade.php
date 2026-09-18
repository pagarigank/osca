{{-- 
    Shared partial — rendered inside .stub-page by both:
      - print-payroll-claim-stub.blade.php (single)
      - print-payroll-stubs.blade.php (batch)
    Requires: $beneficiary, $funding (passed by parent)
--}}
<div class="watermark-logo" style="background-image: url('{{ asset('images/mabalacat_seal.png') }}');"></div>

<div class="content-layer">

    {{-- ── HEADER STRIP ── --}}
    <div class="header">
        <img src="{{ asset('images/mabalacat_seal.png') }}" class="header-logo" alt="Mabalacat City Seal">
        <div class="header-text">
            <div class="republic">Office of the Senior Citizens Affairs</div>
            <div class="city-name">Mabalacat City, Pampanga</div>
            <div class="stub-type-label">PAYROLL CLAIM STUB</div>
        </div>
        <img src="{{ asset('images/osca_logo.png') }}" class="header-logo" alt="OSCA Logo">
    </div>

    {{-- ── LOCATION META ROW ── --}}
    <div class="meta-row">
        <div class="meta-cell">
            <span class="meta-label">Barangay</span>
            <span class="meta-val">{{ $beneficiary->seniorCitizen->barangay->name ?? 'N/A' }}</span>
        </div>
        <div class="meta-cell">
            <span class="meta-label">Purok</span>
            <span class="meta-val">{{ $beneficiary->seniorCitizen->purok_name ?? 'N/A' }}</span>
        </div>
        <div class="meta-cell">
            <span class="meta-label">Fund Source</span>
            <span class="meta-val">{{ $funding->fund_source ?? 'N/A' }}</span>
        </div>
        <div class="meta-cell">
            <span class="meta-label">Run Date</span>
            <span class="meta-val">{{ now()->format('m/d/Y') }}</span>
        </div>
    </div>

    {{-- ── BODY ── --}}
    <div class="body">

        {{-- LEFT: Photo → Signature → QR --}}
        <div class="left">
            <div class="photo-box">
                @if($beneficiary->seniorCitizen->id_picture)
                    <img src="{{ Storage::url($beneficiary->seniorCitizen->id_picture) }}" alt="Photo">
                @else
                    <div class="photo-placeholder">📷<br>Photo</div>
                @endif
            </div>
            <div class="sig-area">
                <div class="sig-box">
                    @if($beneficiary->seniorCitizen->signature)
                        <img src="{{ Storage::url($beneficiary->seniorCitizen->signature) }}" alt="Signature">
                    @endif
                </div>
                <div class="sig-label">Signature / Thumbmark</div>
            </div>

            {{-- QR --}}
            @php
                $qrToken  = $beneficiary->seniorCitizen->qr_token ?? '';
                $idNum    = $beneficiary->seniorCitizen->id_number ?? $beneficiary->seniorCitizen->new_id_number ?? '';
                $qrData   = 'OSCA-CLAIM|ID:'.$idNum.'|TKN:'.$qrToken;
            @endphp
            <div class="qr-container">
                <div class="qr-box">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($qrData) }}" alt="QR Code">
                </div>
                <div class="qr-label">Scan to Verify</div>
            </div>
        </div>

        {{-- RIGHT: Info block --}}
        <div class="right">

            {{-- ID badge top-right --}}
            <div class="id-badge-row">
                <span class="id-badge">{{ $idNum }}</span>
            </div>

            {{-- Name --}}
            <div class="name-block">
                <div class="name-field">
                    <span class="nf-label">Last Name</span>
                    <span class="nf-value">
                        {{ strtoupper($beneficiary->seniorCitizen->last_name) }}
                        @if($beneficiary->seniorCitizen->suffix)&nbsp;{{ strtoupper($beneficiary->seniorCitizen->suffix) }}@endif
                    </span>
                </div>
                <div class="name-field">
                    <span class="nf-label">First Name</span>
                    <span class="nf-value">{{ strtoupper($beneficiary->seniorCitizen->first_name) }}</span>
                </div>
                <div class="name-field">
                    <span class="nf-label">Middle Name</span>
                    <span class="nf-value sm">{{ $beneficiary->seniorCitizen->middle_name ? strtoupper($beneficiary->seniorCitizen->middle_name) : '—' }}</span>
                </div>
            </div>

            {{-- DOB / Sex / Issue Date --}}
            <div class="detail-row">
                <div class="d-cell">
                    <span class="d-label">Date of Birth</span>
                    <span class="d-value">{{ $beneficiary->seniorCitizen->date_of_birth?->format('M d, Y') ?? 'N/A' }}</span>
                </div>
                <div class="d-cell" style="flex:0.7">
                    <span class="d-label">Sex</span>
                    <span class="d-value">{{ strtoupper(rtrim(optional($beneficiary->seniorCitizen->gender)->name ?? 'N/A', '0123456789')) }}</span>
                </div>
                <div class="d-cell">
                    <span class="d-label">Issued</span>
                    <span class="d-value">{{ $beneficiary->seniorCitizen->effective_date?->format('M d, Y') ?? 'N/A' }}</span>
                </div>
            </div>

            {{-- ── PAYOUT STRIP at bottom ── --}}
            <div class="payout-strip">
                <div class="payout-left">
                    <span class="payout-label">Provider</span>
                    <span class="payout-provider">{{ strtoupper($funding->provider ?? 'N/A') }}</span>
                </div>
                <div class="payout-amount-box">
                    <span class="payout-amount-label">AMOUNT</span>
                    <span class="payout-amount-value">₱{{ number_format($funding->amount ?? 0, 2) }}</span>
                </div>
            </div>

        </div>
    </div>

</div>