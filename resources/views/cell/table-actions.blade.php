@foreach($tableActions as $action)
    @switch($action)
        @case('trash')
        @if ($trash)
            <a href="{{ route(preg_replace("/[a-z0-9]+$/i", $action, request()->route()->getName()), request()->route()->parameters()) }}"
               class="btn btn-sm btn-secondary ml-1" title="{{ __('noardcode::laravel-tables.Delete') }}">
                <i class="far fa-trash-alt"></i>
                {{ __('noardcode::laravel-tables.Delete') }}
            </a>
        @else
            <a href="{{ route(preg_replace("/[a-z0-9]+$/i", 'index', request()->route()->getName()), request()->route()->parameters()) }}"
               class="btn btn-sm btn-secondary ml-1" title="{{ __('noardcode::laravel-tables.Back') }}">
                {{ __('noardcode::laravel-tables.Back') }}
            </a>
        @endif
        @break
        @default
    @endswitch
@endforeach

