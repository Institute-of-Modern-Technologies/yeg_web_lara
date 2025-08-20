<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Services\ImagePathService;

class Image extends Component
{
    /**
     * The image source.
     *
     * @var string
     */
    public $src;

    /**
     * The alt text.
     *
     * @var string
     */
    public $alt;

    /**
     * Additional CSS classes.
     *
     * @var string
     */
    public $class;

    /**
     * Any other attributes.
     *
     * @var array
     */
    public $attributes;

    /**
     * Create a new component instance.
     *
     * @param  string  $src
     * @param  string  $alt
     * @param  string  $class
     * @param  array  $attributes
     * @return void
     */
    public function __construct(
        string $src, 
        string $alt = '', 
        string $class = '', 
        array $attributes = []
    ) {
        $this->src = app(ImagePathService::class)->resolveImagePath($src);
        $this->alt = $alt;
        $this->class = $class;
        $this->attributes = $attributes;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.image');
    }
}
