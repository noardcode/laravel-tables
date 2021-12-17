<div class="btn-group">
        <div class="dropdown">
            <a class="btn btn-sm btn-primary dropdown-toggle btn-select disabled" href="#" role="button" id="dropdownMenuLink"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-angle-down mr-1"></i> {{ $title }}
            </a>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                @foreach ($items as $item)
                    <a class="dropdown-item btn-select-item" href="{{ route($item['route']) }}">
                        {{ $item['title'] }}
                    </a>
                @endforeach
            </div>

        </div>
</div>
