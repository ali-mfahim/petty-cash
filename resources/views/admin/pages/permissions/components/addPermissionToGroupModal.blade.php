<div class="modal fade" id="addPermissionToGroupModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 pb-5">
                <div class="text-center mb-2">
                    <h3 class="mb-1">Add Permission to <span id="permissionGroupNameAppend"></span></h3>
                    <p>Permissions you may use and assign to your users.</p>
                    <div id="permissionGroupId_error" class="text-danger"></div>
                </div>
                <form id="addPermissionToGroupnForm" class="row" onsubmit="return false" method="POST">
                    <input type="hidden" name="permissionGroupId" id="permissionGroupId" value="">
                    @csrf
                    <div class="col-12">
                        <label class="form-label" for="modalPermissionName">Permission Name</label>
                        <input type="text" id="modalPermissionName" name="modalPermissionName" class="form-control" placeholder="Permission Name" autofocus data-msg="Please enter permission name" />
                        <div id="modalPermissionName_error2" class="text-danger"></div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary mt-2 me-1">Save</button>
                        <button type="reset" class="btn btn-outline-secondary mt-2" data-bs-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>