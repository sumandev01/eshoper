<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Textarea extends Component
{
    public function __construct(
        public string $name,
        public ?string $value = null,
        public ?string $label = null,
        public ?string $placeholder = null,
        public ?int $rows = 5,
        public ?bool $required = false,
        public ?bool $readonly = false,
        public ?bool $disabled = false,
        public bool $editor = false,
        public ?int $maxlength = null, // Default null
        public ?int $minlength = null, // Default null
        public bool $wordcount = false, // Toggle word count display
    )
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.textarea');
    }
}