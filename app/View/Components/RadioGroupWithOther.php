<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Illuminate\Support\Collection;

class RadioGroupWithOther extends Component
{
    public function __construct(
        public Collection $options,
        public string $name,
        public ?string $value = '',
        public bool $required = false
    ) {}

    public function render(): View
    {
        return view('components.radio-group-with-other');
    }
}
