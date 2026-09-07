<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FactorConsideration;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class FactorConsiderationMaintenance extends Component
{
    public $factor_considerations;
    public $factor_id;
    public $name = '';
    public $is_active = true;
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadFactorConsiderations();
    }

    public function loadFactorConsiderations()
    {
        $this->factor_considerations = FactorConsideration::all();
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
        $this->name = '';
        $this->is_active = true;
        $this->factor_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:factor_considerations,name,' . $this->factor_id,
        ]);

        FactorConsideration::updateOrCreate(
            ['id' => $this->factor_id],
            [
                'name' => $this->name,
                'is_active' => $this->is_active
            ]
        );

        $this->dispatch('notify', message: $this->factor_id ? 'Factor for Consideration updated successfully.' : 'Factor for Consideration created successfully.');
        $this->closeModal();
        $this->loadFactorConsiderations();
    }

    public function edit($id)
    {
        $factor = FactorConsideration::findOrFail($id);
        $this->factor_id = $id;
        $this->name = $factor->name;
        $this->is_active = $factor->is_active;
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $factor = FactorConsideration::findOrFail($id);
        $factor->is_active = false;
        $factor->save();
        
        $this->dispatch('notify', message: 'Factor for Consideration has been deactivated (soft delete).');
        $this->loadFactorConsiderations();
    }

    public function render()
    {
        return view('livewire.factor-consideration-maintenance');
    }
}
