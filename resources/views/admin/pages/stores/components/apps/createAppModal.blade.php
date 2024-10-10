<div class="modal fade" id="createAppModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg  modal-dialog-centered modal-edit-app">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50" style="text-align: left;">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Add New App</h1>
                    <p>Fill the form below to add new app.</p>
                    <div class="text-danger" id="error_message"></div>
                    <div class="text-success" style="font-weight: bold;text-transform: uppercase;font-size: 20px;" id="success_message"></div>
                </div>
                <form id="createAppForm" class="row gy-1 pt-75" onsubmit="return false" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="store_id" value="{{$store->id ?? ''}}">
                    <div class="col-md-6" style="margin-top: 20px;">
                        <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="-------" value="" data-msg="Please enter store name" />
                        <div id="name_error" class="text-danger"></div>
                    </div>


                    <div class="col-md-6" style="margin-top: 20px;">
                        <label class="form-label" for="app_key">App Key <span class="text-danger">*</span></label>
                        <input type="text" id="app_key" name="app_key" class="form-control" placeholder="-------" value="" data-msg="Please enter store app_key" />
                        <div id="app_key_error" class="text-danger"></div>
                    </div>



                    <div class="col-md-6" style="margin-top: 20px;">
                        <label class="form-label" for="app_secret">App Secret <span class="text-danger">*</span></label>
                        <input type="text" id="app_secret" name="app_secret" class="form-control" placeholder="-------" value="" data-msg="Please enter store app_secret" />
                        <div id="app_secret_error" class="text-danger"></div>
                    </div>

                    <div class="col-md-6" style="margin-top: 20px;">
                        <label class="form-label" for="access_token">Access Token <span class="text-danger">*</span></label>
                        <input type="text" id="access_token" name="access_token" class="form-control" placeholder="-------" value="" data-msg="Please enter store access_token" />
                        <div id="access_token_error" class="text-danger"></div>
                    </div>
                    <div class="col-md-6" style="margin-top: 20px;">
                        <label class="form-label" for="api_version">Api Version<span class="text-danger">*</span></label>
                        <input type="text" id="api_version" name="api_version" class="form-control" placeholder="-------" value="" data-msg="Please enter store api_version" />
                        <div id="api_version_error" class="text-danger"></div>
                    </div>

                    <div class="col-md-6" style="margin-top: 20px;">
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