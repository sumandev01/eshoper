<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Select extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $name,
        public ?string $label = null,
        public $options = [],
        public ?string $value = null,
        public ?string $placeholder = null,
        public ?string $class = null,
        public ?string $id = null,
        public ?bool $disabled = false,
        public ?bool $required = false,
        public ?bool $multiple = false,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select');
    }
}
