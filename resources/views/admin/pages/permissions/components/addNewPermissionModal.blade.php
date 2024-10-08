<div class="modal fade" id="addNewPermissionModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 pb-5">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Add New Permission</h1>
                    <p>Permissions you may use and assign to your users.</p>
                </div>
                <form id="addNewPermissionForm" class="row" onsubmit="return false">
                    @csrf
                    <div class="col-12">
                        <label class="form-label" for="modalPermissionName">Permission Group</label>
                        <input type="text" id="modalPermissionName" name="modalPermissionName" class="form-control" placeholder="Permission Group" autofocus data-msg="Please enter permission name" />
                        <div id="modalPermissionName_error" class="text-danger"></div>
                    </div>
                    <div class="col-12 mt-75">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll" />
                            <label class="form-check-label" for="selectAll"> Select All </label>
                        </div>
                    </div>
                    <div class="col-12 mt-75">
                        <div class="row mt-2">
                            @foreach (getDisplayNamePermission() as $item)
                            <div class="col-md-6  mb-2">
                                <div class="form-check">
                                    <input class="form-check-input child_checkbox" name="display_names[]" type="checkbox" value="{{ $item->name ?? null }}" id="{{ $item->name ?? null }}" />
                                    <label class="form-check-label" for="{{ $item->name ?? null }}"> <strong>{{ $item->name ?? null }}</strong></label>
                                </div>
                            </div>
                            @endforeach
                            <div id="display_names_error" class="text-danger"></div>
                        </div>
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