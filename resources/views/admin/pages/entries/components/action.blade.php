<div class="btn-group dropstart">
    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item edit-user-btn " href="javascript:;" edit-user-btn data-user-id="{{$user->id}}"> Edit</a>
        <!-- <a class="dropdown-item disabled" href="#">Option 2</a> -->
        <a class="dropdown-item delete-user-btn" href="javascript:;" data-user-id="{{$user->id}}" data-route="{{route('users.destroy' , $user->id)}}"> Delete</a>
    </div>
</div>