<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Add New User</h1>
                    <p>Fill the form below to add new user.</p>
                    <div class="text-danger" id="error_message"></div>
                    <div class="text-success" style="font-weight: bold;text-transform: uppercase;font-size: 20px;" id="success_message"></div>
                </div>
                <form id="createUserForm" class="row gy-1 pt-75" onsubmit="return false">
                    @csrf
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="first_name">First Name <span class="text-danger">*</span></label>
                        <input type="text" id="first_name" name="first_name" class="form-control" placeholder="-------" value="" data-msg="Please enter your first name" />
                        <div id="first_name_error" class="text-danger"></div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" placeholder="-------" value="" data-msg="Please enter your last name" />
                        <div id="last_name_error" class="text-danger"></div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="email">Email: <span class="text-danger">*</span></label>
                        <input type="text" id="email" name="email" class="form-control" value="" placeholder="-------" />
                        <div id="email_error" class="text-danger"></div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="phone">Contact</label>
                        <input type="number" id="phone" name="phone" class="form-control phone-number-mask" placeholder="-------" value="" />
                        <div id="phone_error" class="text-danger"></div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                        <input type="text" id="password" name="password" class="form-control modal-edit-tax-id" placeholder="*****" value="" />
                        <div id="password_error" class="text-danger"></div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="role">Role <span class="text-danger">*</span></label>
                        <select class="form-select" name="role" id="role">
                            <option value="">--------</option>
                            @if(!empty(getRolesList()))
                            @foreach(getRolesList() as $i => $v)
                            <option value="{{$v->id ?? ''}}">{{$v->display_name ?? ''}}</option>
                            @endforeach
                            @endif

                        </select>
                        <div id="role_error" class="text-danger"></div>
                    </div>
                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="submit" class="btn btn-primary me-1">Save</button>
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>