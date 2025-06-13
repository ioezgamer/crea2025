<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Illuminate\Support\Collection;

class CommunitySelector extends Component
{
    public function __construct(
        public Collection $comunidades,
        public string $name,
        public string $id,
        public ?string $value = '', // El valor actual (para edición)
        public bool $required = false
    ) {}

    public function render(): View
    {
        return view('components.community-selector');
    }
}
