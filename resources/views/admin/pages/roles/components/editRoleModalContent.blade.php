<form id="editRoleModalForm" class="row" onsubmit="return false">
    @csrf

    <input type="hidden" value="{{route('roles.update' , $role->id)}}" id="update-role-route">

    <div class="col-12">
        <label class="form-label" for="roleName">Role Name <span class="text-danger">*</span> </label>
        <input type="text" id="update_roleName" name="roleName" value="{{$role->name ?? ''}}" class="form-control" placeholder="Enter role name" tabindex="-1" data-msg="Please enter role name"  readonly/>
        <div id="update_roleName_error" class="text-danger"></div>
    </div>
    <div class="col-12">
        <h4 class="mt-2 pt-50">Role Permissions</h4>
        <!-- Permission table -->
        <div class="">
            <table class="table table-flush-spacing">
                <tbody>
                    <tr>
                        <td class="text-nowrap fw-bolder">
                            Administrator Access
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system">
                                <i data-feather="info"></i>
                            </span>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll" />
                                <label class="form-check-label" for="selectAll"> Select All </label>
                            </div>
                        </td>

                    </tr>
                    @if (isset($permissions) && !empty($permissions))
                    @foreach ($permissions as $index => $permission)
                    <tr>
                        <td class="text-nowrap fw-bolder">{{ isset($permission->group) && !empty($permission->group) ? ucfirst($permission->group) : '-' }} </td>
                        <td>
                            <div class="d-flex">
                                <div class="row">
                                    @foreach (groupPermissions($permission->group) as $a => $sub_permission)
                                    <div class="col-md-6" style="margin-bottom: 10px;">
                                        <div class="form-check me-3 me-lg-5">
                                            <input class="form-check-input child_checkbox" name="permissions[]" type="checkbox" value="{{$sub_permission->id}}" id="userManagementRead_{{$sub_permission->id}}" @if(isset($permissions_names) && !empty($permissions_names) && in_array( $sub_permission->id , $permissions_names )) checked @endif />
                                            <label class="form-check-label" for="userManagementRead_{{$sub_permission->id}}"> {{ formatPermissionLabel($sub_permission->name) }} </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif

                </tbody>
            </table>
        </div>
        <!-- Permission table -->
    </div>
    <div class="col-12 text-center mt-2">
        <button type="submit" class="btn btn-primary me-1">Save</button>
        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
            Cancle
        </button>
    </div>
</form>