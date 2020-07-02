<table class="table table-hover mb-0">
    <thead>
        <tr>
            @foreach ($collection->tableColumns as $column)
                <th>{{ $column['title'] }}</th>
            @endforeach
            @if(!empty($collection->tableRowActions) || !empty($collection->tableActions))
                <th class="text-right">
                    {{ $parseActionButtons }}
                </th>
            @endif
        </tr>
    </thead>

    <tbody>
        @forelse($collection as $item)
            <tr>
                @foreach($collection->tableColumns as $key => $column)
                    <td>
                        {!! $formatCell($item, $key, $column) !!}
                        @if($key == 'button')
                            <a href="{{ route('admin.attractions.show', $item->id) }}">
                                <button type="button" class="btn btn-va">Show</button>
                            </a>
                        @endif
                    </td>
                @endforeach
                @if(!empty($collection->tableRowActions))
                    <td class="text-right">
                        {!! $parseRowActionButtons($item) !!}
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($collection->tableColumns) + 1 }}">
                    {{ __('general.No items found.') }}
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
