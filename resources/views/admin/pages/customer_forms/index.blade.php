@extends("admin.layouts.master")
@push("title" , $title ?? '')
@section("content")
<section id="dashboard-ecommerce">
    <div class="content-wrapper">
        <!-- Breadcrumbs -->
        <div class="row">
            <div class="col-md-6">
                <h3>{{$title ?? ''}}</h3>
                <p>Review and address all customer queries in this comprehensive list to streamline your support process.</p>
            </div>
            <div class="col-md-6" style="text-align: right;">
            </div>
        </div>
        <!-- Breadcrumbs -->
        <div class="card input-checkbox">
            <div class="card-body">
                <div class="card-datatable">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <table class="datatables-users  table-hover table border-top dataTable no-footer dtr-column data_table" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Customer</th>
                                    <th scope="col">Color</th>
                                    <th scope="col">Country</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Details</th>
                                    <th scope="col">Status</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody id="body"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- view form detail -->
<div class="modal fade" id="viewCustomerFormModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewCustomerFormModalBody">
            </div>
        </div>
    </div>
</div>
<!-- view form detail -->


<!-- add followup modal -->
<div class="modal fade" id="addFollowupModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                Add Followup
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="addFollowupModalBody">

            </div>
        </div>
    </div>
</div>
<!-- add followup modal -->


<!-- view followup modal -->
<div class="modal fade" id="viewFollowups" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewFollowupsBody">
            </div>
        </div>
    </div>
</div>
<!-- view followup modal -->


<!-- change status modal -->
<div class="modal fade" id="changeStatusModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                Change Status
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="changeStatusModalBody">

            </div>
        </div>
    </div>
</div>
<!-- change status modal -->


<!-- send email  -->
<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-send-email">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="sendEmailModalBody">
                <form method="POST" id="sendEmailModalForm">
                    @csrf
                    <input type="hidden" name="email_form_id" id="email_form_id" value="">
                    <input type="hidden" name="email_form_route" id="email_form_route" value="">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="to_email">To <span class="text-danger">*</span></label>
                            <input type="text" name="to_email" id="to_email" class="form-control">
                            <div class="text-danger" id="to_email_error"></div>
                            <!-- <small class="text-warning">This field can not be changed</small> -->
                        </div>
                        <div class="col-md-12 mb-2 ">
                            <label for="subject">Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" id="subject" class="form-control" placeholder="Enter subject of email">
                            <div class="text-danger" id="subject_error"></div>
                        </div>
                        <div class="col-md-12 mb-2 ">
                            <label for="message">Message <span class="text-danger">*</span></label>
                            <textarea name="message" id="message" cols="30" rows="10" class="form-control" placeholder="Enter message of email"></textarea>
                            <div class="text-danger" id="message_error"></div>
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
            </div>
        </div>
    </div>
</div>
<!-- send email  -->




@endsection
@push("scripts")
@include("admin.pages.customer_forms.components.scripts")
@endpush