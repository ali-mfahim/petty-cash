<form id="editUserForm" class="row gy-1 pt-75" onsubmit="return false" method="PUT">

    @csrf

    <input type="hidden" value="{{ route('users.update', $user->id) }}" id="update-user-route">

    <div class="col-12 col-md-6">
        <label class="form-label" for="first_name">First Name<span class="text-danger">*</span></label>
        <input type="text" id="update_first_name" value="{{$user->first_name ?? ''}}" name="first_name" class="form-control" placeholder="-------" data-msg="Please enter your first name" />
        <div id="update_first_name_error" class="text-danger"></div>
    </div>
    <div class="col-12 col-md-6">
        <label class="form-label" for="last_name">Last Name</label>
        <input type="text" id="update_last_name" value="{{$user->last_name ?? ''}}" name="last_name" class="form-control" placeholder="-------" data-msg="Please enter your last name" />
        <div id="update_last_name_error" class="text-danger"></div>
    </div>
    <div class="col-12 col-md-6">
        <label class="form-label" for="email">Email: <span class="text-danger">*</span></label>
        <input type="text" id="update_email" value="{{$user->email ?? ''}}" name="email" class="form-control" placeholder="-------" />
        <div id="update_email_error" class="text-danger"></div>
    </div>
    <div class="col-12 col-md-6">
        <label class="form-label" for="phone">Contact</label>
        <input type="number" id="update_phone" value="{{$user->phone ?? ''}}" name="phone" class="form-control phone-number-mask" placeholder="-------" />
        <div id="update_phone_error" class="text-danger"></div>
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="password">Update Password </label>

        <input type="text" id="update_password" value="" name="password" class="form-control modal-edit-tax-id" placeholder="*****" />
        <small class="text-warning">Leave this filed empty if you don't want to update the password</small>
        <div id="update_password_error" class="text-danger"></div>
    </div>
    <div class="col-12 col-md-6">
        <label class="form-label" for="role">Role <span class="text-danger">*</span></label>
        <select class="form-select" name="role" id="role">
            <option value="">--------</option>
            @if(!empty(getRolesList()))
            @foreach(getRolesList() as $i => $v)
            <option value="{{$v->id ?? ''}}" @if(isset($role->id) && !empty($role->id) && $role->id == $v->id) selected @endif>{{$v->display_name ?? ''}} </option>
            @endforeach
            @endif

        </select>
        <div id="role_error" class="text-danger"></div>
    </div>
    <div class="col-12 text-center mt-2 pt-50">
        <button type="submit" class="btn btn-primary me-1" id="updateUserBtn">Update</button>
        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
            Cancel
        </button>
    </div>
</form>