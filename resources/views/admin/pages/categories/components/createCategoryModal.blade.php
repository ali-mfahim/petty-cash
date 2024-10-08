<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm  modal-dialog-centered modal-edit-Category">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50" style="text-align: center;">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Add New Category</h1>
                    <p>Fill the form below to add new category.</p>
                    <div class="text-danger" id="error_message"></div>
                    <div class="text-success" style="font-weight: bold;text-transform: uppercase;font-size: 20px;" id="success_message"></div>
                </div>
                <form id="createCategoryForm" class="row gy-1 pt-75" onsubmit="return false" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12">
                        <label class="form-label" for="image">Image </label>
                        <div class="thumbnail-container">
                            <img id="thumbnail" src="{{asset('upload-icon.png')}}" alt="Thumbnail" style="cursor: pointer;max-width: 50%;box-shadow: 0px 0px 15px -3px black;margin-top: 20px;margin-bottom: 20px;" />
                            <input type="file" id="image" name="image" class="form-control" style="display: none;" />
                        </div>
                        <div id="image_error" class="text-danger"></div>
                    </div>
                    <div class="d-flex justify-content-center d-none" id="image_spinner">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 20px;">
                        <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="-------" value="" data-msg="Please enter category title" />
                        <div id="title_error" class="text-danger"></div>
                    </div>

                    <div class="col-md-12" style="margin-top: 20px;">
                        <label class="form-label" for="descripton">Description </label>
                        <textarea id="descripton" name="description" class="form-control" cols="30" rows="10" data-msg="Please enter category descripton"></textarea>
                        <div id="descripton" class="text-danger"></div>
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