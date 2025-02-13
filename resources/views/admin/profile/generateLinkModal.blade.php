<div class="modal fade" id="generateFormLinkModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered  ">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Form Link</h1>
                    <p>All the previous form links will be expired after generating new one.</p>
                    <div class="text-danger" id="error_message"></div>
                    <div class="text-success" style="font-weight: bold;text-transform: uppercase;font-size: 20px;"
                        id="success_message"></div>
                </div>
                <form id="createUserForm" class="row gy-1 pt-75" onsubmit="return false">
                    @csrf
                    <input type="hidden" id="user-id" value="{{ getUser()->id }}">
                    <div class="col-md-10 col-md-6">
                        <label class="form-label" for="form_link">Form Link <span class="text-danger">*</span></label>
                        <input type="text" id="form_link" name="form_link" class="form-control"
                            value="{{ $paymentLink->link ?? '' }}" placeholder="-------" value="" data-msg=""
                            readonly />
                        <div id="form_link_error" class="text-danger"></div>
                    </div>
                    <div class="col-md-2  ">
                        <button type="button" class="btn btn-primary generate-link-button"
                            style="margin-top:20px">Generate</button>
                    </div>
                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="button" class="btn btn-primary me-1 copy_button" >Copy</button>
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            aria-label="Close">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
