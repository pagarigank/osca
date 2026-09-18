<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payroll Payout Report</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #000;
        }
        .header h2 { margin: 0 0 5px; font-size: 14px; }
        .header h3 { margin: 0 0 5px; font-size: 12px; }
        .header p { margin: 0; font-size: 11px; }

        .city-group { margin-top: 15px; }
        .city-title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .brgy-group { margin-left: 10px; margin-top: 10px; }
        .brgy-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .purok-group { margin-left: 20px; margin-top: 10px; }
        .purok-title {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .leader-group { margin-left: 30px; margin-top: 5px; margin-bottom: 15px; }
        .leader-title {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }
        th { font-weight: bold; }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    @php 
        $globalCounter = 1;
        $grandTotalAmount = 0;
    @endphp

    <div class="header">
        <h2>OFFICE FOR SENIOR CITIZENS AFFAIRS (OSCA)</h2>
        <h3>MABALACAT CITY PAYROLL REPORT</h3>
        <p>FUNDING: {{ strtoupper($funding->provider) }} - {{ strtoupper($funding->fund_source) }}</p>
        <p>AMOUNT/BENEFICIARY: PHP {{ number_format($funding->amount, 2) }}</p>
    </div>

    @forelse($groupedBeneficiaries as $city => $barangays)
        <div class="city-group">
            <div class="city-title">CITY: {{ strtoupper($city) }}</div>
            
            @foreach($barangays as $barangay => $puroks)
                <div class="brgy-group">
                    <div class="brgy-title">BARANGAY: {{ strtoupper($barangay) }}</div>
                    
                    @foreach($puroks as $purok => $leaders)
                        <div class="purok-group">
                            <div class="purok-title">PUROK: {{ strtoupper($purok) }}</div>
                            
                            @foreach($leaders as $leader => $beneficiaries)
                                <div class="leader-group">
                                    <div class="leader-title">PUROK LEADER: {{ strtoupper($leader) }}</div>
                                    
                                    <table>
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center">#</th>
                                                <th width="15%">ID NO.</th>
                                                <th width="35%">NAME OF BENEFICIARY</th>
                                                <th width="10%">GENDER</th>
                                                <th width="15%" class="text-right">AMOUNT(PHP)</th>
                                                <th width="20%">SIGNATURE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $groupTotal = 0; @endphp
                                            @foreach($beneficiaries as $bf)
                                                @php 
                                                    $amount = $bf->funding->amount ?? $funding->amount ?? 0;
                                                    $groupTotal += $amount;
                                                    $grandTotalAmount += $amount;
                                                @endphp
                                                <tr>
                                                    <td class="text-center">{{ $globalCounter++ }}</td>
                                                    <td>{{ $bf->seniorCitizen->id_number ?? $bf->seniorCitizen->new_id_number }}</td>
                                                    <td>
                                                        {{ strtoupper($bf->seniorCitizen->last_name) }}{{ $bf->seniorCitizen->suffix ? ', '.$bf->seniorCitizen->suffix : '' }},
                                                        {{ strtoupper($bf->seniorCitizen->first_name) }}
                                                        {{ $bf->seniorCitizen->middle_name ? strtoupper(substr($bf->seniorCitizen->middle_name, 0, 1)).'.' : '' }}
                                                    </td>
                                                    <td>{{ $bf->seniorCitizen->gender->name ?? 'N/A' }}</td>
                                                    <td class="text-right">{{ number_format($amount, 2) }}</td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-right font-bold">SUBTOTAL ({{ count($beneficiaries) }} pax)</td>
                                                <td class="text-right font-bold">{{ number_format($groupTotal, 2) }}</td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @empty
        <div class="text-center" style="margin-top: 30px;">
            <p>No beneficiaries found for this funding.</p>
        </div>
    @endforelse

    @if($groupedBeneficiaries->count() > 0)
        <div style="margin-top: 20px; text-align: right; font-size: 12px; font-weight: bold; border-top: 1px dashed #000; padding-top: 10px;">
            GRAND TOTAL ({{ $globalCounter - 1 }} pax): PHP {{ number_format($grandTotalAmount, 2) }}
        </div>
        
        <table style="margin-top: 40px; border: none; width: 100%;">
            <tr>
                <td style="border: none; text-align: center; width: 33%;">
                    _________________________<br>
                    Prepared By
                </td>
                <td style="border: none; text-align: center; width: 33%;">
                    _________________________<br>
                    Verified By
                </td>
                <td style="border: none; text-align: center; width: 33%;">
                    _________________________<br>
                    Approved By
                </td>
            </tr>
        </table>
    @endif

</body>
</html>
