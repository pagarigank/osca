<div style="min-height: 100vh; background-color: #f3f4f6; display: flex; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: white; width: 100%; max-width: 500px; border-radius: 1rem; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); overflow: hidden; display: flex; flex-direction: column; height: 80vh;">
        
        <!-- Header -->
        <div style="background: var(--primary, #2563eb); color: white; padding: 1.25rem; text-align: center;">
            <h2 style="margin: 0; font-size: 1.25rem; font-weight: 600;">Live Support Chat</h2>
            <p style="margin: 0.25rem 0 0; font-size: 0.85rem; opacity: 0.9;">We typically reply in a few minutes.</p>
        </div>

        @if(!$chatSession)
            <!-- Start Session Form -->
            <div style="padding: 2rem; display: flex; flex-direction: column; justify-content: center; flex: 1;">
                <div style="text-align: center; margin-bottom: 2rem;">
                    <span style="font-size: 3rem;">👋</span>
                    <h3 style="margin: 1rem 0 0.5rem; color: #1f2937;">Welcome!</h3>
                    <p style="color: #6b7280; font-size: 0.9rem;">Please enter your name to start chatting with our support team.</p>
                </div>
                
                <form wire:submit="startSession" style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <input type="text" wire:model="guestName" placeholder="Your Name" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 1rem; outline: none;" required>
                        @error('guestName') <span style="color: red; font-size: 0.75rem; display: block; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <input type="email" wire:model="guestEmail" placeholder="Your Email Address" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 1rem; outline: none;" required>
                        @error('guestEmail') <span style="color: red; font-size: 0.75rem; display: block; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" style="background: var(--primary, #2563eb); color: white; border: none; padding: 0.75rem; border-radius: 0.5rem; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s;">Start Chat</button>
                </form>
            </div>
        @else
            <!-- Chat Interface -->
            <div style="flex: 1; overflow-y: auto; padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem; background: #f9fafb;" wire:poll.2s>
                <div style="text-align: center; margin-bottom: 1rem;">
                    <span style="font-size: 0.75rem; background: #e5e7eb; color: #4b5563; padding: 0.25rem 0.75rem; border-radius: 1rem;">Chat Started</span>
                </div>

                @forelse($chatSession->messages as $msg)
                    @if($msg->sender_type === 'guest')
                        <div style="align-self: flex-end; max-width: 80%;">
                            <div style="background: var(--primary, #2563eb); color: white; padding: 0.75rem 1rem; border-radius: 1rem 1rem 0 1rem; font-size: 0.95rem; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                                {{ $msg->message }}
                            </div>
                            <div style="font-size: 0.65rem; color: #9ca3af; text-align: right; margin-top: 0.25rem;">{{ $msg->created_at->format('g:i A') }}</div>
                        </div>
                    @else
                        <div style="align-self: flex-start; max-width: 80%;">
                            <div style="font-size: 0.7rem; color: #6b7280; margin-bottom: 0.25rem; padding-left: 0.25rem;">Support Team</div>
                            <div style="background: white; color: #1f2937; padding: 0.75rem 1rem; border-radius: 0 1rem 1rem 1rem; font-size: 0.95rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                {{ $msg->message }}
                            </div>
                            <div style="font-size: 0.65rem; color: #9ca3af; margin-top: 0.25rem;">{{ $msg->created_at->format('g:i A') }}</div>
                        </div>
                    @endif
                @empty
                    <div style="text-align: center; color: #6b7280; font-size: 0.85rem; margin-top: 2rem;">
                        No messages yet. Say hello!
                    </div>
                @endforelse
            </div>

            <!-- Input Area -->
            <div style="padding: 1rem; background: white; border-top: 1px solid #e5e7eb;">
                <form wire:submit="sendMessage" style="display: flex; gap: 0.5rem;">
                    <input type="text" wire:model="message" placeholder="Type a message..." style="flex: 1; padding: 0.75rem 1rem; border: 1px solid #e5e7eb; border-radius: 2rem; font-size: 0.95rem; outline: none; background: #f9fafb;" required autocomplete="off">
                    <button type="submit" style="background: var(--primary, #2563eb); color: white; border: none; width: 3rem; height: 3rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
