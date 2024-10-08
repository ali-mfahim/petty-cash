<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 pb-5">
                <div class="text-center mb-4">
                    <h1 class="role-title">Add New Role</h1>
                    <p>Set role permissions</p>
                    <div class="text-danger" id="error_message"></div>
                    <div class="text-success" style="font-weight: bold;text-transform: uppercase;font-size: 20px;" id="success_message"></div>
                </div>
                <!-- Add role form -->
                <form id="addRoleForm" class="row" onsubmit="return false" action="{{route('roles.store')}}">
                    @csrf
                    <div class="col-12">
                        <label class="form-label" for="roleName">Role Name <span class="text-danger">*</span> </label>
                        <input type="text" id="roleName" name="roleName" class="form-control" placeholder="Enter role name" tabindex="-1" data-msg="Please enter role name" />
                        <div id="roleName_error" class="text-danger"></div>
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
                                                            <input class="form-check-input child_checkbox" name="permissions[]" type="checkbox" value="{{$sub_permission->id}}" id="userManagementRead_{{$a}}_{{$index}}" />
                                                            <label class="form-check-label" for="userManagementRead_{{$a}}_{{$index}}"> {{ formatPermissionLabel($sub_permission->name) }} </label>
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
                <!--/ Add role form -->
            </div>
        </div>
    </div>
</div>