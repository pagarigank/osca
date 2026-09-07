<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\OtherIncome;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class OtherIncomeMaintenance extends Component
{
    public $other_incomes;
    public $other_income_id;
    public $name = '';
    public $is_active = true;
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadOtherIncomes();
    }

    public function loadOtherIncomes()
    {
        $this->other_incomes = OtherIncome::all();
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
        $this->other_income_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:other_incomes,name,' . $this->other_income_id,
        ]);

        OtherIncome::updateOrCreate(
            ['id' => $this->other_income_id],
            [
                'name' => $this->name,
                'is_active' => $this->is_active
            ]
        );

        $this->dispatch('notify', message: $this->other_income_id ? 'Other Income Source updated successfully.' : 'Other Income Source created successfully.');
        $this->closeModal();
        $this->loadOtherIncomes();
    }

    public function edit($id)
    {
        $income = OtherIncome::findOrFail($id);
        $this->other_income_id = $id;
        $this->name = $income->name;
        $this->is_active = $income->is_active;
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $income = OtherIncome::findOrFail($id);
        $income->is_active = false;
        $income->save();
        
        $this->dispatch('notify', message: 'Other Income Source has been deactivated (soft delete).');
        $this->loadOtherIncomes();
    }

    public function render()
    {
        return view('livewire.other-income-maintenance');
    }
}
