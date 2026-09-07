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
            --light:  #f5f5f5;
            --border: #aaaaaa;
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
            font-size: 12pt;
            color: #333;
            font-weight: 600;
            text-align: center;
            margin-bottom: 10mm;
        }

        /* Each stub on its own page */
        @media print {
            body {
                background: none;
                padding: 0;
                margin: 0;
            }
            
            .screen-label {
                display: none;
            }
            
            .stub-wrapper {
                page-break-before: always;
            }
            
            .stub-wrapper:first-child {
                page-break-before: avoid;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="screen-label">Payroll Claim Stubs - {{ $funding->provider }}</div>
    
    @foreach($beneficiaries as $ben)
    <div class="stub-wrapper">
        @include('pages.print-payroll-claim-stub', ['beneficiary' => $ben, 'funding' => $funding])
    </div>
    @endforeach

</body>
</html>