<div class="modal fade" id="createStoreModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg  modal-dialog-centered modal-edit-store">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50" style="text-align: left;">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Add New Store</h1>
                    <p>Fill the form below to add new store.</p>
                    <div class="text-danger" id="error_message"></div>
                    <div class="text-success" style="font-weight: bold;text-transform: uppercase;font-size: 20px;" id="success_message"></div>
                </div>
                <form id="createStoreForm" class="row gy-1 pt-75" onsubmit="return false" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12" style="text-align: center;">
                        <label class="form-label" for="image">Logo </label>
                        <div class="thumbnail-container">
                            <img id="thumbnail" src="{{asset('upload-icon.png')}}" alt="Thumbnail" style="cursor: pointer;max-width: 20%;box-shadow: 0px 0px 15px -3px black;margin-top: 20px;margin-bottom: 20px;" />
                            <input type="file" id="image" name="logo" class="form-control" style="display: none;" />
                        </div>
                        <div id="image_error" class="text-danger"></div>
                    </div>
                    <div class="d-flex justify-content-center d-none" id="image_spinner">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="col-md-6" style="margin-top: 20px;">
                        <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="-------" value="" data-msg="Please enter store name" />
                        <div id="name_error" class="text-danger"></div>
                    </div>


                    <div class="col-md-6" style="margin-top: 20px;">
                        <label class="form-label" for="domain">Domain <span class="text-danger">*</span></label>
                        <input type="text" id="domain" name="domain" class="form-control" placeholder="-------" value="" data-msg="Please enter store domain" />
                        <div id="domain_error" class="text-danger"></div>
                    </div>



                    <div class="col-md-6" style="margin-top: 20px;">
                        <label class="form-label" for="base_url">Base URL <span class="text-danger">*</span></label>
                        <input type="url" id="base_url" name="base_url" class="form-control" placeholder="-------" value="" data-msg="Please enter store base_url" />
                        <div id="base_url_error" class="text-danger"></div>
                    </div>

                    <div class="col-md-6" style="margin-top: 20px;">
                        <label class="form-label" for="api_url">API URL <span class="text-danger">*</span></label>
                        <input type="url" style="opacity:.6" id="api_url" name="api_url" class="form-control" placeholder="-------" value="" data-msg="Please enter store api_url" readonly />
                        <div id="api_url_error" class="text-danger"></div>
                        <small class="opacity:.6">This will be generated from base url</small>
                    </div>



                    <div class="col-md-12" style="margin-top: 20px;width: 50%;margin: 39px auto;">
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