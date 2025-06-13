<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Alert extends Component
{
    public string $type;
    public string $title;
    public string $message;

    /**
     * Create a new component instance.
     *
     * @param string $type El tipo de alerta (success, error, warning, info).
     * @param string $title El título de la alerta (opcional).
     * @param string $message El mensaje principal de la alerta.
     */
    public function __construct(string $type = 'info', string $title = '', string $message = '')
    {
        $this->type = $type;
        $this->title = $title;
        $this->message = $message;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.alert');
    }
}
