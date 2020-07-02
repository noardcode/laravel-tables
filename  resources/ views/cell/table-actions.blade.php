@foreach($tableActions as $action)
    @switch($action)
        @case('trash')
        @if ($trash)
            <a href="{{ route(preg_replace("/[a-z0-9]+$/i", $action, request()->route()->getName()), request()->route()->parameters()) }}"
               class="btn btn-sm btn-secondary ml-1" title="Deleted">
                <i class="far fa-trash-alt"></i>
                {{ __('Deleted') }}
            </a>
        @else
            <a href="{{ route(preg_replace("/[a-z0-9]+$/i", 'index', request()->route()->getName()), request()->route()->parameters()) }}"
               class="btn btn-sm btn-secondary ml-1" title="Back">
                {{ __('general.Back') }}
            </a>
        @endif
        @break
        @default
    @endswitch
@endforeach

