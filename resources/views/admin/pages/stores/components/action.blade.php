<div class="btn-group dropstart">
    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item view-apps-btn " href="javascript:;" data-store-id="{{$record->id}}"> Apps </a>
        <a class="dropdown-item edit-store-btn " href="javascript:;" data-store-id="{{$record->id}}"> Edit</a>
        <a class="dropdown-item delete-store-btn" href="javascript:;" data-store-id="{{$record->id}}" data-route="{{route('stores.destroy' , $record->id)}}"> Delete</a>
    </div>
</div>