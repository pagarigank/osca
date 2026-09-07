<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PurokLeader;
use App\Models\Purok;
use App\Models\CivilStatus;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rule;

#[Layout('layouts.app')]
class PurokLeaderMaintenance extends Component
{
    public $leaders;
    public $puroks;
    public $civilStatuses;

    public $leader_id;
    public $purok_id = '';
    public $last_name = '';
    public $first_name = '';
    public $middle_name = '';
    public $suffix = '';
    public $mobile_no = '';
    public $date_of_birth = '';
    public $civil_status_id = '';
    public $is_active = true;

    public $isModalOpen = false;
    public $isEditMode = false;

    public $filterPurokId = '';

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $query = PurokLeader::with(['purok.barangay', 'civilStatus'])->orderBy('last_name');

        if ($this->filterPurokId) {
            $query->where('purok_id', $this->filterPurokId);
        }

        $this->leaders = $query->get();
        $this->puroks = Purok::with('barangay')->where('is_active', true)->orderBy('name')->get();
        $this->civilStatuses = CivilStatus::where('is_active', true)->orderBy('name')->get();
    }

    public function updatedFilterPurokId()
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
        $this->leader_id = null;
        $this->purok_id = '';
        $this->last_name = '';
        $this->first_name = '';
        $this->middle_name = '';
        $this->suffix = '';
        $this->mobile_no = '';
        $this->date_of_birth = '';
        $this->civil_status_id = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate([
            'purok_id' => 'required|exists:puroks,id',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:20',
            'mobile_no' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'civil_status_id' => 'nullable|exists:civil_statuses,id',
        ]);

        // Livewire submits empty date / select fields as '' which fails the
        // DATE cast on save. Normalize blank values to null before persisting.
        $this->date_of_birth = $this->date_of_birth ?: null;
        $this->civil_status_id = $this->civil_status_id ?: null;
        $this->middle_name = $this->middle_name ?: null;
        $this->suffix = $this->suffix ?: null;
        $this->mobile_no = $this->mobile_no ?: null;

        PurokLeader::updateOrCreate(
            ['id' => $this->leader_id],
            [
                'purok_id' => $this->purok_id,
                'last_name' => $this->last_name,
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'suffix' => $this->suffix,
                'mobile_no' => $this->mobile_no,
                'date_of_birth' => $this->date_of_birth,
                'civil_status_id' => $this->civil_status_id,
                'is_active' => $this->is_active,
            ]
        );

        $this->dispatch('notify', message: $this->leader_id ? 'Purok Leader updated successfully.' : 'Purok Leader created successfully.');
        $this->closeModal();
        $this->loadData();
    }

    public function edit($id)
    {
        $leader = PurokLeader::findOrFail($id);
        $this->leader_id = $id;
        $this->purok_id = $leader->purok_id;
        $this->last_name = $leader->last_name;
        $this->first_name = $leader->first_name;
        $this->middle_name = $leader->middle_name;
        $this->suffix = $leader->suffix;
        $this->mobile_no = $leader->mobile_no;
        $this->date_of_birth = $leader->date_of_birth?->format('Y-m-d') ?? '';
        $this->civil_status_id = $leader->civil_status_id;
        $this->is_active = $leader->is_active;
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $leader = PurokLeader::findOrFail($id);
        $leader->is_active = false;
        $leader->save();

        $this->dispatch('notify', message: 'Purok Leader has been deactivated (soft delete).');
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.purok-leader-maintenance');
    }
}
