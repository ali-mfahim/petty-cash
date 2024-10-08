<div class="modal fade" id="createColorModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Add New Color</h1>
                    <p>Fill the form below to add new color.</p>
                    <div class="text-danger" id="error_message"></div>
                    <div class="text-success" style="font-weight: bold;text-transform: uppercase;font-size: 20px;" id="success_message"></div>
                </div>
                <form id="createColorForm" class="row gy-1 pt-75" onsubmit="return false">
                    @csrf
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="name">Color Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="-------" value="" data-msg="Please enter color name" />
                        <div id="name_error" class="text-danger"></div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="code">Color Picker</label>
                        <input type="color" id="code" name="code" class="form-control" placeholder="-------" value="" data-msg="Please pickup a color " style="height: 38px;" />
                        <div id="code_error" class="text-danger"></div>
                    </div>
                    <div class="col-md-12" style="margin-top: 20px;">
                        <label class="form-label" for="status">Status </label>
                        <select class="form-select" name="status" id="status">
                            <option value="1">Active</option>
                            <option value="2">Disable</option>
                        </select>
                        <div id="status_error" class="text-danger"></div>
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