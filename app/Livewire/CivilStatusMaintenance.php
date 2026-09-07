<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CivilStatus;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CivilStatusMaintenance extends Component
{
    public $civil_statuses;
    public $civil_status_id;
    public $name = '';
    public $is_active = true;
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadCivilStatuses();
    }

    public function loadCivilStatuses()
    {
        $this->civil_statuses = CivilStatus::all();
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
        $this->civil_status_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:civil_statuses,name,' . $this->civil_status_id,
        ]);

        CivilStatus::updateOrCreate(
            ['id' => $this->civil_status_id],
            [
                'name' => $this->name,
                'is_active' => $this->is_active
            ]
        );

        $this->dispatch('notify', message: $this->civil_status_id ? 'Civil Status updated successfully.' : 'Civil Status created successfully.');
        $this->closeModal();
        $this->loadCivilStatuses();
    }

    public function edit($id)
    {
        $civil_status = CivilStatus::findOrFail($id);
        $this->civil_status_id = $id;
        $this->name = $civil_status->name;
        $this->is_active = $civil_status->is_active;
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $civil_status = CivilStatus::findOrFail($id);
        $civil_status->is_active = false;
        $civil_status->save();
        
        $this->dispatch('notify', message: 'Civil Status has been deactivated (soft delete).');
        $this->loadCivilStatuses();
    }

    public function render()
    {
        return view('livewire.civil-status-maintenance');
    }
}
