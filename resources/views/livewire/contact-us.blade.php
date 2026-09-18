<div style="min-height: 100vh; background-color: var(--bg); display: flex; align-items: center; justify-content: center; padding: 2rem;">
    <div style="background: var(--surface); width: 100%; max-width: 600px; border-radius: var(--radius-xl); box-shadow: var(--shadow-xl); overflow: hidden; border: 1px solid var(--border);">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-mid) 100%); color: white; padding: 2.5rem 2rem; text-align: center;">
            <div style="width: 56px; height: 56px; background: rgba(255,255,255,0.15); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; border: 1px solid rgba(255,255,255,0.2);">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            </div>
            <h2 style="margin: 0; font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em;">Contact Us</h2>
            <p style="margin: 0.5rem 0 0; font-size: 0.95rem; opacity: 0.9;">We'd love to hear from you. Send us a message!</p>
        </div>

        <div style="padding: 2.5rem;">
            @if($isSent)
                <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 2rem; border-radius: var(--radius-lg); text-align: center; margin-bottom: 1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 1rem;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <h3 style="margin: 0 0 0.5rem; font-size: 1.25rem; font-weight: 700;">Message Sent Successfully!</h3>
                    <p style="margin: 0; font-size: 0.95rem; opacity: 0.9;">Thank you for reaching out. We will get back to you shortly.</p>
                    <button type="button" wire:click="$set('isSent', false)" class="btn btn-primary" style="margin-top: 1.75rem;">Send Another Message</button>
                </div>
            @else
                <form wire:submit="sendMessage">
                    @error('email_error')
                        <div style="background: #fef2f2; color: #b91c1c; border-left: 4px solid #ef4444; padding: 1rem; font-size: 0.9rem; border-radius: 0.25rem; margin-bottom: 1.25rem; font-weight: 500;">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="form-group">
                        <label class="form-label">Your Name <span class="required">*</span></label>
                        <input type="text" wire:model="name" placeholder="Juan Dela Cruz" class="form-input" required>
                        @error('name') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address <span class="required">*</span></label>
                        <input type="email" wire:model="email" placeholder="juan@example.com" class="form-input" required>
                        @error('email') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Subject <span class="required">*</span></label>
                        <input type="text" wire:model="subject" placeholder="How can we help?" class="form-input" required>
                        @error('subject') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Message <span class="required">*</span></label>
                        <textarea wire:model="message" rows="5" placeholder="Write your message here..." class="form-input" required></textarea>
                        @error('message') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.875rem; font-size: 1.05rem; margin-top: 1rem; display: flex; justify-content: center;">
                        <svg wire:loading.remove wire:target="sendMessage" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                        <svg wire:loading wire:target="sendMessage" style="animation: spin 1s linear infinite; margin-right: 0.5rem;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg>
                        <span wire:loading.remove wire:target="sendMessage">Send Message</span>
                        <span wire:loading wire:target="sendMessage">Sending...</span>
                    </button>
                    
                    <style>
                        @keyframes spin { 100% { transform: rotate(360deg); } }
                    </style>
                </form>
            @endif
        </div>
    </div>
</div>
