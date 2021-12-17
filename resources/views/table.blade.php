<table class="{{ config('laravel-tables.css.table') }}">
    <thead>
        <tr>
            @if($collection->isSelectable() === true)
                <th>
                    <input type="checkbox" class="all-row-select">
                </th>
            @endif

            @foreach ($collection->getTableColumns() as $key => $column)
                <th data-column="{{ $key }}"
                    {!! !empty($column['class']) ? (' class="' . htmlspecialchars($column['class'] instanceof Closure ? $column['class'](null) : $column['class']) . '"') : '' !!}>
                    {{ $column['title'] }}
                    @if(!empty($column['sortable']))
                        <a href="{{ $getSortableRoute($collection->getSortableRoute(), $key) }}">
                            <i class="fas fa-sort"></i>
                        </a>
                    @endif
                </th>
            @endforeach
            @if(!empty($collection->getRowActions()) || !empty($collection->getTableActions()))
                <th class="text-right">
                    {{ $parseActionButtons }}
                </th>
            @endif
        </tr>
    </thead>

    <tbody>
        @forelse($collection as $item)
            <tr data-primary-key="{{ $item->getKey() }}">

                {{-- Make rows selectable when requested in the collection. --}}
                @if($collection->isSelectable() === true)
                    <td class="small-column">
                        <input type="checkbox" class="row-select" value="{{ $item->getKey() }}" style="margin-top: 7px; float: left;">
                    </td>
                @endif

                @foreach($collection->getTableColumns() as $key => $column)
                    <td data-column="{{ $key }}"
                        {!! !empty($column['class']) ? (' class="' . htmlspecialchars($column['class'] instanceof Closure ? $column['class']($item) : $column['class']) . '"') : '' !!}>
                        {!! $formatCell($item, $key, $column) !!}
                    </td>
                @endforeach
                @if(!empty($collection->getRowActions()))
                    <td class="text-right">
                        {!! $parseRowActionButtons($item) !!}
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($collection->getTableColumns()) + 1 }}">
                    {{ __("noardcode::laravel-tables.No items found.") }}
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@if(method_exists($collection, 'links'))
    <div class="d-flex justify-content-center">
        {{ $collection->links() }}
    </div>
@endif
