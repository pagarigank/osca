<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AccessLevel;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class AccessLevelMaintenance extends Component
{
    public $access_levels;
    public $access_level_id;
    public $name = '';
    public $is_active = true;
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadAccessLevels();
    }

    public function loadAccessLevels()
    {
        $this->access_levels = AccessLevel::all();
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
        $this->access_level_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:access_levels,name,' . $this->access_level_id,
        ]);

        AccessLevel::updateOrCreate(
            ['id' => $this->access_level_id],
            [
                'name' => $this->name,
                'is_active' => $this->is_active
            ]
        );

        $this->dispatch('notify', message: $this->access_level_id ? 'Access Level updated successfully.' : 'Access Level created successfully.');
        $this->closeModal();
        $this->loadAccessLevels();
    }

    public function edit($id)
    {
        $access_level = AccessLevel::findOrFail($id);
        $this->access_level_id = $id;
        $this->name = $access_level->name;
        $this->is_active = $access_level->is_active;
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $access_level = AccessLevel::findOrFail($id);
        $access_level->is_active = false;
        $access_level->save();
        
        $this->dispatch('notify', message: 'Access Level has been deactivated (soft delete).');
        $this->loadAccessLevels();
    }

    public function render()
    {
        return view('livewire.access-level-maintenance');
    }
}
