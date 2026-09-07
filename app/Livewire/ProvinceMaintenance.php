<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Province;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ProvinceMaintenance extends Component
{
    public $provinces;
    public $province_id;
    public $name = '';
    public $is_active = true;
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadProvinces();
    }

    public function loadProvinces()
    {
        $this->provinces = Province::all();
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
        $this->province_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:provinces,name,' . $this->province_id,
        ]);

        Province::updateOrCreate(
            ['id' => $this->province_id],
            [
                'name' => $this->name,
                'is_active' => $this->is_active
            ]
        );

        $this->dispatch('notify', message: $this->province_id ? 'Province updated successfully.' : 'Province created successfully.');
        $this->closeModal();
        $this->loadProvinces();
    }

    public function edit($id)
    {
        $province = Province::findOrFail($id);
        $this->province_id = $id;
        $this->name = $province->name;
        $this->is_active = $province->is_active;
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $province = Province::findOrFail($id);
        $province->is_active = false;
        $province->save();
        
        $this->dispatch('notify', message: 'Province has been deactivated (soft delete).');
        $this->loadProvinces();
    }

    public function render()
    {
        return view('livewire.province-maintenance');
    }
}
