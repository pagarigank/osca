<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SeniorCitizen;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Common\EccLevel;

class PrintController extends Controller
{
    /**
     * Display the ID printing layout for a specific senior citizen.
     */
    public function printId($id)
    {
        $sc = SeniorCitizen::with(['gender', 'barangay'])->findOrFail($id);

        // Generate Base64 QR Code for Verification
        $qrBase64 = null;
        if ($sc->qr_token) {
            $qrOptions = new QROptions([
                'eccLevel'       => EccLevel::H,
                'outputBase64'   => true,
                'addQuietzone'   => true,
                'quietzoneSize'  => 4,
            ]);
            $qrBase64 = (new QRCode($qrOptions))->render(url('/verify/' . $sc->qr_token));
        }

        return view('pages.print-id', compact('sc', 'qrBase64'));
    }

    /**
     * Display the payroll report layout for a specific funding source.
     */
    public function printPayroll($funding_id)
    {
        $funding = \App\Models\Funding::findOrFail($funding_id);
        $beneficiaries = \App\Models\SeniorCitizenFunding::with('seniorCitizen.barangay')
            ->where('funding_id', $funding_id)
            ->whereHas('seniorCitizen')
            ->get();

        return view('pages.print-payroll', compact('funding', 'beneficiaries'));
    }
}
