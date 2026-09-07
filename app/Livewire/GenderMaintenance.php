<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Gender;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class GenderMaintenance extends Component
{
    public $genders;
    public $gender_id;
    public $name = '';
    public $is_active = true;
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadGenders();
    }

    public function loadGenders()
    {
        $this->genders = Gender::all();
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
        $this->gender_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:genders,name,' . $this->gender_id,
        ]);

        Gender::updateOrCreate(
            ['id' => $this->gender_id],
            [
                'name' => $this->name,
                'is_active' => $this->is_active
            ]
        );

        $this->dispatch('notify', message: $this->gender_id ? 'Gender updated successfully.' : 'Gender created successfully.');
        $this->closeModal();
        $this->loadGenders();
    }

    public function edit($id)
    {
        $gender = Gender::findOrFail($id);
        $this->gender_id = $id;
        $this->name = $gender->name;
        $this->is_active = $gender->is_active;
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $gender = Gender::findOrFail($id);
        $gender->is_active = false;
        $gender->save();
        
        $this->dispatch('notify', message: 'Gender has been deactivated (soft delete).');
        $this->loadGenders();
    }

    public function render()
    {
        return view('livewire.gender-maintenance');
    }
}
