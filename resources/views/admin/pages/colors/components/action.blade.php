<div class="btn-group dropstart">
    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item edit-color-btn " href="javascript:;" data-color-id="{{$color->id}}">Edit</a>
        <a class="dropdown-item delete-color-btn" href="javascript:;" data-color-id="{{$color->id}}" data-route="{{route('colors.destroy' , $color->id)}}"> Delete</a>
    </div>
</div>