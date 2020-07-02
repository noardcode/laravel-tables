@foreach($items as $item)
    <span class="badge" style="{{ $item->color ? 'background-color:' . $item->color . ';' : '' }}"><div>{{ $item->$key }}</div></span>
@endforeach

