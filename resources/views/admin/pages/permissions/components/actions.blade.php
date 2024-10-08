<div class="btn-group dropstart">
    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item add-permission-in-group-btn " href="javascript:;" data-permission-id="{{$permission->id}}" data-group-name="{{$permission->group ?? ''}}"> Add Permission </a>
    </div>
</div>