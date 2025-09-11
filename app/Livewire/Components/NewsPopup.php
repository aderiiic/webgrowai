<?php

namespace App\Livewire\Components;

use App\Models\InternalNews;
use Livewire\Component;

class NewsPopup extends Component
{
    public ?InternalNews $news = null;
    public bool $show = false;

    public function mount()
    {
        if (auth()->check()) {
            $this->news = InternalNews::getPopupForUser(auth()->id());
            $this->show = $this->news !== null;
        }
    }

    public function closePopup()
    {
        if ($this->news && auth()->check()) {
            $this->news->markAsSeenBy(auth()->id());
        }
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.components.news-popup');
    }
}
