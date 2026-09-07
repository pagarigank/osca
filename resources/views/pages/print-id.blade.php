<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senior Citizen ID - {{ $sc->id_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Special+Elite&family=Inter:wght@300;400;500;600;700&display=swap');

        /* ── ID Card: 86mm × 54mm ────────────────────────────── */
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

        /* Screen preview wrapper */
        .preview-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12mm;
            padding: 10mm;
        }

        /* ── CARD BASE ───────────────────────────────────────────────────── */
        .card {
            width: var(--card-w);
            height: var(--card-h);
            background: #ffffff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 3px 14px rgba(0,0,0,0.18);
            display: flex;
            flex-direction: column;
        }

        /* ── WATERMARK ───────────────────────────────────────────────────── */
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
            font-size: 14pt;
            font-weight: 900;
            color: rgba(0,0,0,0.04);
            text-transform: uppercase;
            letter-spacing: 4px;
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
            background-size: 38mm;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.07;
        }

        /* ── CONTENT LAYER ───────────────────────────────────────────────── */
        .content-layer {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        /* ══════════════════════════════════════════════════════════════════
           FRONT CARD
        ══════════════════════════════════════════════════════════════════ */

        /* ── Header ─────────────────────────────────────────────────────── */
        .front-header {
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
        .header-text .office-name {
            font-size: 5.5pt;
            letter-spacing: 0.5px;
            color: var(--black);
            line-height: 1.3;
        }

        /* ── Front Body ─────────────────────────────────────────────────── */
        .front-body {
            display: flex;
            flex: 1;
            padding: 1.5mm 2mm 1.5mm 2mm;
            gap: 2mm;
        }

        /* ── Left Column: Photo + Signature ─────────────────────────────── */
        .front-left {
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

        /* ── Right Column: ID Info ──────────────────────────────────────── */
        .front-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding-left: 1mm;
        }

        /* ID Number — top right */
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

        /* Name block — 3 labeled rows */
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

        /* DOB / Gender / Issue Date row */
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
            line-height: 1.2;
        }

        /* Address */
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

        /* ══════════════════════════════════════════════════════════════════
           BACK CARD
        ══════════════════════════════════════════════════════════════════ */

        .back-body {
            display: flex;
            flex: 1;
            padding: 2mm;
            gap: 2.5mm;
        }

        /* Back Left: Thumbmark + QR — fixed-size boxes, NOT stretching */
        .back-left-boxes {
            width: 26mm;
            display: flex;
            flex-direction: row;
            gap: 1mm;
            align-items: flex-start;  /* anchor to top, not full height */
        }

        .thumb-box {
            width: 12mm;
            height: 14mm;
            border: 0.4mm solid var(--border);
            background: var(--light);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0.5mm;
            flex-shrink: 0;
        }
        .thumb-box-label {
            font-size: 3.2pt;
            color: var(--gray);
            text-align: center;
            line-height: 1.3;
        }

        .qr-box {
            width: 13mm;
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
        .qr-placeholder {
            font-size: 3pt;
            color: var(--gray);
            text-align: center;
        }

        /* Back Right: Officials + Emergency */
        .back-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .officials-area {
            display: flex;
            flex-direction: column;
        }

        .official-block {
            display: flex;
            flex-direction: column;
        }
        .official-sig-line {
            width: 100%;
            height: 7mm;   /* space for actual signature */
            border-bottom: 0.4mm solid var(--black);
            margin-bottom: 0.5mm;
        }
        .official-name {
            font-size: 5.5pt;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--black);
            line-height: 1.2;
        }
        .official-title {
            font-size: 4pt;
            color: var(--gray);
            font-style: italic;
            line-height: 1.2;
            margin-bottom: 2mm;
        }

        .emergency-block {
            margin-top: auto;
        }
        .emergency-heading {
            font-size: 4pt;
            font-style: italic;
            font-weight: 600;
            text-decoration: underline;
            color: var(--black);
            margin-bottom: 0.8mm;
            display: block;
        }
        .emergency-line {
            font-size: 5pt;
            color: var(--black);
            line-height: 1.4;
        }
        .emergency-line span {
            font-weight: 600;
        }

        /* ── Print Rules ─────────────────────────────────────────────────── */
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
            .preview-wrap {
                display: block;
                padding: 0;
                gap: 0;
                background: none;
                margin: 0;
                width: 86mm;
            }
            .preview-wrap > div {
                page-break-inside: avoid;
                break-inside: avoid;
            }
            .card {
                box-shadow: none;
                page-break-inside: avoid;
                break-inside: avoid;
            }
            .card-front {
                page-break-after: always;
                break-after: page;
            }
            .card-back {
                page-break-after: auto;
                break-after: auto;
            }
            .screen-label {
                display: none !important;
            }
        }

        /* ── Screen label ────────────────────────────────────────────────── */
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

    {{-- ══════════════════════════════════════════════════════════════════
         FRONT OF ID
    ══════════════════════════════════════════════════════════════════ --}}
    <div>
        <div class="screen-label">— FRONT —</div>
        <div class="card card-front">

            {{-- Mabalacat City Seal Watermark --}}
            <div class="watermark-logo" style="background-image: url('{{ asset('images/mabalacat_seal.png') }}');"></div>

            <div class="content-layer">

                {{-- ── Header ── --}}
                <div class="front-header">
                    {{-- Left: Mabalacat City Seal --}}
                    <img src="{{ asset('images/mabalacat_seal.png') }}"
                         class="header-logo" alt="Mabalacat City Seal">

                    {{-- Center: Official Text --}}
                    <div class="header-text">
                        <div class="republic">Republic of the Philippines</div>
                        <div class="republic">PROVINCE OF PAMPANGA</div>
                        <div class="city-name">MABALACAT CITY</div>
                        <div class="office-name">Office of the Senior Citizens Affairs</div>
                    </div>

                    {{-- Right: OSCA Logo --}}
                    <img src="{{ asset('images/osca_logo.png') }}"
                         class="header-logo" alt="OSCA Logo">
                </div>

                {{-- ── Body ── --}}
                <div class="front-body">

                    {{-- Left Column: Photo + Signature --}}
                    <div class="front-left">
                        <div class="photo-box">
                            @if($sc->id_picture)
                                <img src="{{ Storage::url($sc->id_picture) }}" alt="Photo">
                            @else
                                <div class="photo-placeholder">Picture<br>Here</div>
                            @endif
                        </div>
                        <div class="sig-area">
                            <div class="sig-box">
                                @if($sc->signature)
                                    <img src="{{ Storage::url($sc->signature) }}" alt="Signature">
                                @endif
                            </div>
                            <div class="sig-label">Signature</div>
                        </div>
                    </div>

                    {{-- Right Column: Details --}}
                    <div class="front-right">

                        {{-- ID Number — top right --}}
                        <div class="id-number-row">
                            <span class="id-number-badge">{{ $sc->id_number }}</span>
                        </div>

                        {{-- Name Block: 3 labeled rows --}}
                        <div class="name-block">
                            <div class="name-field">
                                <span class="name-field-label">Last Name</span>
                                <span class="name-field-value">{{ strtoupper($sc->last_name) }}@if($sc->suffix) {{ strtoupper($sc->suffix) }}@endif</span>
                            </div>
                            <div class="name-field">
                                <span class="name-field-label">First Name</span>
                                <span class="name-field-value">{{ strtoupper($sc->first_name) }}</span>
                            </div>
                            <div class="name-field">
                                <span class="name-field-label">Middle Name</span>
                                <span class="name-field-value small">{{ $sc->middle_name ? strtoupper($sc->middle_name) : '—' }}</span>
                            </div>
                        </div>

                        {{-- DOB / Gender / Issue Date --}}
                        <div class="details-row">
                            <div class="detail-cell dob-cell">
                                <span class="detail-label">Date of Birth</span>
                                <span class="detail-value">
                                    {{ $sc->date_of_birth ? strtoupper($sc->date_of_birth->format('F j, Y')) : 'N/A' }}
                                </span>
                            </div>
                            <div class="detail-cell gender-cell">
                                <span class="detail-label">Gender</span>
                                <span class="detail-value">
                                    {{ strtoupper(rtrim(optional($sc->gender)->name ?? 'N/A', '0123456789')) }}
                                </span>
                            </div>
                            <div class="detail-cell issue-cell">
                                <span class="detail-label">Issue Date</span>
                                <span class="detail-value">
                                    {{ $sc->effective_date ? strtoupper($sc->effective_date->format('F j, Y')) : 'N/A' }}
                                </span>
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="address-block">
                            <span class="address-label">Address</span>
                            <div class="address-value">
                                @php
                                    $parts = array_filter([
                                        $sc->house_no,
                                        $sc->street,
                                        $sc->purok_name ? 'Purok ' . $sc->purok_name : null,
                                        optional($sc->barangay)->name ? 'Brgy. ' . $sc->barangay->name : null,
                                        $sc->city_municipality,
                                        $sc->province,
                                    ]);
                                    echo implode(', ', $parts);
                                @endphp
                            </div>
                        </div>

                    </div>{{-- /front-right --}}
                </div>{{-- /front-body --}}
            </div>{{-- /content-layer --}}
        </div>{{-- /card-front --}}
    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         BACK OF ID
    ══════════════════════════════════════════════════════════════════ --}}
    <div>
        <div class="screen-label">— BACK —</div>
        <div class="card card-back">

            {{-- Bagong Pilipinas Watermark --}}
            <div class="watermark">
                <span>BAGONG PILIPINAS &nbsp; BAGONG PILIPINAS &nbsp; BAGONG PILIPINAS</span>
            </div>

            <div class="content-layer">
                <div class="back-body">

                    {{-- LEFT: Fixed Thumbmark + QR Code boxes --}}
                    <div class="back-left-boxes">

                        <div class="thumb-box">
                            @if($sc->thumbmark)
                                <img src="{{ Storage::url($sc->thumbmark) }}" alt="Thumbmark" style="width:100%; height:100%; object-fit:contain;">
                            @else
                                <div class="thumb-box-label">Thumbmark<br>Here</div>
                            @endif
                        </div>

                        <div class="qr-box">
                            @if($qrBase64)
                                <img src="{{ $qrBase64 }}" alt="QR Code">
                            @else
                                <div class="qr-placeholder">QR Code<br>Here</div>
                            @endif
                        </div>

                    </div>{{-- /back-left-boxes --}}

                    {{-- RIGHT: Officials + Emergency Contact --}}
                    <div class="back-right">

                        <div class="officials-area">
                            {{-- OSCA Head --}}
                            <div class="official-block">
                                <div class="official-sig-line"></div>
                                <div class="official-name">RODRIGO G. WAJE</div>
                                <div class="official-title">OSCA Head</div>
                            </div>

                            {{-- City Mayor (3 lines below = margin via official-title margin-bottom) --}}
                            <div class="official-block">
                                <div class="official-sig-line"></div>
                                <div class="official-name">GERALD GUTRIE P. AQUINO</div>
                                <div class="official-title">City Mayor</div>
                            </div>
                        </div>

                        {{-- Emergency Contact --}}
                        <div class="emergency-block">
                            <span class="emergency-heading">In case of Emergency Notify:</span>
                            <div class="emergency-line">
                                Name&nbsp;&nbsp;
                                <span>{{ $sc->emergency_contact ?: 'N/A' }}</span>
                            </div>
                            <div class="emergency-line">
                                Phone&nbsp;
                                <span>{{ $sc->emergency_contact_no ?: 'N/A' }}</span>
                            </div>
                        </div>

                    </div>{{-- /back-right --}}

                </div>{{-- /back-body --}}
            </div>{{-- /content-layer --}}
        </div>{{-- /card-back --}}
    </div>

</div>{{-- /preview-wrap --}}
</body>
</html>
