<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class DatePicker extends Component
{
    public function __construct(
        // Puedes añadir más propiedades aquí si necesitas pasar más configuraciones
    ) {}

    public function render(): View
    {
        return view('components.date-picker');
    }
}
