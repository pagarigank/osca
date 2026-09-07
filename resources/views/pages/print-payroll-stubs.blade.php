<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSCA Payroll Claim Stubs - {{ $funding->provider }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Special+Elite&family=Inter:wght@300;400;500;600;700&display=swap');

        /* ID Card dimensions: 86mm x 54mm */
        :root {
            --card-w: 86mm;
            --card-h: 54mm;
            --black:  #111111;
            --gray:   #555555;
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: #fff;
        }

        .screen-label {
            font-family: 'Inter', sans-serif;
            font-size: 14pt;
            color: #333;
            font-weight: 600;
            text-align: center;
            margin-bottom: 10mm;
            text-transform: uppercase;
        }

        /* Container for multiple stubs on one page */
        .stubs-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 5mm;
            padding: 3mm;
        }

        .stub-page {
            width: var(--card-w);
            height: var(--card-h);
            background: #ffffff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
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
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            padding: 0.8mm 2mm;
            font-size: 5pt;
            color: var(--black);
            gap: 3mm 1mm;
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
            font-size: 4pt;
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

        .print-line {
            width: 80%;
            border-top: 1px dashed var(--gray);
            margin: 5mm auto;
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
            }
            body > .stubs-container {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: flex-start;
                gap: 3mm;
                padding: 2mm;
            }
            .stub-page {
                box-shadow: none;
            }
            .print-line {
                width: 80%;
                border-top: 1px dashed #999;
                margin: 3mm auto;
            }
            .screen-label {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="screen-label">Payroll Claim Stubs - {{ $funding->provider }}</div>

<div class="stubs-container">
    @foreach($beneficiaries as $ben)
        <div class="stub-page">
            @include('pages.print-payroll-claim-stub-single', ['beneficiary' => $ben, 'funding' => $funding])
        </div>
        <div class="print-line"></div>
    @endforeach
</div>

</body>
</html>