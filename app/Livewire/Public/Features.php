<?php

namespace App\Livewire\Public;

use Livewire\Component;

class Features extends Component
{
    public function render()
    {
        return view('livewire.public.features')->layout('components.layouts.page');
    }
}
