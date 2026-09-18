<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PayoutCutoff;
use App\Models\Funding;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class PayoutCutoffMaintenance extends Component
{
    use WithPagination;

    public $cutoffs;
    public $cutoff_id;
    public $funding_id;
    public $from_date;
    public $to_date;
    public $status = 'OPEN';
    public $isModalOpen = false;
    public $isEditMode = false;

    public $fundings;

    public function mount()
    {
        $this->fundings = Funding::where('is_active', true)->get();
        $this->loadCutoffs();
    }

    public function loadCutoffs()
    {
        $this->cutoffs = PayoutCutoff::with('funding')->orderBy('created_at', 'desc')->get();
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
        $this->funding_id = null;
        $this->from_date = '';
        $this->to_date = '';
        $this->status = 'OPEN';
        $this->cutoff_id = null;
    }

    public function store()
    {
        $this->validate([
            'funding_id' => 'required|exists:fundings,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'status' => 'required|in:OPEN,FREEZE,CLOSE',
        ]);

        PayoutCutoff::updateOrCreate(
            ['id' => $this->cutoff_id],
            [
                'funding_id' => $this->funding_id,
                'from_date' => $this->from_date,
                'to_date' => $this->to_date,
                'status' => $this->status,
                'is_active' => true,
            ]
        );

        $this->dispatch('notify', message: $this->cutoff_id ? 'Payout Cutoff updated successfully.' : 'Payout Cutoff created successfully.');
        $this->closeModal();
        $this->loadCutoffs();
    }

    public function edit($id)
    {
        $cutoff = PayoutCutoff::findOrFail($id);
        $this->cutoff_id = $id;
        $this->funding_id = $cutoff->funding_id;
        $this->from_date = $cutoff->from_date->format('Y-m-d');
        $this->to_date = $cutoff->to_date->format('Y-m-d');
        $this->status = $cutoff->status;
        
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $cutoff = PayoutCutoff::findOrFail($id);
        $cutoff->is_active = false;
        $cutoff->save();
        
        $this->dispatch('notify', message: 'Payout Cutoff has been deactivated (soft delete).');
        $this->loadCutoffs();
    }

    public function render()
    {
        return view('livewire.payout-cutoff-maintenance');
    }
}
