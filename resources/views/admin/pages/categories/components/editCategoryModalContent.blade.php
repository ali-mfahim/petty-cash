<form id="editCategoryForm" class="row gy-1 pt-75" onsubmit="return false" method="PUT"  enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" value="{{ route('categories.update', $category->id) }}" id="update-category-route">
    <div class="col-md-12">
        <label class="form-label" for="image">Image</label>
        <div class="thumbnail-container">
            @if(isset($category->image) && !empty($category->image) && checkFileExists($category->image, config('project.upload_path.categories')) == true)
            <img id="update_thumbnail" src="{{asset(config('project.upload_path.categories') . $category->image)}}" alt="Thumbnail" style="cursor: pointer;max-width: 50%;box-shadow: 0px 0px 15px -3px black;margin-top: 20px;margin-bottom: 20px;" />
            @else
            <img id="update_thumbnail" src="{{asset('upload-icon.png')}}" alt="Thumbnail" style="cursor: pointer;max-width: 50%;box-shadow: 0px 0px 15px -3px black;margin-top: 20px;margin-bottom: 20px;" />
            @endif
            <input type="file" id="update_image" name="image" class="form-control" style="display: none;" />
        </div>
        <div id="image_error" class="text-danger"></div>
    </div>
    <div class="d-flex justify-content-center d-none" id="update_image_spinner">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="col-md-12" style="margin-top: 20px;">
        <label class="form-label" for="update_title">Title <span class="text-danger">*</span></label>
        <input type="text" id="update_title" name="title" class="form-control" placeholder="-------" value="{{$category->title ?? ''}}" data-msg="Please enter category title" />
        <div id="update_title_error" class="text-danger"></div>
    </div>

    <div class="col-md-12" style="margin-top: 20px;">
        <label class="form-label" for="descripton">Description </label>
        <textarea id="descripton" name="descripton" class="form-control" cols="30" rows="10" data-msg="Please enter category descripton">{{$category->description ?? ''}}</textarea>
        <div id="descripton" class="text-danger"></div>
    </div>

    <div class="col-md-12" style="margin-top: 20px;">
        <label class="form-label" for="status">Status </label>
        <select class="form-select" name="status" id="status">
            <option value="1" @if(isset($category->status) && !empty($category->status) && $category->status == 1) selected @endif>Active</option>
            <option value="2" @if(isset($category->status) && !empty($category->status) && $category->status == 2) selected @endif>Disable</option>
        </select>
        <div id="status_error" class="text-danger"></div>
    </div>
    <div class="col-12 text-center mt-2 pt-50">
        <button type="submit" class="btn btn-primary me-1">Update</button>
        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
            Cancel
        </button>
    </div>
</form>