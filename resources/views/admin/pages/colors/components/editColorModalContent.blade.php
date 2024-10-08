<form id="editColorForm" class="row gy-1 pt-75" onsubmit="return false" method="PUT">

    @csrf
    <input type="hidden" value="{{ route('colors.update', $color->id) }}" id="update-color-route">
    <div class="col-12 col-md-6">
        <label class="form-label" for="update_name">Color Name <span class="text-danger">*</span></label>
        <input type="text" id="update_name" name="name" class="form-control" placeholder="-------" value="{{$color->name ?? ''}}" data-msg="Please enter color name" />
        <div id="update_name_error" class="text-danger"></div>
    </div>
    <div class="col-12 col-md-6">
        <label class="form-label" for="code">Color Picker</label>
        <input type="color" id="code" name="code" class="form-control" placeholder="-------" value="{{$color->code ?? '' }}" data-msg="Please pickup a color " style="height: 38px;" />
        <div id="code_error" class="text-danger"></div>
    </div>
    <div class="col-md-12" style="margin-top: 20px;">
        <label class="form-label" for="status">Status </label>
        <select class="form-select" name="status" id="status">
            <option value="1" @if(isset($color->status) && !empty($color->status) && $color->status == 1) selected @endif>Active</option>
            <option value="2" @if(isset($color->status) && !empty($color->status) && $color->status == 2) selected @endif>Disable</option>
        </select>
        <div id="status_error" class="text-danger"></div>
    </div>
    <div class="col-12 text-center mt-2 pt-50">
        <button type="submit" class="btn btn-primary me-1" id="updateUserBtn">Update</button>
        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
            Cancel
        </button>
    </div>
</form>