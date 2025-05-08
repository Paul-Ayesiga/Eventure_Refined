<?php

namespace App\Livewire\Public;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;

class Contact extends Component
{
    public $name = '';
    public $email = '';
    public $subject = '';
    public $message = '';

    protected $rules = [
        'name' => 'required|min:2',
        'email' => 'required|email',
        'subject' => 'required|min:5',
        'message' => 'required|min:10',
    ];

    public function submit()
    {
        $this->validate();

        // Here you would typically send the email
        // Mail::to('support@eventure.com')->send(new ContactFormMail($this->all()));

        $this->reset(['name', 'email', 'subject', 'message']);
        $this->dispatch('toast', 'Thank you for your message. We\'ll get back to you soon!', 'success');
    }

    public function render()
    {
        return view('livewire.public.contact')->layout('components.layouts.page');
    }
}
