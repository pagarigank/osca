<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use Livewire\Attributes\Layout;

#[Layout('layouts.guest')]
class ContactUs extends Component
{
    public $name = '';
    public $email = '';
    public $subject = '';
    public $message = '';
    
    public $isSent = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'required|string|max:255',
        'message' => 'required|string|min:10',
    ];

    public function sendMessage()
    {
        $this->validate();

        try {
            Mail::to('kpagarigan@gmail.com')->send(new ContactFormMail(
                $this->name,
                $this->email,
                $this->subject,
                $this->message
            ));
            
            $this->isSent = true;
            $this->reset(['name', 'email', 'subject', 'message']);
            
        } catch (\Exception $e) {
            $this->addError('email_error', 'Sorry, there was an issue sending your message. Please try again later.');
        }
    }

    public function render()
    {
        return view('livewire.contact-us');
    }
}
