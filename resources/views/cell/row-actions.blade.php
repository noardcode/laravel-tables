@if(!empty($actions))

    <div class="btn-group">
        @foreach($actions as $name => $options)
            @if($name == 'delete')
                {{ Form::open(['method' => $trash ? 'PUT' : 'DELETE', 'url' => $actions['delete']['route'], 'class' => 'delete'])}}

                <button title="{{ $options['title'] }}" class="btn btn-sm btn-{{ $options['btn_color'] ?? 'primary' }}"{{ $options['enabled'] ?: ' disabled ' }}
                        @if(!$trash)onclick="return confirm('{{ __('noardcode::laravel-tables.Are you sure you want to delete this item?') }}')"@endif>
                    @if($options['icon'])
                        <i class="fas fa-{{ $options['icon'] }}" title="{{ $options['title'] }}"></i>
                        <div class="sr-only">
                            {{ $options['title'] }}
                        </div>
                    @else
                        {{ $options['title'] }}
                    @endif
                </button>
                {{ Form::close() }}
            @elseif($name == 'force-delete')
                {{ Form::open(['method'  => 'DELETE', 'url' => $actions['force-delete']['route'], 'class' => 'delete'])}}

                <button title="{{ $options['title'] }}" class="btn btn-sm btn-{{ $options['btn_color'] ?? 'primary' }}"
                        onclick="return confirm('{{ __('noardcode::laravel-tables.Are you sure you want to permanently delete this item?') }}')"{{ $options['enabled'] ?: ' disabled' }}>
                    @if($options['icon'])
                        <i class="fas fa-{{ $options['icon'] }}" title="{{ $options['title'] }}"></i>
                        <div class="sr-only">
                            {{ $options['title'] }}
                        </div>
                    @else
                        {{ $options['title'] }}
                    @endif
                </button>
                {{ Form::close() }}
            @elseif(!$trash)
                <a href="{{  $options['enabled'] ? ($options['route'] ?? '#') : 'javascript:void(0)'}}" title="{{ $options['title'] }}"
                   class="btn btn-sm btn-{{ $options['btn_color'] ?? 'primary' }} {{ $options['enabled'] ?: 'disabled' }}">
                    @if($options['icon'])
                        <i class="fas fa-{{ $options['icon'] }}" title="{{ $options['title'] }}"></i>
                        <div class="sr-only">
                            {{ $options['title'] }}
                        </div>
                    @else
                        {{ $options['title'] }}
                    @endif
                </a>
            @elseif($trash && $name == 'restore')
                {{ Form::open(['method'  => 'PUT', 'url' => $actions['restore']['route'], 'class' => 'delete'])}}
                <button title="{{ $options['title'] }}" class="btn btn-sm btn-{{ $options['btn_color'] ?? 'primary' }}"{{ $options['enabled'] ?: ' disabled' }}>
                    @if($options['icon'])
                        <i class="fas fa-{{ $options['icon'] }}" title="{{ $options['title'] }}"></i>
                        <div class="sr-only">
                            {{ $options['title'] }}
                        </div>
                    @else
                        {{ $options['title'] }}
                    @endif
                </button>
                {{ Form::close() }}
            @endif
        @endforeach
    </div>
@endif
