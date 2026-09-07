<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Funding;
use App\Models\SeniorCitizenFunding;
use App\Models\Barangay;
use App\Models\Purok;
use App\Models\PurokLeader;
use Livewire\Attributes\Layout;
use Illuminate\Support\Collection;

#[Layout('layouts.app')]
class Payroll extends Component
{
    public $fundings = [];
    public $selected_funding_id = null;
    
    // Filter properties
    public $selected_barangay_id = null;
    public $selected_purok_id = null;
    public $selected_purok_leader_id = null;
    
    public Collection $barangays;
    public Collection $puroks;
    public Collection $purokLeaders;

    public function mount()
    {
        $this->fundings = Funding::where('is_active', true)->get();
        $this->barangays = collect();
        $this->puroks = collect();
        $this->purokLeaders = collect();
        
        if ($this->fundings->isNotEmpty()) {
            $this->selected_funding_id = $this->fundings->first()->id;
        }
        
        $this->barangays = Barangay::where('is_active', true)->get();
    }

    public function updatedSelectedFundingId()
    {
        $this->selected_barangay_id = null;
        $this->selected_purok_id = null;
        $this->selected_purok_leader_id = null;
        $this->puroks = collect();
        $this->purokLeaders = collect();
    }

    public function updatedSelectedBarangayId()
    {
        $this->selected_purok_id = null;
        $this->selected_purok_leader_id = null;
        
        if ($this->selected_barangay_id) {
            $this->puroks = Purok::where('barangay_id', $this->selected_barangay_id)
                ->where('is_active', true)
                ->get();
        } else {
            $this->puroks = collect();
        }
        $this->purokLeaders = collect();
    }

    public function updatedSelectedPurokId()
    {
        $this->selected_purok_leader_id = null;
        
        if ($this->selected_purok_id) {
            $this->purokLeaders = PurokLeader::where('purok_id', $this->selected_purok_id)
                ->where('is_active', true)
                ->get();
        } else {
            $this->purokLeaders = collect();
        }
    }

    public function getBeneficiariesProperty()
    {
        $query = SeniorCitizenFunding::with('seniorCitizen.barangay', 'funding');
        
        if ($this->selected_funding_id) {
            $query->where('funding_id', $this->selected_funding_id);
            
            // Apply barangay filter
            if ($this->selected_barangay_id) {
                $query->whereHas('seniorCitizen', function ($q) {
                    $q->where('barangay_id', $this->selected_barangay_id);
                });
            }
            
            // Apply purok filter  
            if ($this->selected_purok_id) {
                $query->whereHas('seniorCitizen', function ($q) {
                    $q->where('purok_id', $this->selected_purok_id);
                });
            }
            
            // Apply purok leader filter (via purok_leader_id on senior_citizens)
            if ($this->selected_purok_leader_id) {
                $query->whereHas('seniorCitizen', function ($q) {
                    $q->where('purok_leader_id', $this->selected_purok_leader_id);
                });
            }
            
            return $query->whereHas('seniorCitizen')->get();
        }
        return collect();
    }

    public function getSelectedFundingProperty()
    {
        if ($this->selected_funding_id) {
            return Funding::find($this->selected_funding_id);
        }
        return null;
    }

    public function render()
    {
        $beneficiaries   = $this->beneficiaries;
        $selectedFunding = $this->selectedFunding;

        $totalSlots     = $selectedFunding ? (int) $selectedFunding->beneficiary : 0;
        $occupiedSlots  = $beneficiaries->count();
        $availableSlots = max(0, $totalSlots - $occupiedSlots);

        return view('livewire.payroll', [
            'beneficiaries'   => $beneficiaries,
            'selectedFunding' => $selectedFunding,
            'totalSlots'      => $totalSlots,
            'occupiedSlots'   => $occupiedSlots,
            'availableSlots'  => $availableSlots,
            'barangays'       => $this->barangays,
            'puroks'          => $this->puroks,
            'purokLeaders'    => $this->purokLeaders,
        ]);
    }
}