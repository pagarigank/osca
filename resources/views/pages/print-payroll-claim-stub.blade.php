<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSCA Payroll Claim Stub - {{ $beneficiary->seniorCitizen->id_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Special+Elite&family=Inter:wght@300;400;500;600;700&display=swap');

        /* ID Card dimensions: 86mm x 54mm */
        :root {
            --card-w: 86mm;
            --card-h: 54mm;
            --black:  #111111;
            --gray:   #555555;
            --light:  #f5f5f5;
            --border: #aaaaaa;
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: #e8e8e8;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .stub-page {
            width: var(--card-w);
            height: var(--card-h);
            background: #ffffff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 3px 14px rgba(0,0,0,0.18);
            display: flex;
            flex-direction: column;
        }

        .watermark {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }
        .watermark span {
            font-size: 12pt;
            font-weight: 900;
            color: rgba(0,0,0,0.04);
            text-transform: uppercase;
            letter-spacing: 3px;
            white-space: nowrap;
            transform: rotate(-30deg);
            line-height: 1;
        }
        .watermark-logo {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
            background-size: 32mm;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.07;
        }

        .content-layer {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5mm 2mm 1mm 2mm;
            border-bottom: 0.7mm solid #1a1a1a;
        }

        .header-logo {
            width: 13mm;
            height: 13mm;
            object-fit: contain;
            flex-shrink: 0;
        }

        .header-text {
            text-align: center;
            flex: 1;
            padding: 0 2mm;
        }
        .header-text .republic {
            font-size: 5.5pt;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--black);
            line-height: 1.4;
        }
        .header-text .city-name {
            font-size: 7.5pt;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--black);
            line-height: 1.4;
        }

        /* Print stub header - location info */
        .print-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1mm 2mm;
            border-bottom: 0.5mm solid var(--border);
            font-size: 5.5pt;
            color: var(--black);
            flex-wrap: wrap;
            gap: 1mm;
        }
        .print-header .header-item {
            white-space: nowrap;
            text-align: center;
        }
        .print-header .header-label {
            font-weight: 600;
            text-transform: uppercase;
            color: var(--gray);
            display: block;
            font-size: 4.5pt;
        }
        .print-header .header-value {
            color: var(--black);
        }

        .body {
            display: flex;
            flex: 1;
            padding: 1.5mm 2mm 1.5mm 2mm;
            gap: 2mm;
        }

        .left {
            width: 21mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0;
            flex-shrink: 0;
        }

        .photo-box {
            width: 21mm;
            height: 24mm;
            border: 0.5mm solid var(--border);
            background: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .photo-placeholder {
            font-size: 4pt;
            color: #999;
            text-align: center;
            padding: 1mm;
        }

        .sig-area {
            width: 21mm;
            margin-top: 2mm;
            text-align: center;
        }
        .sig-box {
            width: 21mm;
            height: 7mm;
            border-bottom: 0.4mm solid var(--black);
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 0.5mm;
            overflow: hidden;
        }
        .sig-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .sig-label {
            font-size: 4pt;
            color: var(--gray);
            margin-top: 0.5mm;
            letter-spacing: 0.5px;
        }

        .right {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding-left: 1mm;
        }

        .id-number-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1mm;
        }
        .id-number-badge {
            font-size: 6.5pt;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: var(--black);
        }

        .name-block {
            display: flex;
            align-items: flex-start;
            gap: 2mm;
            margin-bottom: 1mm;
        }
        .name-labels {
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .name-field {
            margin-bottom: 1mm;
        }
        .name-field-label {
            font-size: 4pt;
            color: var(--gray);
            font-style: italic;
            line-height: 1.1;
            display: block;
        }
        .name-field-value {
            font-size: 8pt;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--black);
            line-height: 1.1;
            display: block;
        }
        .name-field-value.small {
            font-size: 7pt;
        }

        /* QR Code container - enlarged */
        .qr-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .qr-box {
            width: 12mm;
            height: 12mm;
            border: 0.4mm solid var(--border);
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5mm;
        }
        .qr-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .qr-label {
            font-size: 4pt;
            color: var(--gray);
            text-align: center;
            margin-top: 0.4mm;
            line-height: 1.1;
        }

        .details-row {
            display: flex;
            gap: 2mm;
            margin-bottom: 0.5mm;
        }
        .detail-cell {
            display: flex;
            flex-direction: column;
        }
        .detail-cell.dob-cell { flex: 2; }
        .detail-cell.gender-cell { flex: 1; }
        .detail-cell.issue-cell { flex: 2; }

        .detail-label {
            font-size: 3.8pt;
            color: var(--gray);
            font-style: italic;
            line-height: 1.2;
        }
        .detail-value {
            font-size: 6pt;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--black);
        }

        .address-block {
            margin-top: 0;
        }
        .address-label {
            font-size: 3.8pt;
            color: var(--gray);
            font-style: italic;
            line-height: 1.2;
        }
        .address-value {
            font-size: 5.5pt;
            font-weight: 600;
            color: var(--black);
            line-height: 1.3;
            text-transform: uppercase;
        }

        .payroll-section {
            margin-top: auto;
            padding-top: 1mm;
            border-top: 0.3mm solid #aaa;
        }
        .payroll-label {
            font-size: 4pt;
            color: var(--gray);
            font-style: italic;
            margin-bottom: 0.3mm;
        }
        .payroll-value {
            font-size: 7pt;
            font-weight: 600;
            color: var(--black);
        }
        .payroll-amount {
            color: #16a34a;
            font-weight: 700;
        }

        /* Running line between stubs */
        .print-line {
            width: 80%;
            border-top: 0.5mm dashed var(--gray);
            margin: 2mm auto;
        }

        @page {
            size: 86mm 54mm;
            margin: 0;
        }

        @media print {
            html, body {
                background: none;
                padding: 0;
                margin: 0;
                width: 86mm;
            }
            .stub-page {
                box-shadow: none;
                page-break-before: always;
            }
            .stub-page:first-child {
                page-break-before: avoid;
            }
        }
    </style>
</head>
<body>

<div class="stub-page">

        {{-- Mabalacat City Seal Watermark --}}
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
                    <span class="header-label">Purok Leader</span>
                    <span class="header-value">{{ $beneficiary->seniorCitizen->purokLeader ? $beneficiary->seniorCitizen->purokLeader->last_name . ', ' . $beneficiary->seniorCitizen->purokLeader->first_name : 'N/A' }}</span>
                </div>
            </div>

            {{-- Body --}}
            <div class="body">

                {{-- LEFT COLUMN: Photo + Signature --}}
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
                </div>

                {{-- RIGHT COLUMN: Details with QR beside name --}}
                <div class="right">

                    {{-- ID Number --}}
                    <div class="id-number-row">
                        <span class="id-number-badge">{{ $beneficiary->seniorCitizen->id_number ?? $beneficiary->seniorCitizen->new_id_number }}</span>
                    </div>

                    {{-- Name Block with QR --}}
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

                        {{-- QR Code beside First Name --}}
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