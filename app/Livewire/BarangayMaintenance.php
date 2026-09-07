<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Barangay;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class BarangayMaintenance extends Component
{
    public $barangays;
    public $barangay_id;
    public $name = '';
    public $prefix = '';
    public $id_sequence = 0;
    public $pay_type = '';
    public $is_active = true;
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadBarangays();
    }

    public function loadBarangays()
    {
        $this->barangays = Barangay::all();
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
        $this->prefix = '';
        $this->id_sequence = 0;
        $this->pay_type = '';
        $this->is_active = true;
        $this->barangay_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:barangays,name,' . $this->barangay_id,
            'prefix' => 'nullable|string|max:50',
            'id_sequence' => 'nullable|integer',
            'pay_type' => 'nullable|string|max:50',
        ]);

        Barangay::updateOrCreate(
            ['id' => $this->barangay_id],
            [
                'name' => $this->name,
                'prefix' => $this->prefix,
                'id_sequence' => $this->id_sequence ?: 0,
                'pay_type' => $this->pay_type,
                'is_active' => $this->is_active
            ]
        );

        $this->dispatch('notify', message: $this->barangay_id ? 'Barangay updated successfully.' : 'Barangay created successfully.');
        $this->closeModal();
        $this->loadBarangays();
    }

    public function edit($id)
    {
        $barangay = Barangay::findOrFail($id);
        $this->barangay_id = $id;
        $this->name = $barangay->name;
        $this->prefix = $barangay->prefix;
        $this->id_sequence = $barangay->id_sequence;
        $this->pay_type = $barangay->pay_type;
        $this->is_active = $barangay->is_active;
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $barangay = Barangay::findOrFail($id);
        $barangay->is_active = false;
        $barangay->save();
        
        $this->dispatch('notify', message: 'Barangay has been deactivated (soft delete).');
        $this->loadBarangays();
    }

    public function render()
    {
        return view('livewire.barangay-maintenance');
    }
}
