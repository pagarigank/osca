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
     * Helper to get beneficiaries sorted and grouped up to 4 levels
     */
    private function getGroupedBeneficiaries($funding_id, Request $request = null)
    {
        $query = SeniorCitizenFunding::with(['seniorCitizen.barangay', 'seniorCitizen.gender', 'seniorCitizen.purokLeader'])
            ->where('funding_id', $funding_id)
            ->whereHas('seniorCitizen');

        // Optional filtering from request if passed (from payroll livewire)
        if ($request) {
            if ($request->barangay_id) {
                $query->whereHas('seniorCitizen', fn($q) => $q->where('barangay_id', $request->barangay_id));
            }
            if ($request->purok_id) {
                $query->whereHas('seniorCitizen', fn($q) => $q->where('purok_id', $request->purok_id));
            }
            if ($request->purok_leader_id) {
                $query->whereHas('seniorCitizen', fn($q) => $q->where('purok_leader_id', $request->purok_leader_id));
            }
        }

        $beneficiaries = $query->get()
            ->sortBy(function ($bf) {
                $sc = $bf->seniorCitizen;
                return ($sc->last_name ?? '') . ($sc->first_name ?? '');
            });

        // Group by City > Barangay > Purok > Purok Leader
        return $beneficiaries->groupBy([
            fn($bf) => $bf->seniorCitizen->city_municipality ?? 'Mabalacat City',
            fn($bf) => $bf->seniorCitizen->barangay ? $bf->seniorCitizen->barangay->name : 'Unassigned Barangay',
            fn($bf) => $bf->seniorCitizen->purok ?? 'Unassigned Purok',
            fn($bf) => $bf->seniorCitizen->purokLeader ? strtoupper($bf->seniorCitizen->purokLeader->last_name . ', ' . $bf->seniorCitizen->purokLeader->first_name) : 'UNASSIGNED LEADER',
        ]);
    }

    /**
     * Display the payroll report layout for a specific funding source.
     */
    public function printPayroll($funding_id, Request $request)
    {
        $funding = \App\Models\Funding::findOrFail($funding_id);
        $groupedBeneficiaries = $this->getGroupedBeneficiaries($funding_id, $request);

        return view('pages.print-payroll', compact('funding', 'groupedBeneficiaries'));
    }

    /**
     * Generate Dot Matrix friendly PDF for a specific funding source.
     */
    public function printPayrollPdf($funding_id, Request $request)
    {
        $funding = \App\Models\Funding::findOrFail($funding_id);
        $groupedBeneficiaries = $this->getGroupedBeneficiaries($funding_id, $request);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.print-payroll-pdf', compact('funding', 'groupedBeneficiaries'))
            ->setPaper('legal', 'landscape');
            
        return $pdf->stream('Payroll_Payout_Report_' . $funding_id . '.pdf');
    }

    /**
     * Display the payroll claim stubs layout for ALL beneficiaries of a funding source.
     * Each beneficiary gets an individual ID-card format stub with QR code.
     */
    public function printPayrollClaimStubs($funding_id, Request $request)
    {
        $funding = \App\Models\Funding::findOrFail($funding_id);
        
        // Use grouped to ensure they are sorted in drill-down order for printing
        $groupedBeneficiaries = $this->getGroupedBeneficiaries($funding_id, $request);
        
        // Flatten them back into a single list, but now they are inherently sorted by the 4 levels!
        $beneficiaries = collect();
        foreach ($groupedBeneficiaries as $city => $barangays) {
            foreach ($barangays as $barangay => $puroks) {
                foreach ($puroks as $purok => $leaders) {
                    foreach ($leaders as $leader => $bens) {
                        foreach ($bens as $ben) {
                            $beneficiaries->push($ben);
                        }
                    }
                }
            }
        }

        return view('pages.print-payroll-stubs', compact('funding', 'beneficiaries'));
    }

    /**
     * Display the payroll claim stub for a specific beneficiary.
     */
    public function printPayrollClaimStub($funding_id, $seniorCitizenId)
    {
        $funding = \App\Models\Funding::findOrFail($funding_id);
        $beneficiary = SeniorCitizenFunding::with(['seniorCitizen.barangay', 'seniorCitizen.gender', 'seniorCitizen.purokLeader', 'funding'])
            ->where('funding_id', $funding_id)
            ->where('senior_citizen_id', $seniorCitizenId)
            ->whereHas('seniorCitizen')
            ->firstOrFail();

        return view('pages.print-payroll-claim-stub-single', compact('funding', 'beneficiary'));
    }
}