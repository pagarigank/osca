<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Diagnosis;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class DiagnosisMaintenance extends Component
{
    public $diagnoses;
    public $diagnosis_id;
    public $generic_name = '';
    public $icd10_code = '';
    public $icd10_short_name = '';
    public $icd10_long_name = '';
    public $is_active = true;
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadDiagnoses();
    }

    public function loadDiagnoses()
    {
        $this->diagnoses = Diagnosis::all();
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
        $this->generic_name = '';
        $this->icd10_code = '';
        $this->icd10_short_name = '';
        $this->icd10_long_name = '';
        $this->is_active = true;
        $this->diagnosis_id = null;
    }

    public function store()
    {
        $this->validate([
            'generic_name' => 'required|string|max:255',
            'icd10_code' => 'nullable|string|max:50',
            'icd10_short_name' => 'nullable|string|max:255',
            'icd10_long_name' => 'nullable|string|max:500',
        ]);

        Diagnosis::updateOrCreate(
            ['id' => $this->diagnosis_id],
            [
                'generic_name' => $this->generic_name,
                'icd10_code' => $this->icd10_code,
                'icd10_short_name' => $this->icd10_short_name,
                'icd10_long_name' => $this->icd10_long_name,
                'is_active' => $this->is_active
            ]
        );

        $this->dispatch('notify', message: $this->diagnosis_id ? 'Diagnosis updated successfully.' : 'Diagnosis created successfully.');
        $this->closeModal();
        $this->loadDiagnoses();
    }

    public function edit($id)
    {
        $diagnosis = Diagnosis::findOrFail($id);
        $this->diagnosis_id = $id;
        $this->generic_name = $diagnosis->generic_name;
        $this->icd10_code = $diagnosis->icd10_code;
        $this->icd10_short_name = $diagnosis->icd10_short_name;
        $this->icd10_long_name = $diagnosis->icd10_long_name;
        $this->is_active = $diagnosis->is_active;
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $diagnosis = Diagnosis::findOrFail($id);
        $diagnosis->is_active = false;
        $diagnosis->save();
        
        $this->dispatch('notify', message: 'Diagnosis has been deactivated (soft delete).');
        $this->loadDiagnoses();
    }

    public function render()
    {
        return view('livewire.diagnosis-maintenance');
    }
}
