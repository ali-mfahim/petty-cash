<form id="editStoreForm" class="row gy-1 pt-75" onsubmit="return false" method="PUT" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" value="{{ route('stores.update', $store->id) }}" id="update-store-route">
    <div class="col-md-12" style="text-align: center;">
        <label class="form-label" for="image">Logo</label>
        <div class="thumbnail-container">
            @if(isset($store->logo) && !empty($store->logo) && checkFileExists($store->logo, config('project.upload_path.store_logo')) == true)
            <img id="update_thumbnail" src="{{asset(config('project.upload_path.store_logo') . $store->logo)}}" alt="Thumbnail" style="cursor: pointer;max-width: 50%;box-shadow: 0px 0px 15px -3px black;margin-top: 20px;margin-bottom: 20px;" />
            @else
            <img id="update_thumbnail" src="{{asset('upload-icon.png')}}" alt="Thumbnail" style="cursor: pointer;max-width: 50%;box-shadow: 0px 0px 15px -3px black;margin-top: 20px;margin-bottom: 20px;" />
            @endif
            <input type="file" id="update_image" name="logo" class="form-control" style="display: none;" />
        </div>
        <div id="image_error" class="text-danger"></div>
    </div>
    <div class="d-flex justify-content-center d-none" id="update_image_spinner">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>



    <div class="col-md-6" style="margin-top: 20px;">
        <label class="form-label" for="update_name">Name <span class="text-danger">*</span></label>
        <input type="text" id="update_name" name="name" class="form-control" placeholder="-------" value="{{$store->name ?? ''}}" data-msg="Please enter store name" />
        <div id="update_name_error" class="text-danger"></div>
    </div>


    <div class="col-md-6" style="margin-top: 20px;">
        <label class="form-label" for="update_domain">Domain <span class="text-danger">*</span></label>
        <input type="text" id="update_domain" name="domain" class="form-control" placeholder="-------" value="{{$store->domain ?? ''}}" data-msg="Please enter store domain" />
        <div id="update_domain_error" class="text-danger"></div>
    </div>



    <div class="col-md-6" style="margin-top: 20px;">
        <label class="form-label" for="update_base_url">Base URL <span class="text-danger">*</span></label>
        <input type="url" id="update_base_url" name="base_url" class="form-control" placeholder="-------" value="{{$store->base_url ?? ''}}" data-msg="Please enter store base_url" />
        <div id="update_base_url_error" class="text-danger"></div>
    </div>

    <div class="col-md-6" style="margin-top: 20px;">
        <label class="form-label" for="update_api_url">API URL <span class="text-danger">*</span></label>
        <input type="url" style="opacity:.6" id="update_api_url" name="api_url" class="form-control" placeholder="-------" value="{{$store->api_url ?? ''}}" data-msg="Please enter store api_url" readonly />
        <div id="update_api_url_error" class="text-danger"></div>
        <small class="opacity:.6">This will be generated from base url</small>
    </div>

    <div class="col-md-12" style="margin-top: 20px;width: 50%;margin: 39px auto;">
        <label class="form-label" for="status">Status </label>
        <select class="form-select" name="status" id="update_status">
            <option value="1" @if(isset($store->status) && !empty($store->status) && $store->status == 1) selected @endif>Active</option>
            <option value="2" @if(isset($store->status) && !empty($store->status) && $store->status == 2) selected @endif>Disable</option>
        </select>
        <div id="update_status_error" class="text-danger"></div>
    </div>
    <div class="col-12 text-center mt-2 pt-50">
        <button type="submit" class="btn btn-primary me-1">Update</button>
        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
            Cancel
        </button>
    </div>
</form>