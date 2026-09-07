<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\SeniorCitizen;

#[Layout('layouts.public')]
class ScVerification extends Component
{
    public ?SeniorCitizen $sc = null;
    public bool $found = true;

    public function mount(string $token)
    {
        $this->sc = SeniorCitizen::with(['barangay', 'gender', 'civilStatus'])
            ->where('qr_token', $token)
            ->first();

        if (!$this->sc) {
            $this->found = false;
        }
    }

    public function render()
    {
        return view('livewire.sc-verification');
    }
}
