@canany(["app-edit" , "app-delete"])

<div class="btn-group dropstart">
    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
    </button>
    <div class="dropdown-menu">

        @can("app-edit")
        <a class="dropdown-item edit-app-btn " href="javascript:;" data-app-id="{{$record->id}}"> Edit</a>
        @endcan


        @can("app-delete")
        <a class="dropdown-item delete-app-btn" href="javascript:;" data-app-id="{{$record->id}}" data-route="{{route('stores.deleteApp' , $record->id)}}"> Delete</a>
        @endcan


    </div>
</div>
@endcan