<?php

namespace Noardcode\Tables\Components;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use Noardcode\Tables\Collections\Collection;

class Table extends Component
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
    private array $columns;

    /**
     * @var array
     */
    private array $tableActions;

    /**
     * @var array
     */
    private array $rowActions;

    /**
     * Table constructor.
     *
     * @param iterable $collection
     * @param bool $isTrash
     */
    public function __construct(iterable $collection, bool $isTrash = false)
    {
        $this->collection = $collection;
        $this->isTrash = $isTrash;
        $this->columns = $this->collection->getTableColumns();
        $this->tableActions = $this->collection->getTableActions();
        $this->rowActions = $this->collection->getRowActions();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function render(): Renderable
    {
        return view('noardcode::table')
            ->with('collection', $this->collection)
            ->with('isTrash', $this->isTrash);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $item
     * @param string $key
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Database\Eloquent\Model|\Illuminate\View\View|mixed
     */
    public function formatCell(Model $item, string $key)
    {
        $value = $this->getCellValue($item, $key);

        if (empty($this->columns[$key]['type'])) {
            return $value;
        }

        switch ($this->columns[$key]['type']) {
            case ('route'):
                return view('noardcode::cell.link')
                    ->with('link', route(
                        $this->columns[$key]['route'],
                        request()->route()->parameters() + [$item->id]
                    ))
                    ->with('value', $value);
            case ('date'):
                return optional($value)->format(!empty($this->columns[$key]['date_format']) ?
                    $this->columns[$key]['date_format'] : 'd-m-Y H:i');
            case ('boolean'):
                return view('noardcode::cell.boolean')
                    ->with('bool', !!$value);
            case ('badge'):
                return view('noardcode::cell.badge')
                    ->with('items', $value instanceof Collection ? $value : [$value])
                    ->with('key', $this->getRelationValueKey($key));
            case ('html'):
                return view('noardcode::cell.html')
                    ->with('value', $value);
            case ('view'):
                return view($this->columns[$key]['view'])
                    ->with('value', $value)
                    ->with('item', $item);
            default:
                return $value;
        }
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function parseActionButtons(): Renderable
    {
        return view('noardcode::cell.table-actions')
            ->with('tableActions', $this->tableActions)
            ->with('trash', !$this->isTrash);
    }

    /**
     * @param $item
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function parseRowActionButtons(Model $item): Renderable
    {
        $this->baseRouteName = preg_replace("/[a-z0-9]+$/i", '', request()->route()->getName());
        $actions = [];

        foreach ($this->rowActions as $name => $options) {
            $options = is_bool($options) ? [] : $options;

            switch ($name) {
                case ('delete'):
                    if (!$this->isTrash) {
                        $actions[$name] = $this->getRowActions(
                            $item,
                            $options,
                            __('noardcode::laravel-tables.Delete'),
                            'danger',
                            'trash',
                            'destroy'
                        );
                    }
                    break;
                case ('restore'):
                    if ($this->isTrash) {
                        $actions[$name] = $this->getRowActions(
                            $item,
                            $options,
                            __('noardcode::laravel-tables.Restore'),
                            'success',
                            'trash-restore',
                            'restore'
                        );
                    }
                    break;
                case ('force-delete'):
                    if ($this->isTrash) {
                        $actions[$name] = $this->getRowActions(
                            $item,
                            $options,
                            __('noardcode::laravel-tables.Force delete'),
                            'danger',
                            'trash',
                            'force-delete'
                        );
                    }
                    break;
                case ('edit'):
                    $actions[$name] = $this->getRowActions(
                        $item,
                        $options,
                        __('noardcode::laravel-tables.Edit'),
                        null,
                        'pencil-alt',
                        'edit'
                    );
                    break;
                case ('show'):
                    $actions[$name] = $this->getRowActions(
                        $item,
                        $options,
                        __('noardcode::laravel-tables.Details'),
                        null,
                        'eye',
                        'show'
                    );
                    break;
                default:
                    $actions[$name] = $this->getRowActions($item, $options);
            }
        }

        return view('noardcode::cell.row-actions')
            ->with('actions', $actions)
            ->with('trash', $this->isTrash);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $item
     * @param string $key
     *
     * @return \Illuminate\Database\Eloquent\Model|mixed
     */
    private function getCellValue(Model $item, string $key)
    {
        if (strpos($key, '.')) {
            $value = array_reduce(explode('.', $key), function ($o, $p) {
                if ($o instanceof Collection) {
                    return $o;
                } else {
                    return is_numeric($p) ? ($o[$p] ?? null) : ($o->$p ?? null);
                }
            }, $item);
        } else {
            $value = $item->{$key};
        }

        return $value;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function getRelationValueKey(string $key): string
    {
        if (strpos($key, '.') !== false) {
            $key = explode('.', $key);
            $key = $key[count($key) - 1];
        }

        return $key;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $item
     * @param array $options
     * @param string|null $title
     * @param string|null $btnColor
     * @param string|null $icon
     * @param string|null $route
     *
     * @return array
     */
    private function getRowActions(
        Model $item,
        array $options,
        string $title = null,
        string $btnColor = null,
        string $icon = null,
        string $route = null
    ): array {
        /** Check if custom route has been defined*/
        if (!empty($options['route'])) {
            $options['route'] = route($options['route'], $this->getRouteParams($item));
        }

        /** Check if closure is set and action shoulf be enabled */
        if(!empty($options['closure'])) {
            $options['enabled'] = $options['closure']($item);
            unset($options['closure']);
        } else {
            $options['enabled'] = true;
        }

        return $options + [
                'title' => $title,
                'btn_color' => $btnColor,
                'icon' => $icon,
                'route' => empty($options['route'])
                    ? route($this->baseRouteName . $route, $this->getRouteParams($item))
                    : null,
            ];
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $item
     *
     * @return array|null
     */
    private function getRouteParams(Model $item): ?array
    {
        return request()->route()->parameters() + [$item->getKey()];
    }

    public function getSortableRoute(string $routeName, string $column)
    {
        return route($routeName, request()->route()->parameters() + [$column]);
    }
}
