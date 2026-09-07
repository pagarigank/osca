<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Office;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class OfficeMaintenance extends Component
{
    public $offices;
    public $office_id;
    public $name = '';
    public $is_active = true;
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadOffices();
    }

    public function loadOffices()
    {
        $this->offices = Office::all();
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
        $this->office_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:offices,name,' . $this->office_id,
        ]);

        Office::updateOrCreate(
            ['id' => $this->office_id],
            [
                'name' => $this->name,
                'is_active' => $this->is_active
            ]
        );

        $this->dispatch('notify', message: $this->office_id ? 'Office updated successfully.' : 'Office created successfully.');
        $this->closeModal();
        $this->loadOffices();
    }

    public function edit($id)
    {
        $office = Office::findOrFail($id);
        $this->office_id = $id;
        $this->name = $office->name;
        $this->is_active = $office->is_active;
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $office = Office::findOrFail($id);
        $office->is_active = false;
        $office->save();
        
        $this->dispatch('notify', message: 'Office has been deactivated (soft delete).');
        $this->loadOffices();
    }

    public function render()
    {
        return view('livewire.office-maintenance');
    }
}
