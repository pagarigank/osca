<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ChatReplyMail;
use Livewire\Attributes\Layout;

class AdminChat extends Component
{
    public $activeSessionId = null;
    public $message = '';

    public function selectSession($id)
    {
        $this->activeSessionId = $id;
        
        // Mark as read optionally
        if ($this->activeSessionId) {
            ChatMessage::where('chat_session_id', $this->activeSessionId)
                ->where('sender_type', 'guest')
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }
    }

    public function sendMessage()
    {
        if (!$this->activeSessionId) return;
        
        $this->validate([
            'message' => 'required|string|max:1000'
        ]);

        $session = ChatSession::find($this->activeSessionId);
        
        if ($session) {
            $session->messages()->create([
                'sender_type' => 'admin',
                'user_id' => auth()->id(),
                'message' => $this->message
            ]);
            
            // Touch session to bring it to top
            $session->touch();

            // Send email to guest if email was provided
            if ($session->guest_email) {
                try {
                    Mail::to($session->guest_email)->send(new ChatReplyMail(
                        auth()->user()->name ?? 'OSCA Admin',
                        $this->message
                    ));
                } catch (\Exception $e) {
                    // Fail silently so chat continues working even if mailtrap is down
                }
            }
        }

        $this->message = '';
    }

    public function closeSession()
    {
        if (!$this->activeSessionId) return;
        
        $session = ChatSession::find($this->activeSessionId);
        if ($session) {
            $session->update(['status' => 'closed']);
        }
        
        $this->activeSessionId = null;
    }

    public function render()
    {
        $activeSessions = ChatSession::withCount(['messages as unread_count' => function ($query) {
            $query->where('sender_type', 'guest')->where('is_read', false);
        }])->where('status', 'active')->orderBy('updated_at', 'desc')->get();

        $activeChat = null;
        if ($this->activeSessionId) {
            $activeChat = ChatSession::with('messages.user')->find($this->activeSessionId);
        }

        return view('livewire.admin-chat', [
            'activeSessions' => $activeSessions,
            'activeChat' => $activeChat
        ]);
    }
}
