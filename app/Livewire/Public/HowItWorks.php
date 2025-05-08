<?php

namespace App\Livewire\Public;

use Livewire\Component;

class HowItWorks extends Component
{
    public function render()
    {
        return view('livewire.public.how-it-works')->layout('components.layouts.page');
    }
}
