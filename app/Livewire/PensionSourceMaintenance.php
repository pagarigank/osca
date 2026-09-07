<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PensionSource;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class PensionSourceMaintenance extends Component
{
    public $pension_sources;
    public $pension_source_id;
    public $name = '';
    public $is_active = true;
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadPensionSources();
    }

    public function loadPensionSources()
    {
        $this->pension_sources = PensionSource::all();
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
        $this->pension_source_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:pension_sources,name,' . $this->pension_source_id,
        ]);

        PensionSource::updateOrCreate(
            ['id' => $this->pension_source_id],
            [
                'name' => $this->name,
                'is_active' => $this->is_active
            ]
        );

        $this->dispatch('notify', message: $this->pension_source_id ? 'Pension Source updated successfully.' : 'Pension Source created successfully.');
        $this->closeModal();
        $this->loadPensionSources();
    }

    public function edit($id)
    {
        $pensionSource = PensionSource::findOrFail($id);
        $this->pension_source_id = $id;
        $this->name = $pensionSource->name;
        $this->is_active = $pensionSource->is_active;
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $pensionSource = PensionSource::findOrFail($id);
        $pensionSource->is_active = false;
        $pensionSource->save();
        
        $this->dispatch('notify', message: 'Pension Source has been deactivated (soft delete).');
        $this->loadPensionSources();
    }

    public function render()
    {
        return view('livewire.pension-source-maintenance');
    }
}
