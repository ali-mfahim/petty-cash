<form id="editAppForm" class="row gy-1 pt-75" onsubmit="return false" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" value="{{ route('stores.updateApp', $app->id) }}" id="update-app-route">

    <div class="col-md-6" style="margin-top: 20px;">
        <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
        <input type="text" id="update_name" name="name" class="form-control" placeholder="-------" value="{{$app->app_name ?? ''}}" data-msg="Please enter store name" />
        <div id="update_name_error" class="text-danger"></div>
    </div>


    <div class="col-md-6" style="margin-top: 20px;">
        <label class="form-label" for="app_key">App Key <span class="text-danger">*</span></label>
        <input type="text" id="update_app_key" name="app_key" class="form-control" placeholder="-------" value="{{$app->app_key ?? ''}}" data-msg="Please enter store app_key" />
        <div id="update_app_key_error" class="text-danger"></div>
    </div>



    <div class="col-md-6" style="margin-top: 20px;">
        <label class="form-label" for="app_secret">App Secret <span class="text-danger">*</span></label>
        <input type="text" id="update_app_secret" name="app_secret" class="form-control" placeholder="-------" value="{{$app->app_secret ?? ''}}" data-msg="Please enter store app_secret" />
        <div id="update_app_secret_error" class="text-danger"></div>
    </div>

    <div class="col-md-6" style="margin-top: 20px;">
        <label class="form-label" for="access_token">Access Token <span class="text-danger">*</span></label>
        <input type="text" id="update_access_token" name="access_token" class="form-control" placeholder="-------" value="{{$app->access_token ?? ''}}" data-msg="Please enter store access_token" />
        <div id="update_access_token_error" class="text-danger"></div>
    </div>
    <div class="col-md-6" style="margin-top: 20px;">
        <label class="form-label" for="api_version">Api Version<span class="text-danger">*</span></label>
        <input type="text" id="update_api_version" name="api_version" class="form-control" placeholder="-------" value="{{$app->api_version ?? ''}}" data-msg="Please enter store api_version" />
        <div id="update_api_version_error" class="text-danger"></div>
    </div>
    <div class="col-md-6" style="margin-top: 20px">
        <label class="form-label" for="status">Status </label>
        <select class="form-select" name="status" id="update_status">
            <option value="1" @if(isset($app->status) && !empty($app->status) && $app->status == 1) selected @endif>Active</option>
            <option value="2" @if(isset($app->status) && !empty($app->status) && $app->status == 2) selected @endif>Disable</option>
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