<?php

namespace Noardcode\Tables\Collections;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class Collection
 *
 * @package Noardcode\Tables\Collections
 */
abstract class Collection extends EloquentCollection
{
    /**
     * @var bool
     */
    protected bool $selectable = false;

    /**
     * @return array
     */
    abstract public function getTableColumns(): array;

    /**
     * @return array
     */
    public function getTableActions(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getRowActions(): array
    {
        return [];
    }

    /**
     * @return string|null
     */
    public function getSortableRoute(): ?string
    {
        return null;
    }

    /**
     * @return bool
     */
    public function isSelectable(): bool
    {
        return $this->selectable;
    }
}
