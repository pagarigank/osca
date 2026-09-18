<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Claim Stub — {{ $beneficiary->seniorCitizen->id_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        /* ── Card dimensions ── */
        :root {
            --card-w: 86mm;
            --card-h: 54mm;
            --black:  #111111;
            --gray:   #555555;
            --light:  #f5f5f5;
            --border: #bbbbbb;
            --accent: #1a4b8c; /* OSCA blue accent */
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            font-family: 'Inter', sans-serif;
            background: #d8d8d8;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ── Screen wrapper ── */
        .screen-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            gap: 6mm;
            padding: 8mm;
        }
        .screen-label {
            font-size: 11pt;
            font-weight: 700;
            color: #444;
            text-align: center;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .btn-print {
            padding: 8px 22px;
            font-size: 10pt;
            font-weight: 600;
            cursor: pointer;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 6px;
            font-family: 'Inter', sans-serif;
        }

        /* ── STUB CARD ── */
        .stub-page {
            width: var(--card-w);
            height: var(--card-h);
            background: #ffffff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.22);
            display: flex;
            flex-direction: column;
        }

        /* ── Watermark ── */
        .watermark-logo {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background-size: 28mm;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.06;
        }

        /* ── Content layer ── */
        .content-layer {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        /* ── HEADER ── */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.2mm 2mm 0.8mm 2mm;
            border-bottom: 0.8mm solid var(--accent);
            background: linear-gradient(to right, #f0f4fa, #fff, #f0f4fa);
        }
        .header-logo {
            width: 11mm;
            height: 11mm;
            object-fit: contain;
            flex-shrink: 0;
        }
        .header-text {
            text-align: center;
            flex: 1;
            padding: 0 1.5mm;
        }
        .header-text .republic {
            font-size: 4.5pt;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--gray);
            line-height: 1.3;
        }
        .header-text .city-name {
            font-size: 5.5pt;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--black);
            line-height: 1.3;
        }
        .header-text .stub-type-label {
            font-size: 6.5pt;
            font-weight: 800;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-top: 0.5mm;
            line-height: 1.2;
        }

        /* ── META ROW ── */
        .meta-row {
            display: flex;
            justify-content: space-between;
            padding: 0.7mm 2mm;
            background: var(--accent);
            gap: 2mm;
        }
        .meta-cell {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .meta-label {
            font-size: 3.2pt;
            font-weight: 700;
            text-transform: uppercase;
            color: rgba(255,255,255,0.7);
            letter-spacing: 0.3px;
        }
        .meta-val {
            font-size: 4.5pt;
            font-weight: 700;
            color: #fff;
            white-space: nowrap;
        }

        /* ── BODY ── */
        .body {
            display: flex;
            flex: 1;
            padding: 1.2mm 1.5mm;
            gap: 1.8mm;
        }

        /* LEFT column */
        .left {
            width: 18mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0;
            flex-shrink: 0;
        }
        .photo-box {
            width: 18mm;
            height: 20mm;
            border: 0.4mm solid var(--border);
            background: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }
        .photo-box img { width: 100%; height: 100%; object-fit: cover; }
        .photo-placeholder { font-size: 4pt; color: #999; text-align: center; }

        .sig-area { width: 18mm; margin-top: 1.2mm; text-align: center; }
        .sig-box {
            width: 18mm;
            height: 5.5mm;
            border-bottom: 0.4mm solid var(--black);
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 0.3mm;
            overflow: hidden;
        }
        .sig-box img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .sig-label { font-size: 3.2pt; color: var(--gray); margin-top: 0.3mm; letter-spacing: 0.3px; }

        .qr-container { margin-top: 1mm; display: flex; flex-direction: column; align-items: center; }
        .qr-box {
            width: 11mm;
            height: 11mm;
            border: 0.3mm solid var(--border);
            background: #fff;
            padding: 0.4mm;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .qr-box img { width: 100%; height: 100%; object-fit: contain; }
        .qr-label { font-size: 3.2pt; color: var(--gray); margin-top: 0.3mm; }

        /* RIGHT column */
        .right {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* ID badge */
        .id-badge-row { display: flex; justify-content: flex-end; margin-bottom: 0.8mm; }
        .id-badge {
            font-size: 5.5pt;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: var(--accent);
            font-family: 'Courier New', monospace;
        }

        /* Name block */
        .name-block { margin-bottom: 0.8mm; }
        .name-field { margin-bottom: 0.6mm; }
        .nf-label {
            font-size: 3.2pt;
            color: var(--gray);
            font-style: italic;
            line-height: 1.1;
            display: block;
            text-transform: uppercase;
        }
        .nf-value {
            font-size: 7.5pt;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--black);
            line-height: 1.0;
            display: block;
        }
        .nf-value.sm { font-size: 6.5pt; font-weight: 700; }

        /* Detail row */
        .detail-row { display: flex; gap: 1.5mm; margin-bottom: 0.8mm; }
        .d-cell { display: flex; flex-direction: column; flex: 1; }
        .d-label { font-size: 3.2pt; color: var(--gray); font-style: italic; line-height: 1.2; text-transform: uppercase; }
        .d-value { font-size: 5.5pt; font-weight: 700; letter-spacing: 0.3px; text-transform: uppercase; color: var(--black); }

        /* ── PAYOUT STRIP ── */
        .payout-strip {
            margin-top: auto;
            display: flex;
            align-items: stretch;
            border: 0.5mm solid var(--accent);
            border-radius: 0.5mm;
            overflow: hidden;
        }
        .payout-left {
            flex: 1;
            background: #f0f4fa;
            padding: 0.8mm 1.2mm;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .payout-label {
            font-size: 3pt;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--gray);
            letter-spacing: 0.3px;
        }
        .payout-provider {
            font-size: 5.5pt;
            font-weight: 800;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .payout-amount-box {
            background: var(--accent);
            padding: 0.8mm 2.5mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 18mm;
        }
        .payout-amount-label {
            font-size: 3pt;
            font-weight: 700;
            text-transform: uppercase;
            color: rgba(255,255,255,0.75);
            letter-spacing: 0.5px;
        }
        .payout-amount-value {
            font-size: 9pt;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.5px;
        }

        /* ── PRINT ── */
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
            .screen-wrapper { display: contents; }
            .screen-label, .btn-print { display: none; }
            .stub-page { box-shadow: none; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="screen-wrapper">
    <div class="screen-label">Payroll Claim Stub Preview</div>
    <button class="btn-print" onclick="window.print()">🖨 Print Stub</button>

    <div class="stub-page">
        @include('pages.print-payroll-claim-stub-single', ['beneficiary' => $beneficiary, 'funding' => $funding])
    </div>
</div>

</body>
</html>