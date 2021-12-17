<?php

namespace Noardcode\Tables\Components;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\View\Component;

class TableSelect extends Component
{
    protected string $title;

    protected array $items;

    /**
     * Table constructor.
     *
     * @param iterable $collection
     * @param bool $isTrash
     */
    public function __construct(string $title, array $items)
    {
        $this->title = $title;
        $this->items = $items;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function render(): Renderable
    {
        return view('noardcode::table-select')
            ->with('title', $this->title)
            ->with('items', $this->items);
    }
}
