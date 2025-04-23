<form id="updateUserReportStatusForm" class="row gy-1 pt-75" onsubmit="return false" method="PUT">
    @csrf
    <input type="hidden" value="{{ $user->id ?? '' }}" id="update-user-id">
    <input type="hidden" value="{{ $month ?? '' }}" id="update-month">
    <input type="hidden" value="{{ $year ?? '' }}" id="update-year">
    <div class="col-md-12">
        <label for="">Status</label>
        <select name="" class="form-select" id="">
            <option value="1">Settled</option>
            <option value="2">Not Settled</option>
        </select>
    </div>
    <div class="col-md-12">
        <label for="">Transaction Type</label>
        <select name="" class="form-select" id="">
            <option value="1">Paid</option>
            <option value="2">Received</option>
        </select>
    </div>
    <div class="col-md-12">
        <label for="">Transaction Person</label>
        <select name="" class="form-select" id="">
            @if (isset($users) && !empty($users))
                @foreach ($users as $index => $value)
                    <option value="{{ $value->id }}">{{ getUserName($value) }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-12 text-center mt-2 pt-50">
        <button type="submit" class="btn btn-primary me-1" id="updateUserBtn">Update</button>
        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
            Cancel
        </button>
    </div>
</form>
