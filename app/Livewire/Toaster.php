<?php

namespace App\Livewire;

use Livewire\Component;

class Toaster extends Component
{
    public $message = '';
    public $type = 'success'; // types: success, error, info
    public $show = false;
    public $position = 'top-right'; // available positions: top-right, top-left, bottom-right, bottom-left

    // Listen for the 'toast' event. You can pass message, type, and optionally a position.
    protected $listeners = ['toast' => 'showToast'];

    public function showToast($message, $type , $position)
    {
        $this->message = $message;
        $this->type = $type;
        $this->position = $position;
        $this->show = true;

        // Dispatch a browser event (optional) to handle additional JS logic.
        $this->dispatch('toast-displayed');
    }

    public function render()
    {
        return view('livewire.toaster');
    }
}
