<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSCA Payroll Claim Stubs - {{ $funding->provider }} {{ $funding->fund_source }}</title>
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

        /* Screen preview wrapper - one stub per row */
        .preview-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8mm;
            padding: 10mm;
        }

        /* SINGLE STUB WRAPPER - each stub is full page size */
        .stub-wrapper {
            width: 100%;
            max-width: 86mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4mm;
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

        /* WATERMARK */
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
            font-family: 'Inter', sans-serif;
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

        /* CONTENT LAYER */
        .content-layer {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        /* HEADER */
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

        /* BODY */
        .body {
            display: flex;
            flex: 1;
            padding: 1.5mm 2mm 1.5mm 2mm;
            gap: 2mm;
        }

        /* LEFT COLUMN: Photo + Signature */
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

        /* RIGHT COLUMN: Details */
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
            margin-bottom: 1mm;
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

        /* PAYROLL INFO */
        .payroll-section {
            margin-top: 1mm;
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
            line-height: 1.2;
        }
        .payroll-amount {
            color: #16a34a;
            font-weight: 700;
        }

        /* QR CODE - larger for scanning */
        .qr-section {
            padding: 1mm 2mm 1mm 2mm;
            border-top: 0.3mm solid #aaa;
            display: flex;
            align-items: center;
            gap: 2mm;
        }

        .qr-box {
            width: 14mm;
            height: 14mm;
            border: 0.4mm solid var(--border);
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5mm;
            flex-shrink: 0;
        }
        .qr-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .qr-info {
            font-size: 5.5pt;
            color: var(--black);
            font-weight: 600;
        }
        .qr-info small {
            display: block;
            font-size: 3.8pt;
            color: var(--gray);
            font-weight: 400;
            margin-top: 0.2mm;
        }

        /* Print Rules - multiple stubs per page */
        @page {
            size: portset 86mm 54mm;
            margin: 0;
        }

        @media print {
            body {
                background: none;
                padding: 0;
                margin: 0;
            }
            .preview-wrap {
                display: flex;
                flex-direction: column;
                gap: 5mm;
            }
            .stub-wrapper {
                page-break-inside: avoid;
            }
            .stub-page {
                box-shadow: none;
            }
            .stub-page + .stub-wrapper {
                page-break-before: always;
            }
            .screen-label {
                display: none !important;
            }
        }

        /* Screen label */
        .screen-label {
            font-family: 'Inter', sans-serif;
            font-size: 9pt;
            color: #666;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-align: center;
            margin-bottom: 3mm;
        }
        @media print { .screen-label { display: none; } }
    </style>
</head>
<body onload="window.print()">

<div class="preview-wrap">
    <div class="screen-label">Payroll Claim Stubs</div>
    
    @foreach($beneficiaries as $ben)
    <div class="stub-wrapper">
        <div class="stub-page">

           {{-- Watermark --}}
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

                {{-- Body --}}
                <div class="body">
                    
                    {{-- Left Column: Photo + Signature --}}
                    <div class="left">
                        <div class="photo-box">
                            @if($ben->seniorCitizen->id_picture)
                                <img src="{{ Storage::url($ben->seniorCitizen->id_picture) }}" alt="Photo">
                            @else
                                <div class="photo-placeholder">Photo<br>Here</div>
                            @endif
                        </div>
                        <div class="sig-area">
                            <div class="sig-box">
                                @if($ben->seniorCitizen->signature)
                                    <img src="{{ Storage::url($ben->seniorCitizen->signature) }}" alt="Signature">
                                @endif
                            </div>
                            <div class="sig-label">Signature</div>
                        </div>
                    </div>

                    {{-- Right Column: Details --}}
                    <div class="right">
                        
                        {{-- ID Number --}}
                        <div class="id-number-row">
                            <span class="id-number-badge">{{ $ben->seniorCitizen->id_number ?? $ben->seniorCitizen->new_id_number }}</span>
                        </div>

                        {{-- Name Block --}}
                        <div class="name-block">
                            <div class="name-field">
                                <span class="name-field-label">Last Name</span>
                                <span class="name-field-value">{{ strtoupper($ben->seniorCitizen->last_name) }}@if($ben->seniorCitizen->suffix) {{ strtoupper($ben->seniorCitizen->suffix) }}@endif</span>
                            </div>
                            <div class="name-field">
                                <span class="name-field-label">First Name</span>
                                <span class="name-field-value">{{ strtoupper($ben->seniorCitizen->first_name) }}</span>
                            </div>
                            <div class="name-field">
                                <span class="name-field-label">Middle Name</span>
                                <span class="name-field-value small">{{ $ben->seniorCitizen->middle_name ? strtoupper($ben->seniorCitizen->middle_name) : '—' }}</span>
                            </div>
                        </div>

                        {{-- DOB / Gender / Issue Date --}}
                        <div class="details-row">
                            <div class="detail-cell dob-cell">
                                <span class="detail-label">DOB</span>
                                <span class="detail-value">
                                    {{ $ben->seniorCitizen->date_of_birth ? $ben->seniorCitizen->date_of_birth->format('M d, Y') : 'N/A' }}
                                </span>
                            </div>
                            <div class="detail-cell gender-cell">
                                <span class="detail-label">Sex</span>
                                <span class="detail-value">
                                    {{ strtoupper(rtrim(optional($ben->seniorCitizen->gender)->name ?? 'N/A', '0123456789')) }}
                                </span>
                            </div>
                            <div class="detail-cell issue-cell">
                                <span class="detail-label">Issue</span>
                                <span class="detail-value">
                                    {{ $ben->seniorCitizen->effective_date ? $ben->seniorCitizen->effective_date->format('M d, Y') : 'N/A' }}
                                </span>
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="address-block">
                            <span class="address-label">Address</span>
                            <div class="address-value">
                                @php
                                    $parts = array_filter([
                                        $ben->seniorCitizen->house_no,
                                        $ben->seniorCitizen->street,
                                        $ben->seniorCitizen->purok_name ? 'Purok ' . $ben->seniorCitizen->purok_name : null,
                                        optional($ben->seniorCitizen->barangay)->name ? 'Brgy. ' . $ben->seniorCitizen->barangay->name : null,
                                        $ben->seniorCitizen->city_municipality,
                                        $ben->seniorCitizen->province,
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
                            <div class="payroll-value payroll-amount">₱{{ number_format($ben->funding->amount ?? 0, 2) }}</div>
                        </div>
                    </div>
                </div>

                {{-- QR Code for Verification --}}
                @php
                    $qrToken = $ben->seniorCitizen->qr_token;
                    $qrContent = 'OSCA Payroll Claim|ID:' . ($ben->seniorCitizen->id_number ?? $ben->seniorCitizen->new_id_number) . '|Token:' . $qrToken;
                @endphp

                <div class="qr-section">
                    <div class="qr-box">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode($qrContent) }}" alt="Payroll QR Code">
                    </div>
                    <div class="qr-info">
                        Verify Online
                        <small>Scan QR code for authenticity</small>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endforeach

</div>
</body>
</html>