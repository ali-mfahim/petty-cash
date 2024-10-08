<div class="btn-group dropstart">
    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item view-customer-btn " href="javascript:;" data-customer-id="{{$record->id}}" data-route="{{route('customers.show' , $record->id)}}">View</a>
    </div>
</div>