<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSCA Payroll Claim Stubs - {{ $funding->provider }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Special+Elite&family=Inter:wght@300;400;500;600;700&display=swap');

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

        .print-line {
            width: 80%;
            border-top: 1px dashed var(--gray);
            margin: 8mm auto;
        }

        @media print {
            body {
                background: none;
                padding: 0;
                margin: 0;
            }
            .screen-label {
                display: none;
            }
            .print-line {
                width: 80%;
                border-top: 1px dashed #999;
                margin: 5mm auto;
            }
        }
    </style>
</head>
<body>

    <div class="screen-label">Payroll Claim Stubs - {{ $funding->provider }}</div>
    
    @foreach($beneficiaries as $index => $ben)
        @include('pages.print-payroll-claim-stub', ['beneficiary' => $ben, 'funding' => $funding])
        @if(!$loop->last)
            <div class="print-line"></div>
        @endif
    @endforeach

</body>
</html>