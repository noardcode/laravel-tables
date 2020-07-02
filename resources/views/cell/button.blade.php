@if($item->{$key})
    <div class="badge p-2 badge-success">
        <i class="fas fa-check"></i>
    </div>
@else
    <div class="badge p-2 badge-danger">
        <i class="fas fa-times"></i>
    </div>
@endif
