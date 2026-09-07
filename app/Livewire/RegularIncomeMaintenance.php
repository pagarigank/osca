<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\RegularIncome;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class RegularIncomeMaintenance extends Component
{
    public $regularIncomes;
    public $regular_income_id;
    public $name = '';
    public $is_active = true;
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadRegularIncomes();
    }

    public function loadRegularIncomes()
    {
        $this->regularIncomes = RegularIncome::all();
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
        $this->regular_income_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:regular_incomes,name,' . $this->regular_income_id,
        ]);

        RegularIncome::updateOrCreate(
            ['id' => $this->regular_income_id],
            [
                'name' => $this->name,
                'is_active' => $this->is_active
            ]
        );

        $this->dispatch('notify', message: $this->regular_income_id ? 'Regular Income updated successfully.' : 'Regular Income created successfully.');
        $this->closeModal();
        $this->loadRegularIncomes();
    }

    public function edit($id)
    {
        $regularIncome = RegularIncome::findOrFail($id);
        $this->regular_income_id = $id;
        $this->name = $regularIncome->name;
        $this->is_active = $regularIncome->is_active;
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $regularIncome = RegularIncome::findOrFail($id);
        $regularIncome->is_active = false;
        $regularIncome->save();
        
        $this->dispatch('notify', message: 'Regular Income has been deactivated (soft delete).');
        $this->loadRegularIncomes();
    }

    public function render()
    {
        return view('livewire.regular-income-maintenance');
    }
}
