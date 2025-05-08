<?php

namespace App\Livewire\Public;

use Livewire\Component;

class Pricing extends Component
{
    public $billingFrequency = 'monthly';

    public function render()
    {
        return view('livewire.public.pricing')->layout('components.layouts.page');
    }
}
