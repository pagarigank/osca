<div style="min-height: 100vh; background-color: var(--bg); display: flex; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: var(--surface); width: 100%; max-width: 500px; border-radius: var(--radius-xl); box-shadow: var(--shadow-xl); overflow: hidden; display: flex; flex-direction: column; height: 85vh; max-height: 800px; border: 1px solid var(--border);">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-mid) 100%); color: white; padding: 1.5rem; text-align: center; box-shadow: var(--shadow-sm); z-index: 10;">
            <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; border: 1px solid rgba(255,255,255,0.2);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
            </div>
            <h2 style="margin: 0; font-size: 1.25rem; font-weight: 700; letter-spacing: -0.01em;">Live Support Chat</h2>
            <p style="margin: 0.25rem 0 0; font-size: 0.85rem; opacity: 0.9;">We typically reply in a few minutes.</p>
        </div>

        @if(!$chatSession)
            <!-- Start Session Form -->
            <div style="padding: 2.5rem 2rem; display: flex; flex-direction: column; justify-content: center; flex: 1; background: var(--surface);">
                <div style="text-align: center; margin-bottom: 2.5rem;">
                    <span style="font-size: 3.5rem; display: inline-block; margin-bottom: 0.5rem;">👋</span>
                    <h3 style="margin: 0 0 0.5rem; color: var(--text-primary); font-size: 1.5rem; font-weight: 800;">Welcome!</h3>
                    <p style="color: var(--text-secondary); font-size: 0.95rem; line-height: 1.5;">Please enter your details to start chatting with our support team.</p>
                </div>
                
                <form wire:submit="startSession">
                    <div class="form-group">
                        <label class="form-label">Your Name <span class="required">*</span></label>
                        <input type="text" wire:model="guestName" placeholder="Juan Dela Cruz" class="form-input" required>
                        @error('guestName') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label">Email Address <span class="required">*</span></label>
                        <input type="email" wire:model="guestEmail" placeholder="juan@example.com" class="form-input" required>
                        @error('guestEmail') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.875rem; font-size: 1.05rem; display: flex; justify-content: center; align-items: center; gap: 0.5rem;">
                        Start Chat
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </button>
                </form>
            </div>
        @else
            <!-- Chat Interface -->
            <div style="flex: 1; overflow-y: auto; padding: 1.5rem; display: flex; flex-direction: column; gap: 1.25rem; background: #f8fafc;" wire:poll.2s>
                <div style="text-align: center; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.72rem; font-weight: 600; background: #e2e8f0; color: #475569; padding: 0.35rem 1rem; border-radius: var(--radius-full); text-transform: uppercase; letter-spacing: 0.05em;">Chat Started</span>
                </div>

                @forelse($chatSession->messages as $msg)
                    @if($msg->sender_type === 'guest')
                        <div style="align-self: flex-end; max-width: 82%;">
                            <div style="background: var(--primary); color: white; padding: 0.875rem 1.125rem; border-radius: 1.25rem 1.25rem 0 1.25rem; font-size: 0.95rem; box-shadow: var(--shadow-sm); line-height: 1.5;">
                                {{ $msg->message }}
                            </div>
                            <div style="font-size: 0.7rem; font-weight: 500; color: #94a3b8; text-align: right; margin-top: 0.35rem; margin-right: 0.25rem;">{{ $msg->created_at->format('g:i A') }}</div>
                        </div>
                    @else
                        <div style="align-self: flex-start; max-width: 82%;">
                            <div style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 0.35rem; padding-left: 0.5rem; display: flex; align-items: center; gap: 0.35rem;">
                                <div style="width: 16px; height: 16px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                </div>
                                Support Team
                            </div>
                            <div style="background: white; color: var(--text-primary); padding: 0.875rem 1.125rem; border-radius: 0 1.25rem 1.25rem 1.25rem; font-size: 0.95rem; border: 1px solid var(--border); box-shadow: var(--shadow-sm); line-height: 1.5;">
                                {{ $msg->message }}
                            </div>
                            <div style="font-size: 0.7rem; font-weight: 500; color: #94a3b8; margin-top: 0.35rem; margin-left: 0.25rem;">{{ $msg->created_at->format('g:i A') }}</div>
                        </div>
                    @endif
                @empty
                    <div style="text-align: center; color: #94a3b8; font-size: 0.9rem; margin-top: auto; margin-bottom: auto; padding: 2rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 1rem;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        No messages yet. Say hello!
                    </div>
                @endforelse
            </div>

            <!-- Input Area -->
            <div style="padding: 1.25rem; background: white; border-top: 1px solid var(--border); box-shadow: 0 -4px 10px rgba(0,0,0,0.02); z-index: 10;">
                <form wire:submit="sendMessage" style="display: flex; gap: 0.75rem; align-items: center;">
                    <input type="text" wire:model="message" placeholder="Type a message..." class="form-input" style="flex: 1; border-radius: var(--radius-full); padding: 0.875rem 1.25rem; background: #f8fafc; border-color: #e2e8f0;" required autocomplete="off">
                    <button type="submit" style="background: var(--primary); color: white; border: none; width: 3.25rem; height: 3.25rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; box-shadow: var(--shadow-md); transition: transform 0.2s, background 0.2s;" onmouseover="this.style.transform='scale(1.05)'; this.style.background='var(--primary-light)';" onmouseout="this.style.transform='scale(1)'; this.style.background='var(--primary)';">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: -2px; margin-top: 1px;"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
