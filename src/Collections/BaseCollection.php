<?php

namespace Noardcode\Tables\Collections;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class BaseCollection
 *
 * @package Noardcode\Tables\Collections
 */
abstract class BaseCollection extends Collection
{
    /**
     * @var array
     */
    public array $tableColumns = [];

    /**
     * @var array
     */
    public array $tableActions = [];

    /**
     * @var array
     */
    public array $tableRowActions = [];

    /**
     * BaseCollection constructor.
     *
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        parent::__construct($items);
    }
}
