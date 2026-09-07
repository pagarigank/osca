<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LivingCondition;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class LivingConditionMaintenance extends Component
{
    public $living_conditions;
    public $living_condition_id;
    public $name = '';
    public $is_active = true;
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadLivingConditions();
    }

    public function loadLivingConditions()
    {
        $this->living_conditions = LivingCondition::all();
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
        $this->living_condition_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:living_conditions,name,' . $this->living_condition_id,
        ]);

        LivingCondition::updateOrCreate(
            ['id' => $this->living_condition_id],
            [
                'name' => $this->name,
                'is_active' => $this->is_active
            ]
        );

        $this->dispatch('notify', message: $this->living_condition_id ? 'Living Condition updated successfully.' : 'Living Condition created successfully.');
        $this->closeModal();
        $this->loadLivingConditions();
    }

    public function edit($id)
    {
        $living_condition = LivingCondition::findOrFail($id);
        $this->living_condition_id = $id;
        $this->name = $living_condition->name;
        $this->is_active = $living_condition->is_active;
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $living_condition = LivingCondition::findOrFail($id);
        $living_condition->is_active = false;
        $living_condition->save();
        
        $this->dispatch('notify', message: 'Living Condition has been deactivated (soft delete).');
        $this->loadLivingConditions();
    }

    public function render()
    {
        return view('livewire.living-condition-maintenance');
    }
}
