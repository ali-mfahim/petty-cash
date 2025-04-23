<form id="updateUserReportStatusForm" class="row gy-1 pt-75" onsubmit="return false" method="PUT">
    @csrf
    <input type="hidden" name="user_id" value="{{ $user->id ?? '' }}" id="update-user-id">
    <input type="hidden" name="month" value="{{ $month ?? '' }}" id="update-month">
    <input type="hidden" name="year" value="{{ $year ?? '' }}" id="update-year">
    <div class="col-md-12">
        <label for="">Status</label>
        <select name="status" class="form-select" id="status">
            <option value="1">Settled</option>
            <option value="2">Not Settled</option>
        </select>
        <div id="status_error" class="text-danger"></div>
    </div>
    <div class="col-md-12">
        <label for="">Transaction Type</label>
        <select name="transaction_status" class="form-select" id="transaction_status">
            <option value="1">Paid</option>
            <option value="2">Received</option>
        </select>
        <div id="transaction_status_error" class="text-danger"></div>
    </div>
    <div class="col-md-12">
        <label for="">Transaction Person</label>
        <select name="transaction_user_id" class="form-select" id="transaction_user_id">
            @if (isset($users) && !empty($users))
                @foreach ($users as $index => $value)
                    <option value="{{ $value->id }}">{{ getUserName($value) }}</option>
                @endforeach
            @endif
        </select>
        <div id="transaction_user_id_error" class="text-danger"></div>

    </div>
    <div class="col-md-12">
        <label for="">Amount</label>
        <input type="text" name="amount" class="form-control" value="0" id="amount">
        <div id="amount_error" class="text-danger"></div>
    </div>
    <div class="col-12 text-center mt-2 pt-50">
        <button type="submit" class="btn btn-primary me-1" id="updateUserBtn">Update</button>
        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
            Cancel
        </button>
    </div>
</form>
