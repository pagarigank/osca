<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\SeniorCitizen;
use App\Models\ChatSession;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public $pendingCount       = 0;
    public $pendingUsers       = [];
    public $totalSeniorCitizens = 0;
    public $activeSeniorCitizens = 0;
    public $unreadMessagesCount = 0;

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->pendingCount = User::where('approval_status', 'PENDING')->count();

        $this->pendingUsers = User::with('office')
            ->where('approval_status', 'PENDING')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $this->totalSeniorCitizens  = SeniorCitizen::count();
        $this->activeSeniorCitizens = SeniorCitizen::where('is_active', true)->count();
        
        $this->unreadMessagesCount = ChatSession::where('status', 'active')
            ->whereHas('messages', function($q) {
                $q->where('sender_type', 'guest')->where('is_read', false);
            })->count();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
