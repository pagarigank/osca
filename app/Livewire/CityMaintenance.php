<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\City;
use App\Models\Province;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CityMaintenance extends Component
{
    public $cities;
    public $provinces = [];
    public $city_id;
    public $province_id = '';
    public $name = '';
    public $is_active = true;
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->cities = City::with('province')->get();
        $this->provinces = Province::where('is_active', true)->get();
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
        $this->province_id = '';
        $this->name = '';
        $this->is_active = true;
        $this->city_id = null;
    }

    public function store()
    {
        $this->validate([
            'province_id' => 'required|exists:provinces,id',
            'name' => 'required',
        ]);

        City::updateOrCreate(
            ['id' => $this->city_id],
            [
                'province_id' => $this->province_id,
                'name' => $this->name,
                'is_active' => $this->is_active
            ]
        );

        $this->dispatch('notify', message: $this->city_id ? 'City/Municipality updated successfully.' : 'City/Municipality created successfully.');
        $this->closeModal();
        $this->loadData();
    }

    public function edit($id)
    {
        $city = City::findOrFail($id);
        $this->city_id = $id;
        $this->province_id = $city->province_id;
        $this->name = $city->name;
        $this->is_active = $city->is_active;
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $city = City::findOrFail($id);
        $city->is_active = false;
        $city->save();
        
        $this->dispatch('notify', message: 'City/Municipality has been deactivated (soft delete).');
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.city-maintenance');
    }
}
