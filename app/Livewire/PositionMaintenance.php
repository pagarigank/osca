<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Position;
use App\Models\Office;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class PositionMaintenance extends Component
{
    public $positions;
    public $offices;

    // Form fields
    public $position_id;
    public $office_id   = '';
    public $name        = '';
    public $is_active   = true;

    // Filter
    public $filter_office_id = '';

    // Modal state
    public $isModalOpen = false;
    public $isEditMode  = false;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $query = Position::with('office')->orderBy('name');

        if ($this->filter_office_id) {
            $query->where('office_id', $this->filter_office_id);
        }

        $this->positions = $query->get();
        $this->offices   = Office::where('is_active', true)->orderBy('name')->get();
    }

    public function updatedFilterOfficeId()
    {
        $this->loadData();
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
        $this->isEditMode  = false;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->position_id = null;
        $this->office_id   = '';
        $this->name        = '';
        $this->is_active   = true;
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate([
            'office_id' => 'required|exists:offices,id',
            'name'      => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('positions')->where('office_id', $this->office_id)->ignore($this->position_id),
            ],
        ], [
            'office_id.required' => 'Please select an office.',
            'name.unique'        => 'This position already exists under the selected office.',
        ]);

        Position::updateOrCreate(
            ['id' => $this->position_id],
            [
                'office_id' => $this->office_id,
                'name'      => $this->name,
                'is_active' => $this->is_active,
            ]
        );

        $this->dispatch('notify', message: $this->position_id ? 'Position updated successfully.' : 'Position created successfully.');
        $this->closeModal();
        $this->loadData();
    }

    public function edit($id)
    {
        $position = Position::findOrFail($id);
        $this->position_id = $id;
        $this->office_id   = $position->office_id;
        $this->name        = $position->name;
        $this->is_active   = $position->is_active;
        $this->isEditMode  = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $position = Position::findOrFail($id);
        $position->is_active = false;
        $position->save();

        $this->dispatch('notify', message: 'Position has been deactivated (soft delete).');
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.position-maintenance');
    }
}
