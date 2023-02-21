<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Counter extends Component
{
    public $count = 0;

    public function increment()
    {
        $this->count += 1;
    }

    public function decrement()
    {
        $this->count--;
    }
    public function render()
    {
        // return "2132131";
        return view('livewire.counter');
    }

}