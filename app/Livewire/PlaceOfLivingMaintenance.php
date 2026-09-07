<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PlaceOfLiving;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class PlaceOfLivingMaintenance extends Component
{
    public $places_of_living;
    public $place_of_living_id;
    public $name = '';
    public $is_active = true;
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadPlacesOfLiving();
    }

    public function loadPlacesOfLiving()
    {
        $this->places_of_living = PlaceOfLiving::all();
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
        $this->place_of_living_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:place_of_livings,name,' . $this->place_of_living_id,
        ]);

        PlaceOfLiving::updateOrCreate(
            ['id' => $this->place_of_living_id],
            [
                'name' => $this->name,
                'is_active' => $this->is_active
            ]
        );

        $this->dispatch('notify', message: $this->place_of_living_id ? 'Place of Living updated successfully.' : 'Place of Living created successfully.');
        $this->closeModal();
        $this->loadPlacesOfLiving();
    }

    public function edit($id)
    {
        $place = PlaceOfLiving::findOrFail($id);
        $this->place_of_living_id = $id;
        $this->name = $place->name;
        $this->is_active = $place->is_active;
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $place = PlaceOfLiving::findOrFail($id);
        $place->is_active = false;
        $place->save();
        
        $this->dispatch('notify', message: 'Place of Living has been deactivated (soft delete).');
        $this->loadPlacesOfLiving();
    }

    public function render()
    {
        return view('livewire.place-of-living-maintenance');
    }
}
