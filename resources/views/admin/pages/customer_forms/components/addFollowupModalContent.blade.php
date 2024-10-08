<form method="POST" id="addFollowupModalForm">
    <input type="hidden" name="route" id="form_route" value="{{route('coorporate-forms.addFollowup')}}">
    <input type="hidden" name="form_id" id="form_id" value="{{$form->id ?? ''}}">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <label for="remarks">Remarks</label>
            <textarea name="remarks" id="remarks" class="form-control" cols="30" rows="10" placeholder="Enter your remarks"></textarea>
            <div id="remarks_error" class="text-danger" style="font-weight: bold;font-size: 17px;"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <label for="add_followup_status">Status</label>
            <select name="add_followup_status" id="add_followup_status" class="form-select">
                @if(isset($statuses) && !empty($statuses))
                @foreach($statuses as $index => $value)
                <option value="{{$value->id}}" @if(isset($form->status) && !empty($form->status) && $form->status == $value->id) selected @endif>{{$value->name}}</option>
                @endforeach
                @endif
            </select>
            <span class="text-warning">Leave this field if you don't want to change</span>
            <div id="add_followup_status_error" class="text-danger" style="font-weight: bold;font-size: 17px;"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <button type="submit" class="btn btn-success w-100 mt-2">
                Save
            </button>
        </div>
        <div class="col-md-6">
            <button type="reset" class="btn btn-danger w-100 mt-2" data-bs-dismiss="modal" aria-label="Close">
                Cancel
            </button>
        </div>
    </div>
</form>