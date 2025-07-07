<?php

namespace App\View\Components;

use Illuminate\View\Component;

class BackLink extends Component
{
    public $href;
    public $text;

    public function __construct($href, $text)
    {
        $this->href = $href;
        $this->text = $text;
    }

    public function render()
    {
        return view('components.back-link');
    }
}
