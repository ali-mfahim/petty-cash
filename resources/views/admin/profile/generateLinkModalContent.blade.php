<div class="text-center mb-2">
    <h1 class="mb-1"><span id="form-link-modal-heading">{{ $title ?? '' }}</span></h1>
    <p>All the previous form links will be expired after generating new one.</p>
    <div class="text-danger" id="error_message"></div>
    <div class="text-success" style="font-weight: bold;text-transform: uppercase;font-size: 20px;" id="success_message">
    </div>
</div>
<form id="createUserForm" class="row gy-1 pt-75" onsubmit="return false">
    @csrf
    <input type="hidden" id="user-id" value="{{ $user_id ?? '' }}">
    <div class="col-md-10 col-md-6">
        <label class="form-label" for="form_link">Form Link <span class="text-danger">*</span></label>
        <input type="text" id="form_link" name="form_link" class="form-control" placeholder="-------"
            value="{{ $link->link ?? '' }}" data-msg="" readonly />
        <div id="form_link_error" class="text-danger"></div>
    </div>
    <div class="col-md-2  ">
        <button type="button" class="btn btn-primary generate-link-button" data-type="{{ $type ?? '' }}"
            style="margin-top:20px">Generate</button>
    </div>
    <div class="col-12 text-center mt-2 pt-50">
        <button type="button" class="btn btn-primary me-1 copy_button">Copy</button>
        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
            Cancel
        </button>
    </div>
</form>
