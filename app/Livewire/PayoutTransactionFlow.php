<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\PayoutCutoff;
use App\Models\PayoutTransaction;
use App\Models\SeniorCitizenFunding;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class PayoutTransactionFlow extends Component
{
    use WithFileUploads;

    public $cutoff;
    public $beneficiaries;
    
    public $isModalOpen = false;
    public $activeBeneficiary = null;
    
    // Form fields
    public $amount;
    public $proof_upload;
    public $webcam_image_base64;

    public function mount($id)
    {
        $this->cutoff = PayoutCutoff::with('funding')->findOrFail($id);
        $this->loadBeneficiaries();
    }

    public function loadBeneficiaries()
    {
        // Get all active senior citizens assigned to this funding
        $this->beneficiaries = SeniorCitizenFunding::with('seniorCitizen')
            ->where('funding_id', $this->cutoff->funding_id)
            ->where('is_active', true)
            ->get()
            ->map(function ($scf) {
                // Attach the transaction state for this cutoff
                $transaction = PayoutTransaction::where('payout_cutoff_id', $this->cutoff->id)
                    ->where('senior_citizen_funding_id', $scf->id)
                    ->first();
                
                $scf->transaction = $transaction;
                return $scf;
            });
    }

    public function openTransactionModal($scfId)
    {
        if ($this->cutoff->status !== 'OPEN') {
            $this->dispatch('notify', message: 'Transactions cannot be processed while cutoff is ' . $this->cutoff->status);
            return;
        }

        $this->resetValidation();
        $this->activeBeneficiary = $this->beneficiaries->firstWhere('id', $scfId);
        $this->amount = $this->cutoff->funding->amount ?? 0;
        $this->proof_upload = null;
        $this->webcam_image_base64 = null;
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->activeBeneficiary = null;
    }

    public function processTransaction()
    {
        $this->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $proofPath = null;
        
        // Handle webcam capture
        if ($this->webcam_image_base64) {
            $imageParts = explode(";base64,", $this->webcam_image_base64);
            if (count($imageParts) == 2) {
                $imageTypeAux = explode("image/", $imageParts[0]);
                $imageType = $imageTypeAux[1];
                $imageBase64 = base64_decode($imageParts[1]);
                $fileName = 'payout_' . $this->cutoff->id . '_' . $this->activeBeneficiary->seniorCitizen->id_number . '_' . time() . '.' . $imageType;
                
                \Illuminate\Support\Facades\Storage::disk('public')->put('payout_proofs/' . $fileName, $imageBase64);
                $proofPath = 'payout_proofs/' . $fileName;
            }
        } 
        // Handle file upload fallback
        elseif ($this->proof_upload) {
            $proofPath = $this->proof_upload->store('payout_proofs', 'public');
        }

        PayoutTransaction::updateOrCreate(
            [
                'payout_cutoff_id' => $this->cutoff->id,
                'senior_citizen_funding_id' => $this->activeBeneficiary->id,
            ],
            [
                'amount' => $this->amount,
                'received_date' => now(),
                'proof_path' => $proofPath,
                'is_received' => true,
                'is_active' => true,
            ]
        );

        $this->dispatch('notify', message: 'Payout recorded successfully for ' . $this->activeBeneficiary->seniorCitizen->first_name);
        $this->closeModal();
        $this->loadBeneficiaries();
    }

    public function render()
    {
        return view('livewire.payout-transaction-flow');
    }
}
