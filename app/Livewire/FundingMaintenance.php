<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Funding;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class FundingMaintenance extends Component
{
    public $fundings;
    public $funding_id;
    public $provider = '';
    public $fund_source = '';
    public $amount = 0;
    public $effective_date = '';
    public $beneficiary = 0;
    public $total = 0;
    public $is_active = true;
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadFundings();
    }

    public function loadFundings()
    {
        $this->fundings = Funding::all();
    }

    public function updatedAmount()
    {
        $this->total = (float)$this->amount * (int)$this->beneficiary;
    }

    public function updatedBeneficiary()
    {
        $this->total = (float)$this->amount * (int)$this->beneficiary;
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
        $this->isEditMode = false;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->provider = '';
        $this->fund_source = '';
        $this->amount = 0;
        $this->effective_date = '';
        $this->beneficiary = 0;
        $this->total = 0;
        $this->is_active = true;
        $this->funding_id = null;
    }

    public function store()
    {
        $this->validate([
            'provider' => 'required',
            'fund_source' => 'required',
            'amount' => 'required|numeric',
            'effective_date' => 'required|date',
            'beneficiary' => 'required|integer',
        ]);

        $calculated_total = (float)$this->amount * (int)$this->beneficiary;

        Funding::updateOrCreate(
            ['id' => $this->funding_id],
            [
                'provider' => $this->provider,
                'fund_source' => $this->fund_source,
                'amount' => $this->amount,
                'effective_date' => $this->effective_date,
                'beneficiary' => $this->beneficiary,
                'total' => $calculated_total,
                'is_active' => $this->is_active
            ]
        );

        $this->dispatch('notify', message: $this->funding_id ? 'Funding Source updated successfully.' : 'Funding Source created successfully.');
        $this->closeModal();
        $this->loadFundings();
    }

    public function edit($id)
    {
        $funding = Funding::findOrFail($id);
        $this->funding_id = $id;
        $this->provider = $funding->provider;
        $this->fund_source = $funding->fund_source;
        $this->amount = $funding->amount;
        $this->effective_date = $funding->effective_date;
        $this->beneficiary = $funding->beneficiary;
        $this->total = $funding->total;
        $this->is_active = $funding->is_active;
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $funding = Funding::findOrFail($id);
        $funding->is_active = false;
        $funding->save();
        
        $this->dispatch('notify', message: 'Funding Source has been deactivated (soft delete).');
        $this->loadFundings();
    }

    public function render()
    {
        return view('livewire.funding-maintenance');
    }
}
