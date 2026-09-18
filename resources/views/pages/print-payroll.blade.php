<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Report - {{ $funding->fund_source }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', Arial, sans-serif;
            font-size: 10pt;
            color: #1a1a1a;
            background: #fff;
            padding: 12mm 14mm;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ──── DOCUMENT HEADER ──── */
        .doc-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 5mm;
            border-bottom: 1.5pt solid #1a1a1a;
            margin-bottom: 4mm;
        }
        .doc-header .logo {
            width: 20mm;
            height: 20mm;
            object-fit: contain;
            flex-shrink: 0;
        }
        .doc-header .title-block {
            flex: 1;
            text-align: center;
            padding: 0 5mm;
        }
        .doc-header .republic-text {
            font-size: 7.5pt;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #444;
            margin-bottom: 1mm;
        }
        .doc-header .lgu-name {
            font-size: 12pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #1a1a1a;
            line-height: 1.2;
        }
        .doc-header .office-name {
            font-size: 8.5pt;
            font-weight: 600;
            text-transform: uppercase;
            color: #444;
            margin-top: 0.5mm;
            letter-spacing: 0.5px;
        }

        /* ──── REPORT TITLE ──── */
        .report-title-bar {
            text-align: center;
            border: 1.5pt solid #1a1a1a;
            padding: 2.5mm 4mm;
            margin-bottom: 4mm;
        }
        .report-title-bar h1 {
            font-size: 11pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #1a1a1a;
        }
        .report-title-bar .subtitle {
            font-size: 8pt;
            color: #555;
            margin-top: 0.5mm;
            font-weight: 500;
        }

        /* ──── META INFO ──── */
        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 2mm 6mm;
            margin-bottom: 5mm;
            padding: 3mm 4mm;
            background: #f8f8f8;
            border: 0.5pt solid #ddd;
        }
        .meta-item { display: flex; flex-direction: column; }
        .meta-label {
            font-size: 6.5pt;
            font-weight: 700;
            text-transform: uppercase;
            color: #777;
            letter-spacing: 0.5px;
            margin-bottom: 0.5mm;
        }
        .meta-value {
            font-size: 9pt;
            font-weight: 600;
            color: #1a1a1a;
        }

        /* ──── TABLE ──── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6mm;
            font-size: 8.5pt;
        }
        thead tr {
            background: #1a1a1a;
            color: #fff;
        }
        thead th {
            padding: 2.5mm 3mm;
            text-align: left;
            font-weight: 700;
            font-size: 7.5pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 0.5pt solid #333;
        }
        tbody tr:nth-child(even) { background: #fafafa; }
        tbody tr:hover { background: #f0f4ff; }
        tbody td {
            padding: 2mm 3mm;
            border: 0.5pt solid #ddd;
            vertical-align: middle;
            color: #1a1a1a;
        }
        .td-num { text-align: center; font-weight: 600; width: 8mm; color: #777; }
        .td-id { font-family: 'Courier New', monospace; font-size: 8pt; font-weight: 700; color: #1a1a1a; width: 28mm; }
        .td-name { font-weight: 600; text-transform: uppercase; font-size: 8.5pt; }
        .td-brgy { font-size: 8pt; }
        .td-amount { text-align: right; font-weight: 700; font-size: 9pt; width: 20mm; }
        .td-sig { width: 35mm; height: 9mm; }

        tfoot tr { background: #f0f0f0; }
        tfoot th {
            padding: 2mm 3mm;
            border: 0.5pt solid #999;
            font-size: 9pt;
        }
        tfoot .total-label { text-align: right; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
        tfoot .total-amount { text-align: right; font-weight: 800; font-size: 10pt; }

        /* ──── SUMMARY BAR ──── */
        .summary-bar {
            display: flex;
            justify-content: flex-end;
            gap: 8mm;
            margin-bottom: 8mm;
            padding: 2.5mm 4mm;
            background: #f8f8f8;
            border: 0.5pt solid #ddd;
            font-size: 8.5pt;
        }
        .summary-bar .sum-item { display: flex; flex-direction: column; align-items: flex-end; }
        .summary-bar .sum-label { font-size: 6.5pt; color: #777; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .summary-bar .sum-value { font-size: 10pt; font-weight: 800; color: #1a1a1a; }

        /* ──── SIGNATURE FOOTER ──── */
        .sig-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 10mm;
            padding-top: 0;
            gap: 10mm;
        }
        .sig-block {
            flex: 1;
            text-align: center;
        }
        .sig-name-line {
            border-top: 1pt solid #1a1a1a;
            padding-top: 2mm;
            margin-top: 12mm;
        }
        .sig-name {
            font-size: 9pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #1a1a1a;
        }
        .sig-position {
            font-size: 7pt;
            color: #666;
            margin-top: 0.5mm;
            font-style: italic;
        }

        /* ──── PRINT BUTTON (screen only) ──── */
        .no-print {
            margin-bottom: 6mm;
            display: flex;
            justify-content: flex-end;
            gap: 3mm;
        }
        .btn-print {
            padding: 8px 20px;
            font-size: 10pt;
            font-weight: 600;
            cursor: pointer;
            background: #1a1a1a;
            color: white;
            border: none;
            border-radius: 6px;
            font-family: 'Inter', sans-serif;
        }

        /* ──── PAGE BREAK ──── */
        @page { margin: 0; size: A4 portrait; }

        @media print {
            body { padding: 10mm 12mm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">🖨 Print Report</button>
    </div>

    {{-- DOCUMENT HEADER --}}
    <div class="doc-header">
        <img src="{{ asset('images/mabalacat_seal.png') }}" class="logo" alt="Mabalacat Seal">
        <div class="title-block">
            <div class="republic-text">Republic of the Philippines</div>
            <div class="lgu-name">City Government of Mabalacat</div>
            <div class="office-name">Office of the Senior Citizens Affairs (OSCA)</div>
        </div>
        <img src="{{ asset('images/osca_logo.png') }}" class="logo" alt="OSCA Logo">
    </div>

    {{-- REPORT TITLE --}}
    <div class="report-title-bar">
        <h1>Payroll Report — List of Beneficiaries</h1>
        <div class="subtitle">
            {{ $funding->fund_source }} &nbsp;|&nbsp; Printed: {{ now()->format('F d, Y \a\t h:i A') }}
        </div>
    </div>

    {{-- META INFO GRID --}}
    <div class="meta-grid">
        <div class="meta-item">
            <span class="meta-label">Fund Provider</span>
            <span class="meta-value">{{ $funding->provider ?? 'N/A' }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Fund Source</span>
            <span class="meta-value">{{ $funding->fund_source }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Effective Date</span>
            <span class="meta-value">{{ $funding->effective_date ? \Carbon\Carbon::parse($funding->effective_date)->format('F d, Y') : 'N/A' }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Amount Per Beneficiary</span>
            <span class="meta-value">₱{{ number_format($funding->amount, 2) }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Target Beneficiaries</span>
            <span class="meta-value">{{ $funding->beneficiary ?? 'N/A' }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Total Allocation</span>
            <span class="meta-value">₱{{ number_format($funding->total, 2) }}</span>
        </div>
    </div>

    {{-- PAYROLL TABLE WITH 4-LEVEL DRILL DOWN --}}
    @php 
        $globalCounter = 1;
        $grandTotalAmount = 0;
        $totalBeneficiariesCount = 0;
    @endphp

    @forelse($groupedBeneficiaries as $city => $barangays)
        <h2 style="font-size: 11pt; margin-top: 5mm; text-transform: uppercase;">City/Municipality: {{ $city }}</h2>
        
        @foreach($barangays as $barangay => $puroks)
            <h3 style="font-size: 10pt; margin-top: 3mm; margin-left: 5mm; text-transform: uppercase;">Barangay: {{ $barangay }}</h3>
            
            @foreach($puroks as $purok => $leaders)
                <h4 style="font-size: 9pt; margin-top: 2mm; margin-left: 10mm; text-transform: uppercase; color: #444;">Purok: {{ $purok }}</h4>
                
                @foreach($leaders as $leader => $beneficiaries)
                    <h5 style="font-size: 8.5pt; margin-top: 1mm; margin-bottom: 2mm; margin-left: 15mm; text-transform: uppercase; color: #666;">Purok Leader: {{ $leader }}</h5>
                    
                    <table>
                        <thead>
                            <tr>
                                <th class="td-num">#</th>
                                <th class="td-id">OSCA ID No.</th>
                                <th>Beneficiary Name</th>
                                <th>Gender</th>
                                <th style="text-align:right;">Amount (₱)</th>
                                <th style="text-align:center; width:35mm;">Signature / Thumbmark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $groupTotalAmount = 0; @endphp
                            @foreach($beneficiaries as $ben)
                                @php 
                                    $amount = $ben->funding->amount ?? $funding->amount ?? 0; 
                                    $groupTotalAmount += $amount;
                                    $grandTotalAmount += $amount;
                                    $totalBeneficiariesCount++;
                                @endphp
                                <tr>
                                    <td class="td-num">{{ $globalCounter++ }}</td>
                                    <td class="td-id">{{ $ben->seniorCitizen->id_number ?? $ben->seniorCitizen->new_id_number }}</td>
                                    <td class="td-name">
                                        {{ strtoupper($ben->seniorCitizen->last_name) }}{{ $ben->seniorCitizen->suffix ? ', '.$ben->seniorCitizen->suffix : '' }},
                                        {{ strtoupper($ben->seniorCitizen->first_name) }}
                                        {{ $ben->seniorCitizen->middle_name ? strtoupper(substr($ben->seniorCitizen->middle_name, 0, 1)).'.' : '' }}
                                    </td>
                                    <td>{{ $ben->seniorCitizen->gender->name ?? 'N/A' }}</td>
                                    <td class="td-amount">{{ number_format($amount, 2) }}</td>
                                    <td class="td-sig"></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="total-label" style="text-align: right; padding-right: 5mm;">Subtotal ({{ count($beneficiaries) }} pax)</th>
                                <th class="total-amount">₱{{ number_format($groupTotalAmount, 2) }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                @endforeach
            @endforeach
        @endforeach
    @empty
        <div style="text-align:center; padding: 6mm; color:#777; border: 1px solid #ddd; margin-bottom: 5mm;">
            No beneficiaries found for this funding source.
        </div>
    @endforelse

    @if($totalBeneficiariesCount > 0)
        <div style="text-align: right; font-size: 11pt; font-weight: 800; border-top: 1.5pt solid #000; padding-top: 2mm; margin-bottom: 5mm;">
            GRAND TOTAL: ₱{{ number_format($grandTotalAmount, 2) }}
        </div>
    @endif


    {{-- SUMMARY BAR --}}
    <div class="summary-bar">
        <div class="sum-item">
            <span class="sum-label">Total Beneficiaries</span>
            <span class="sum-value">{{ $totalBeneficiariesCount ?? 0 }}</span>
        </div>
        <div class="sum-item">
            <span class="sum-label">Total Amount Released</span>
            <span class="sum-value">₱{{ number_format($grandTotalAmount ?? 0, 2) }}</span>
        </div>
    </div>

    {{-- SIGNATURE FOOTER --}}
    <div class="sig-footer">
        <div class="sig-block">
            <div class="sig-name-line">
                <div class="sig-name">________________________</div>
                <div class="sig-position">Prepared By / Encoder</div>
            </div>
        </div>
        <div class="sig-block">
            <div class="sig-name-line">
                <div class="sig-name">________________________</div>
                <div class="sig-position">Verified By</div>
            </div>
        </div>
        <div class="sig-block">
            <div class="sig-name-line">
                <div class="sig-name">________________________</div>
                <div class="sig-position">OSCA Head / Approved By</div>
            </div>
        </div>
    </div>

</body>
</html>