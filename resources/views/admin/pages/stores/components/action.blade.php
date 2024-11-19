@canany(["app.list","store-edit","store.delete"])
<div class="btn-group dropstart">
    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
    </button>
    <div class="dropdown-menu">


        @can("app-list")
        <a class="dropdown-item view-apps-btn " @if(isset($record->slug) && !empty($record->slug)) href="{{ route('stores.apps' , $record->slug) }}" target="_blank" @else href="javascipt:;" @endif > Apps </a>
        @endcan


        @can("store-edit")
        <a class="dropdown-item edit-store-btn " href="javascript:;" data-store-id="{{$record->id}}"> Edit</a>
        @endcan


        @can("store-delete")
        <a class="dropdown-item delete-store-btn" href="javascript:;" data-store-id="{{$record->id}}" data-route="{{route('stores.destroy' , $record->id)}}"> Delete</a>
        @endcan

        @can("store-collections")
        <a class="dropdown-item import-collection-btn" href="javascript:;" data-store-id="{{$record->id}}"  > Import Collections </a>
        @endcan


    </div>
</div>
@endcanany