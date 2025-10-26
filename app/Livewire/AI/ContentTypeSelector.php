<?php

namespace App\Livewire\AI;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ContentTypeSelector extends Component
{
    public function render(): View
    {
        return view('livewire.a-i.content-type-selector');
    }
}
