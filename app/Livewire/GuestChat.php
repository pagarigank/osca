<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

#[Layout('layouts.guest')]
class GuestChat extends Component
{
    public $guestName = '';
    public $guestEmail = '';
    public $message = '';
    public $chatSession = null;

    public function mount()
    {
        $identifier = session()->get('guest_identifier');
        if (!$identifier) {
            $identifier = (string) Str::uuid();
            session()->put('guest_identifier', $identifier);
        }

        $this->chatSession = ChatSession::with('messages')
            ->where('guest_identifier', $identifier)
            ->first();
    }

    public function startSession()
    {
        $this->validate([
            'guestName' => 'required|string|max:255',
            'guestEmail' => 'required|email|max:255'
        ]);

        $identifier = session()->get('guest_identifier');

        $this->chatSession = ChatSession::updateOrCreate(
            ['guest_identifier' => $identifier],
            [
                'guest_name' => $this->guestName,
                'guest_email' => $this->guestEmail,
                'status' => 'active'
            ]
        );
    }

    public function sendMessage()
    {
        if (!$this->chatSession) return;
        
        $this->validate([
            'message' => 'required|string|max:1000'
        ]);

        $this->chatSession->messages()->create([
            'sender_type' => 'guest',
            'message' => $this->message
        ]);

        // If the admin closed the chat, re-open it automatically when the guest replies
        $this->chatSession->update(['status' => 'active']);
        $this->chatSession->touch();

        $this->message = '';
        $this->chatSession->load('messages');
    }

    public function render()
    {
        if ($this->chatSession) {
            $this->chatSession->load('messages');
        }
        return view('livewire.guest-chat');
    }
}
