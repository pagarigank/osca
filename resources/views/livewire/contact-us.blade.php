<div style="min-height: 100vh; background-color: #f3f4f6; display: flex; align-items: center; justify-content: center; padding: 2rem;">
    <div style="background: white; width: 100%; max-width: 600px; border-radius: 1rem; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); overflow: hidden;">
        
        <!-- Header -->
        <div style="background: var(--primary, #2563eb); color: white; padding: 2rem; text-align: center;">
            <h2 style="margin: 0; font-size: 1.75rem; font-weight: bold;">Contact Us</h2>
            <p style="margin: 0.5rem 0 0; font-size: 0.95rem; opacity: 0.9;">We'd love to hear from you. Send us a message!</p>
        </div>

        <div style="padding: 2.5rem;">
            @if($isSent)
                <div style="background: #ecfdf5; border: 1px solid #10b981; color: #065f46; padding: 1.5rem; border-radius: 0.5rem; text-align: center; margin-bottom: 1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 1rem; color: #10b981;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <h3 style="margin: 0 0 0.5rem; font-size: 1.25rem;">Message Sent Successfully!</h3>
                    <p style="margin: 0; font-size: 0.95rem;">Thank you for reaching out. We will get back to you shortly.</p>
                    <button type="button" wire:click="$set('isSent', false)" style="margin-top: 1.5rem; background: transparent; border: 1px solid #065f46; color: #065f46; padding: 0.5rem 1rem; border-radius: 0.25rem; cursor: pointer; font-weight: 600;">Send Another Message</button>
                </div>
            @else
                <form wire:submit="sendMessage" style="display: flex; flex-direction: column; gap: 1.25rem;">
                    @error('email_error')
                        <div style="background: #fef2f2; color: #dc2626; border-left: 4px solid #ef4444; padding: 1rem; font-size: 0.9rem; border-radius: 0.25rem;">
                            {{ $message }}
                        </div>
                    @enderror

                    <div>
                        <label style="display: block; font-size: 0.9rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Your Name</label>
                        <input type="text" wire:model="name" placeholder="John Doe" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 1rem; outline: none; transition: border-color 0.2s;" required>
                        @error('name') <span style="color: red; font-size: 0.75rem; display: block; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.9rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Email Address</label>
                        <input type="email" wire:model="email" placeholder="john@example.com" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 1rem; outline: none; transition: border-color 0.2s;" required>
                        @error('email') <span style="color: red; font-size: 0.75rem; display: block; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.9rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Subject</label>
                        <input type="text" wire:model="subject" placeholder="How can we help?" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 1rem; outline: none; transition: border-color 0.2s;" required>
                        @error('subject') <span style="color: red; font-size: 0.75rem; display: block; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.9rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Message</label>
                        <textarea wire:model="message" rows="5" placeholder="Write your message here..." style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 1rem; outline: none; resize: vertical; transition: border-color 0.2s;" required></textarea>
                        @error('message') <span style="color: red; font-size: 0.75rem; display: block; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" style="background: var(--primary, #2563eb); color: white; border: none; padding: 1rem; border-radius: 0.5rem; font-size: 1.1rem; font-weight: bold; cursor: pointer; transition: background 0.2s; margin-top: 0.5rem; display: flex; justify-content: center; align-items: center; gap: 0.5rem;">
                        <svg wire:loading.remove wire:target="sendMessage" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                        <svg wire:loading wire:target="sendMessage" style="animation: spin 1s linear infinite;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg>
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
