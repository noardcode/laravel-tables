<table class="table table-hover mb-0">
    <thead>
        <tr>
            @foreach ($collection->getTableColumns() as $column)
                <th>{{ $column['title'] }}</th>
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
            <tr>
                @foreach($collection->getTableColumns() as $key => $column)
                    <td>
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
