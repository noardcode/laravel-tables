<?php

namespace Noardcode\Tables\Traits;

use Noardcode\Tables\Collections\Collection;

trait CollectionEditable
{
    protected string $collection;

    /**
     * @param array $models
     *
     * @return Collection
     */
    public function newCollection(array $models = []): Collection
    {
        return new $this->collection($models);
    }

    /**
     * @param string $collection
     */
    public function setCollection(string $collection)
    {
        $this->collection = $collection;
        return $this;
    }
}
