<?php

namespace App\Http\Controllers;

use App\Models\PayoutCutoff;
use App\Models\PayoutTransaction;
use App\Models\SeniorCitizenFunding;

class PayoutController extends Controller
{
    public function index()
    {
        $cutoffs = PayoutCutoff::with(['funding', 'transactions.seniorCitizenFunding.seniorCitizen'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.payout-cutoff', compact('cutoffs'));
    }
}
