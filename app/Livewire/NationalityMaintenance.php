<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Nationality;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class NationalityMaintenance extends Component
{
    public $nationalities;
    public $nationality_id;
    public $name = '';
    public $is_active = true;
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadNationalities();
    }

    public function loadNationalities()
    {
        $this->nationalities = Nationality::all();
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
        $this->nationality_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:nationalities,name,' . $this->nationality_id,
        ]);

        Nationality::updateOrCreate(
            ['id' => $this->nationality_id],
            [
                'name' => $this->name,
                'is_active' => $this->is_active
            ]
        );

        $this->dispatch('notify', message: $this->nationality_id ? 'Nationality updated successfully.' : 'Nationality created successfully.');
        $this->closeModal();
        $this->loadNationalities();
    }

    public function edit($id)
    {
        $nationality = Nationality::findOrFail($id);
        $this->nationality_id = $id;
        $this->name = $nationality->name;
        $this->is_active = $nationality->is_active;
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $nationality = Nationality::findOrFail($id);
        $nationality->is_active = false;
        $nationality->save();
        
        $this->dispatch('notify', message: 'Nationality has been deactivated (soft delete).');
        $this->loadNationalities();
    }

    public function render()
    {
        return view('livewire.nationality-maintenance');
    }
}
