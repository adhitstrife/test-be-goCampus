<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class PostArticle extends Component
{
    use WithPagination;

    public $title;
    public $content;

    public function render()
    {
        return view('livewire.post-article');
    }
}
