<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Location;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rule;

#[Layout('layouts.app')]
class LocationMaintenance extends Component
{
    public $locations;
    public $location_id;
    public $province = '';
    public $city_municipality = '';
    public $is_active = true;
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadLocations();
    }

    public function loadLocations()
    {
        $this->locations = Location::all();
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
        $this->province = '';
        $this->city_municipality = '';
        $this->is_active = true;
        $this->location_id = null;
    }

    public function store()
    {
        $this->validate([
            'province' => 'required|string|max:255',
            'city_municipality' => [
                'required',
                'string',
                'max:255',
                Rule::unique('locations')->where(function ($query) {
                    return $query->where('province', $this->province)
                                 ->where('city_municipality', $this->city_municipality);
                })->ignore($this->location_id),
            ],
        ], [
            'city_municipality.unique' => 'The combination of Province and City/Municipality already exists.'
        ]);

        Location::updateOrCreate(
            ['id' => $this->location_id],
            [
                'province' => $this->province,
                'city_municipality' => $this->city_municipality,
                'is_active' => $this->is_active
            ]
        );

        $this->dispatch('notify', message: $this->location_id ? 'Location updated successfully.' : 'Location created successfully.');
        $this->closeModal();
        $this->loadLocations();
    }

    public function edit($id)
    {
        $location = Location::findOrFail($id);
        $this->location_id = $id;
        $this->province = $location->province;
        $this->city_municipality = $location->city_municipality;
        $this->is_active = $location->is_active;
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $location = Location::findOrFail($id);
        $location->is_active = false;
        $location->save();
        
        $this->dispatch('notify', message: 'Location has been deactivated (soft delete).');
        $this->loadLocations();
    }

    public function render()
    {
        return view('livewire.location-maintenance');
    }
}
