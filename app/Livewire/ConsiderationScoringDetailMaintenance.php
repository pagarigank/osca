<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ConsiderationScoringDetail;
use App\Models\FactorConsideration;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ConsiderationScoringDetailMaintenance extends Component
{
    public $scoring_details;
    public $factors;
    
    public $scoring_id;
    public $factor_consideration_id = '';
    public $particular = '';
    public $guide_points = '';
    public $is_active = true;
    
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->scoring_details = ConsiderationScoringDetail::with('factorConsideration')->get();
        $this->factors = FactorConsideration::where('is_active', true)->get();
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
        $this->factor_consideration_id = '';
        $this->particular = '';
        $this->guide_points = '';
        $this->is_active = true;
        $this->scoring_id = null;
    }

    public function store()
    {
        $this->validate([
            'factor_consideration_id' => 'required|exists:factor_considerations,id',
            'particular' => 'required|string|max:255',
            'guide_points' => 'required|integer|min:0',
        ]);

        ConsiderationScoringDetail::updateOrCreate(
            ['id' => $this->scoring_id],
            [
                'factor_consideration_id' => $this->factor_consideration_id,
                'particular' => $this->particular,
                'guide_points' => $this->guide_points,
                'is_active' => $this->is_active
            ]
        );

        $this->dispatch('notify', message: $this->scoring_id ? 'Scoring Detail updated successfully.' : 'Scoring Detail created successfully.');
        $this->closeModal();
        $this->loadData();
    }

    public function edit($id)
    {
        $detail = ConsiderationScoringDetail::findOrFail($id);
        $this->scoring_id = $id;
        $this->factor_consideration_id = $detail->factor_consideration_id;
        $this->particular = $detail->particular;
        $this->guide_points = $detail->guide_points;
        $this->is_active = $detail->is_active;
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $detail = ConsiderationScoringDetail::findOrFail($id);
        $detail->is_active = false;
        $detail->save();
        
        $this->dispatch('notify', message: 'Scoring Detail has been deactivated (soft delete).');
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.consideration-scoring-detail-maintenance');
    }
}
