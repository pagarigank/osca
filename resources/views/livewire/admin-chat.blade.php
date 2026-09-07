<div style="padding: 1.5rem;" wire:poll.2s>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.5rem; font-weight: bold; color: #1f2937; margin: 0;">Live Support Chat</h2>
    </div>

    <div style="display: flex; gap: 1.5rem; height: 75vh; background: white; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); overflow: hidden; border: 1px solid #e5e7eb;">
        
        <!-- Sidebar -->
        <div style="width: 320px; background: #f9fafb; border-right: 1px solid #e5e7eb; display: flex; flex-direction: column;">
            <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb; background: white;">
                <h3 style="margin: 0; font-size: 1rem; font-weight: 600; color: #374151;">Active Sessions</h3>
            </div>
            <div style="flex: 1; overflow-y: auto;">
                @forelse($activeSessions as $session)
                    <div wire:click="selectSession({{ $session->id }})" style="padding: 1rem; border-bottom: 1px solid #e5e7eb; cursor: pointer; transition: background 0.2s; background: {{ $activeSessionId === $session->id ? '#eff6ff' : 'transparent' }};">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                            <span style="font-weight: 600; color: {{ $activeSessionId === $session->id ? '#1d4ed8' : '#1f2937' }}; font-size: 0.95rem;">
                                {{ $session->guest_name }}
                            </span>
                            <span style="font-size: 0.7rem; color: #9ca3af;">{{ $session->updated_at->diffForHumans(null, true, true) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 0.8rem; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">
                                @if($session->messages->count() > 0)
                                    {{ $session->messages->last()->message }}
                                @else
                                    <em>New chat session</em>
                                @endif
                            </span>
                            @if($session->unread_count > 0)
                                <span style="background: var(--primary, #2563eb); color: white; border-radius: 1rem; padding: 0.1rem 0.4rem; font-size: 0.7rem; font-weight: bold;">
                                    {{ $session->unread_count }}
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="padding: 2rem 1rem; text-align: center; color: #9ca3af; font-size: 0.9rem;">
                        No active support chats at the moment.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Main Chat Area -->
        <div style="flex: 1; display: flex; flex-direction: column; background: white;">
            @if($activeChat)
                <!-- Chat Header -->
                <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h3 style="margin: 0; font-size: 1.1rem; font-weight: 600; color: #1f2937;">{{ $activeChat->guest_name }}</h3>
                        <span style="font-size: 0.8rem; color: #10b981; display: flex; align-items: center; gap: 0.25rem;">
                            <div style="width: 8px; height: 8px; background: #10b981; border-radius: 50%;"></div> Online
                        </span>
                    </div>
                    <button type="button" wire:click="closeSession" wire:confirm="Are you sure you want to close this chat session?" style="background: white; border: 1px solid #ef4444; color: #ef4444; padding: 0.4rem 0.75rem; border-radius: 0.5rem; font-size: 0.85rem; cursor: pointer;">
                        End Chat
                    </button>
                </div>

                <!-- Messages Area -->
                <div style="flex: 1; overflow-y: auto; padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem; background: #f9fafb;">
                    <div style="text-align: center; margin-bottom: 1rem;">
                        <span style="font-size: 0.75rem; background: #e5e7eb; color: #4b5563; padding: 0.25rem 0.75rem; border-radius: 1rem;">Chat Started {{ $activeChat->created_at->format('M j, Y g:i A') }}</span>
                    </div>

                    @foreach($activeChat->messages as $msg)
                        @if($msg->sender_type === 'admin')
                            <div style="align-self: flex-end; max-width: 80%;">
                                <div style="font-size: 0.7rem; color: #6b7280; text-align: right; margin-bottom: 0.25rem; padding-right: 0.25rem;">You</div>
                                <div style="background: var(--primary, #2563eb); color: white; padding: 0.75rem 1rem; border-radius: 1rem 1rem 0 1rem; font-size: 0.95rem; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                                    {{ $msg->message }}
                                </div>
                                <div style="font-size: 0.65rem; color: #9ca3af; text-align: right; margin-top: 0.25rem;">{{ $msg->created_at->format('g:i A') }}</div>
                            </div>
                        @else
                            <div style="align-self: flex-start; max-width: 80%;">
                                <div style="font-size: 0.7rem; color: #6b7280; margin-bottom: 0.25rem; padding-left: 0.25rem;">{{ $activeChat->guest_name }}</div>
                                <div style="background: white; color: #1f2937; padding: 0.75rem 1rem; border-radius: 0 1rem 1rem 1rem; font-size: 0.95rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                    {{ $msg->message }}
                                </div>
                                <div style="font-size: 0.65rem; color: #9ca3af; margin-top: 0.25rem;">{{ $msg->created_at->format('g:i A') }}</div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Input Area -->
                <div style="padding: 1rem 1.5rem; background: white; border-top: 1px solid #e5e7eb;">
                    <form wire:submit="sendMessage" style="display: flex; gap: 0.75rem;">
                        <input type="text" wire:model="message" placeholder="Type your reply to {{ $activeChat->guest_name }}..." style="flex: 1; padding: 0.75rem 1rem; border: 1px solid #e5e7eb; border-radius: 2rem; font-size: 0.95rem; outline: none; background: #f9fafb;" required autocomplete="off">
                        <button type="submit" style="background: var(--primary, #2563eb); color: white; border: none; width: 3rem; height: 3rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                        </button>
                    </form>
                </div>
            @else
                <!-- No Chat Selected -->
                <div style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #9ca3af; background: #f9fafb;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1rem;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    <p style="font-size: 1.1rem; margin: 0;">Select a chat session to start replying</p>
                </div>
            @endif
        </div>
    </div>
</div>
