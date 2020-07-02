<?php

namespace Noardcode\Tables\Components;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use Noardcode\Tables\Collections\BaseCollection;

class Table extends Component
{
    /**
     * @var \Noardcode\Tables\Collections\BaseCollection
     */
    private BaseCollection $collection;

    /**
     * @var bool
     */
    private bool $isTrash;

    /**
     * Create a new component instance.
     *
     * @param  \Noardcode\Tables\Collections\BaseCollection  $collection
     * @param  bool  $isTrash
     */
    public function __construct(BaseCollection $collection, bool $isTrash = false)
    {
        $this->collection = $collection;
        $this->isTrash = $isTrash;
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
     * @param  \Illuminate\Database\Eloquent\Model  $item
     * @param  string  $key
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Database\Eloquent\Model|\Illuminate\View\View|mixed
     */
    public function formatCell(Model $item, string $key)
    {
        $value = $this->getCellValue($item, $key);

        if (empty($this->collection->tableColumns[$key]['type'])) {
            return $value;
        }

        switch ($this->collection->tableColumns[$key]['type']) {
            case ('route'):
                return view('noardcode::cell.link')
                    ->with('link', route(
                        $this->collection->tableColumns[$key]['route'],
                        request()->route()->parameters() + [$item->id]
                    ))
                    ->with('value', $value);
                break;
            case ('date'):
                return $value->format(!empty($this->collection->tableColumns[$key]['date_format']) ?
                    $this->collection->tableColumns[$key]['date_format'] : 'd-m-Y H:i');
                break;
            case ('boolean'):
                return view('noardcode::cell.boolean')
                    ->with('bool', !!$value);
                break;
            case ('badge'):
                return view('noardcode::cell.badge')
                    ->with('items', $value instanceof Collection ? $value : [$value])
                    ->with('key', $this->getRelationValueKey($key));
                break;
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
            ->with('tableActions', $this->collection->tableActions)
            ->with('trash', !$this->isTrash);
    }

    /**
     * @param $item
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function parseRowActionButtons($item): Renderable
    {
        $baseRouteName = preg_replace("/[a-z0-9]+$/i", '', request()->route()->getName());
        $baseAction = [
            'title' => null,
            'btn_color' => null,
            'icon' => null,
            'route' => null,
        ];
        $actions = [];

        foreach ($this->collection->tableRowActions as $name => $options) {
            switch ($name) {
                case ('delete'):
                    if (!$this->isTrash) {
                        $actions[$name] = ([
                                'title' => 'general.Delete',
                                'btn_color' => 'danger',
                                'icon' => 'trash',
                                'route' => route(
                                    $baseRouteName . 'destroy',
                                    request()->route()->parameters() + [$item->id]
                                ),
                            ] + $baseAction);
                    }
                    break;
                case ('restore'):
                    if ($this->isTrash) {
                        $actions[$name] = ([
                                'title' => 'general.Restore',
                                'btn_color' => 'success',
                                'icon' => 'trash-restore',
                                'route' => route(
                                    $baseRouteName . 'restore',
                                    request()->route()->parameters() + [$item->id]
                                ),
                            ] + $baseAction);
                    }
                    break;
                case ('force-delete'):
                    if ($this->isTrash) {
                        $actions[$name] = ([
                                'title' => 'general.Force delete',
                                'btn_color' => 'danger',
                                'icon' => 'trash',
                                'route' => route(
                                    $baseRouteName . 'force-delete',
                                    request()->route()->parameters() + [$item->id]
                                ),
                            ] + $baseAction);
                    }
                    break;
                case ('edit'):
                    $actions[$name] = ([
                            'title' => 'general.Edit',
                            'icon' => 'pencil-alt',
                            'route' => route(
                                $baseRouteName . 'edit',
                                request()->route()->parameters() + [$item->id]
                            ),
                        ] + $baseAction);
                    break;
                case ('show'):
                    $actions[$name] = ([
                            'title' => 'general.Details',
                            'icon' => 'eye',
                            'route' => route(
                                $baseRouteName . 'show',
                                request()->route()->parameters() + [$item->id]
                            ),
                        ] + $baseAction);
                    break;
                default:
                    $actions[$name] = ([
                            'route' => route(
                                $options['route'],
                                request()->route()->parameters() + [$item->id]
                            )
                        ] + $options + $baseAction);
            }
        }

        return view('noardcode::cell.row-actions')
            ->with('actions', $actions)
            ->with('trash', $this->isTrash);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $item
     * @param  string  $key
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
     * @param  string  $key
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
}
