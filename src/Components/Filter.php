<?php

namespace Noardcode\Tables\Components;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use Noardcode\Tables\Collections\Collection;

class Filter extends Component
{
    /**
     * @var iterable
     */
    private iterable $collection;

    /**
     * @var bool
     */
    private bool $isTrash;

    /**
     * @var string|string[]|null
     */
    private ?string $baseRouteName;

    /**
     * @var array
     */
    private array $filters;

    /**
     * Table constructor.
     *
     * @param iterable $collection
     */
    public function __construct(iterable $collection)
    {
        $this->collection = $collection;
        $this->filters = $this->collection->getFilters();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Renderable
     */
    public function render(): Renderable
    {
        return view('noardcode::table-filters')
            ->with('collection', $this->collection);
    }
}
