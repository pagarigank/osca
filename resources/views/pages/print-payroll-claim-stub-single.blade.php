<div class="stub-page">
    <div class="watermark-logo" style="background-image: url('{{ asset('images/mabalacat_seal.png') }}');"></div>

    <div class="content-layer">
        
        {{-- Header --}}
        <div class="header">
            <img src="{{ asset('images/mabalacat_seal.png') }}" class="header-logo" alt="Mabalacat City Seal">
            <div class="header-text">
                <div class="republic">OFFICE OF THE SENIOR CITIZENS AFFAIRS</div>
                <div class="city-name">MABALACAT CITY</div>
            </div>
            <img src="{{ asset('images/osca_logo.png') }}" class="header-logo" alt="OSCA Logo">
        </div>

        {{-- Print Stub Header - Location Info --}}
        <div class="print-header">
            <div class="header-item">
                <span class="header-label">City</span>
                <span class="header-value">MABALACAT</span>
            </div>
            <div class="header-item">
                <span class="header-label">Run Date</span>
                <span class="header-value">{{ now()->format('M d, Y') }}</span>
            </div>
            <div class="header-item">
                <span class="header-label">Barangay</span>
                <span class="header-value">{{ $beneficiary->seniorCitizen->barangay->name ?? 'N/A' }}</span>
            </div>
            <div class="header-item">
                <span class="header-label">Purok</span>
                <span class="header-value">{{ $beneficiary->seniorCitizen->purok_name ?? 'N/A' }}</span>
            </div>
            <div class="header-item">
                <span class="header-label">Leader</span>
                <span class="header-value">{{ $beneficiary->seniorCitizen->purokLeader ? $beneficiary->seniorCitizen->purokLeader->last_name . ', ' . $beneficiary->seniorCitizen->purokLeader->first_name : 'N/A' }}</span>
            </div>
        </div>

        {{-- Body --}}
        <div class="body">

            {{-- LEFT COLUMN: Photo + Signature + QR --}}
            <div class="left">
                <div class="photo-box">
                    @if($beneficiary->seniorCitizen->id_picture)
                        <img src="{{ Storage::url($beneficiary->seniorCitizen->id_picture) }}" alt="Photo">
                    @else
                        <div class="photo-placeholder">Photo<br>Here</div>
                    @endif
                </div>
                <div class="sig-area">
                    <div class="sig-box">
                        @if($beneficiary->seniorCitizen->signature)
                            <img src="{{ Storage::url($beneficiary->seniorCitizen->signature) }}" alt="Signature">
                        @endif
                    </div>
                    <div class="sig-label">Signature</div>
                </div>

                {{-- QR Code on FRONT --}}
                @php
                    $qrToken = $beneficiary->seniorCitizen->qr_token;
                    $qrContent = 'OSCA Payroll Claim|ID:' . ($beneficiary->seniorCitizen->id_number ?? $beneficiary->seniorCitizen->new_id_number) . '|Token:' . $qrToken;
                @endphp
                <div class="qr-container">
                    <div class="qr-box">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode($qrContent) }}" alt="QR Code">
                    </div>
                    <div class="qr-label">Verify</div>
                </div>
            </div>

            {{-- RIGHT COLUMN: Details --}}
            <div class="right">

                {{-- ID Number --}}
                <div class="id-number-row">
                    <span class="id-number-badge">{{ $beneficiary->seniorCitizen->id_number ?? $beneficiary->seniorCitizen->new_id_number }}</span>
                </div>

                {{-- Name Block --}}
                <div class="name-block">
                    <div class="name-labels">
                        <div class="name-field">
                            <span class="name-field-label">Last Name</span>
                            <span class="name-field-value">{{ strtoupper($beneficiary->seniorCitizen->last_name) }}@if($beneficiary->seniorCitizen->suffix) {{ strtoupper($beneficiary->seniorCitizen->suffix) }}@endif</span>
                        </div>
                        <div class="name-field">
                            <span class="name-field-label">First Name</span>
                            <span class="name-field-value">{{ strtoupper($beneficiary->seniorCitizen->first_name) }}</span>
                        </div>
                        <div class="name-field">
                            <span class="name-field-label">Middle Name</span>
                            <span class="name-field-value small">{{ $beneficiary->seniorCitizen->middle_name ? strtoupper($beneficiary->seniorCitizen->middle_name) : '—' }}</span>
                        </div>
                    </div>
                </div>

                {{-- DOB / Gender / Issue Date --}}
                <div class="details-row">
                    <div class="detail-cell dob-cell">
                        <span class="detail-label">DOB</span>
                        <span class="detail-value">
                            {{ $beneficiary->seniorCitizen->date_of_birth ? $beneficiary->seniorCitizen->date_of_birth->format('M d, Y') : 'N/A' }}
                        </span>
                    </div>
                    <div class="detail-cell gender-cell">
                        <span class="detail-label">Sex</span>
                        <span class="detail-value">
                            {{ strtoupper(rtrim(optional($beneficiary->seniorCitizen->gender)->name ?? 'N/A', '0123456789')) }}
                        </span>
                    </div>
                    <div class="detail-cell issue-cell">
                        <span class="detail-label">Issue</span>
                        <span class="detail-value">
                            {{ $beneficiary->seniorCitizen->effective_date ? $beneficiary->seniorCitizen->effective_date->format('M d, Y') : 'N/A' }}
                        </span>
                    </div>
                </div>

                {{-- Address --}}
                <div class="address-block">
                    <span class="address-label">Address</span>
                    <div class="address-value">
                        @php
                            $parts = array_filter([
                                $beneficiary->seniorCitizen->house_no,
                                $beneficiary->seniorCitizen->street,
                                $beneficiary->seniorCitizen->purok_name ? 'Purok ' . $beneficiary->seniorCitizen->purok_name : null,
                                optional($beneficiary->seniorCitizen->barangay)->name ? 'Brgy. ' . $beneficiary->seniorCitizen->barangay->name : null,
                                $beneficiary->seniorCitizen->city_municipality,
                                $beneficiary->seniorCitizen->province,
                            ]);
                            echo implode(', ', $parts);
                        @endphp
                    </div>
                </div>

                {{-- Payroll Info --}}
                <div class="payroll-section">
                    <div class="payroll-label">Fund Provider</div>
                    <div class="payroll-value">{{ $funding->provider ?? 'N/A' }}</div>
                    
                    <div class="payroll-label" style="margin-top: 1mm;">Amount</div>
                    <div class="payroll-value payroll-amount">₱{{ number_format($beneficiary->funding->amount ?? 0, 2) }}</div>
                </div>
            </div>
        </div>

    </div>
</div>