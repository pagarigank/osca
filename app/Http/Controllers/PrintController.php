<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SeniorCitizen;
use App\Models\SeniorCitizenFunding;
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

    /**
     * Display the payroll claim stubs layout for ALL beneficiaries of a funding source.
     * Each beneficiary gets an individual ID-card format stub with QR code.
     */
    public function printPayrollClaimStubs($funding_id)
    {
        $funding = \App\Models\Funding::findOrFail($funding_id);
        $beneficiaries = SeniorCitizenFunding::with('seniorCitizen.barangay', 'seniorCitizen.gender')
            ->where('funding_id', $funding_id)
            ->whereHas('seniorCitizen')
            ->get();

        return view('pages.print-payroll-stubs', compact('funding', 'beneficiaries'));
    }

    /**
     * Display the payroll claim stub for a specific beneficiary.
     * This format combines the front of the ID with a larger QR code
     * for verification purposes.
     */
    public function printPayrollClaimStub($funding_id, $seniorCitizenId)
    {
        $funding = \App\Models\Funding::findOrFail($funding_id);
        $beneficiary = SeniorCitizenFunding::with('seniorCitizen.barangay', 'seniorCitizen.gender', 'funding')
            ->where('funding_id', $funding_id)
            ->where('senior_citizen_id', $seniorCitizenId)
            ->whereHas('seniorCitizen')
            ->firstOrFail();

        return view('pages.print-payroll-claim-stub', compact('funding', 'beneficiary'));
    }
}