<form method="POST" id="changeStatusModalForm">
    <input type="hidden" name="customer_form_id" id="customer_form_id" value="{{$form->id  ?? ''}}">
    <input type="hidden" name="new_status_id" id="new_status_id" value="{{$status_id ?? ''}}">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <label for="status_remarks">Remarks</label>
            <textarea name="remarks" id="status_remarks" class="form-control" cols="30" rows="10" placeholder="Enter your remarks"></textarea>
            <div id="status_remarks_error" class="text-danger" style="font-weight: bold;font-size: 17px;"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <button type="submit" class="btn btn-success w-100 mt-2">Save</button>
        </div>
        <div class="col-md-6">
            <button type="reset" class="btn btn-danger w-100 mt-2" data-bs-dismiss="modal" aria-label="Close">
                Cancel
            </button>
        </div>
    </div>
</form>