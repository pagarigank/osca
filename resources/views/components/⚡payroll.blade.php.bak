<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Report - {{ $funding->provider }} {{ $funding->fund_source }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0 0 5px;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            font-size: 14px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section strong {
            display: inline-block;
            width: 120px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 200px;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
            @page { margin: 1cm; size: portrait; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px; cursor: pointer; background: #2563eb; color: white; border: none; border-radius: 4px;">Print Now</button>
    </div>

    <div class="header">
        <h1>Office of the Senior Citizens Affairs (OSCA)</h1>
        <p>Payroll Report for Beneficiaries</p>
    </div>

    <div class="info-section">
        <div><strong>Provider:</strong> {{ $funding->provider }}</div>
        <div><strong>Fund Source:</strong> {{ $funding->fund_source }}</div>
        <div><strong>Effective Date:</strong> {{ $funding->effective_date ? \Carbon\Carbon::parse($funding->effective_date)->format('F d, Y') : 'N/A' }}</div>
        <div><strong>Base Amount:</strong> Php {{ number_format($funding->amount, 2) }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50px;" class="text-center">#</th>
                <th style="width: 150px;">ID Number</th>
                <th>Name</th>
                <th>Barangay</th>
                <th style="width: 100px;" class="text-right">Amount</th>
                <th style="width: 150px;" class="text-center">Signature</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAmount = 0; @endphp
            @forelse($beneficiaries as $index => $ben)
                @php $amount = $ben->funding->amount ?? 0; $totalAmount += $amount; @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $ben->seniorCitizen->id_number ?? $ben->seniorCitizen->new_id_number }}</td>
                    <td>{{ strtoupper($ben->seniorCitizen->last_name) }}, {{ strtoupper($ben->seniorCitizen->first_name) }} {{ $ben->seniorCitizen->middle_name }}</td>
                    <td>{{ $ben->seniorCitizen->barangay->name ?? 'N/A' }}</td>
                    <td class="text-right">{{ number_format($amount, 2) }}</td>
                    <td></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No beneficiaries found for this funding source.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">TOTAL:</th>
                <th class="text-right">{{ number_format($totalAmount, 2) }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div class="signature-box">
            <div class="signature-line">Prepared By</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">Verified By</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">Approved By</div>
        </div>
    </div>

</body>
</html>