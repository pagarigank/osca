<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PayrollProvider;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class PayrollProviderMaintenance extends Component
{
    public $payroll_providers;
    public $payroll_provider_id;
    public $name = '';
    public $is_active = true;
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadPayrollProviders();
    }

    public function loadPayrollProviders()
    {
        $this->payroll_providers = PayrollProvider::all();
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
        $this->payroll_provider_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:payroll_providers,name,' . $this->payroll_provider_id,
        ]);

        PayrollProvider::updateOrCreate(
            ['id' => $this->payroll_provider_id],
            [
                'name' => $this->name,
                'is_active' => $this->is_active
            ]
        );

        $this->dispatch('notify', message: $this->payroll_provider_id ? 'Payroll Provider updated successfully.' : 'Payroll Provider created successfully.');
        $this->closeModal();
        $this->loadPayrollProviders();
    }

    public function edit($id)
    {
        $provider = PayrollProvider::findOrFail($id);
        $this->payroll_provider_id = $id;
        $this->name = $provider->name;
        $this->is_active = $provider->is_active;
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $provider = PayrollProvider::findOrFail($id);
        $provider->is_active = false;
        $provider->save();
        
        $this->dispatch('notify', message: 'Payroll Provider has been deactivated (soft delete).');
        $this->loadPayrollProviders();
    }

    public function render()
    {
        return view('livewire.payroll-provider-maintenance');
    }
}
