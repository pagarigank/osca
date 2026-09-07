<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Purok;
use App\Models\Barangay;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rule;

#[Layout('layouts.app')]
class PurokMaintenance extends Component
{
    public $puroks;
    public $barangays;

    public $purok_id;
    public $barangay_id = '';
    public $name = '';
    public $purok_code = '';
    public $description = '';
    public $prefix = '';
    public $id_sequence = 0;
    public $pay_type = '';
    public $is_active = true;

    public $isModalOpen = false;
    public $isEditMode = false;

    public $filterBarangayId = '';

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $query = Purok::with('barangay')->orderBy('name');

        if ($this->filterBarangayId) {
            $query->where('barangay_id', $this->filterBarangayId);
        }

        $this->puroks = $query->get();
        $this->barangays = Barangay::where('is_active', true)->orderBy('name')->get();
    }

    public function updatedFilterBarangayId()
    {
        $this->loadData();
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
        $this->purok_id = null;
        $this->barangay_id = '';
        $this->name = '';
        $this->purok_code = '';
        $this->description = '';
        $this->prefix = '';
        $this->id_sequence = 0;
        $this->pay_type = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate([
            'barangay_id' => 'required|exists:barangays,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('puroks')->where('barangay_id', $this->barangay_id)->ignore($this->purok_id),
            ],
            'purok_code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('puroks')->where('barangay_id', $this->barangay_id)->ignore($this->purok_id),
            ],
            'description' => 'nullable|string|max:1000',
            'prefix' => 'nullable|string|max:50',
            'id_sequence' => 'nullable|integer',
            'pay_type' => 'nullable|string|max:50',
        ], [
            'barangay_id.required' => 'Please select a barangay.',
            'name.unique' => 'This purok already exists under the selected barangay.',
            'purok_code.unique' => 'This purok code already exists under the selected barangay.',
        ]);

        Purok::updateOrCreate(
            ['id' => $this->purok_id],
            [
                'barangay_id' => $this->barangay_id,
                'name' => $this->name,
                'purok_code' => $this->purok_code ?: null,
                'description' => $this->description ?: null,
                'prefix' => $this->prefix,
                'id_sequence' => $this->id_sequence ?: 0,
                'pay_type' => $this->pay_type,
                'is_active' => $this->is_active,
            ]
        );

        $this->dispatch('notify', message: $this->purok_id ? 'Purok updated successfully.' : 'Purok created successfully.');
        $this->closeModal();
        $this->loadData();
    }

    public function edit($id)
    {
        $purok = Purok::findOrFail($id);
        $this->purok_id = $id;
        $this->barangay_id = $purok->barangay_id;
        $this->name = $purok->name;
        $this->purok_code = $purok->purok_code ?? '';
        $this->description = $purok->description ?? '';
        $this->prefix = $purok->prefix;
        $this->id_sequence = $purok->id_sequence;
        $this->pay_type = $purok->pay_type;
        $this->is_active = $purok->is_active;
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $purok = Purok::findOrFail($id);
        $purok->is_active = false;
        $purok->save();

        $this->dispatch('notify', message: 'Purok has been deactivated (soft delete).');
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.purok-maintenance');
    }
}
