@extends("admin.layouts.master")
@push("title" , $title ?? '')
@section("content")
<section id="dashboard-ecommerce">
    <div class="content-wrapper">
        <!-- Breadcrumbs -->
        <div class="row">
            <div class="col-md-6">
                <h3>{{$title ?? ''}}</h3>
                <p>Explore the list below to manage your customer profiles and interactions seamlessly</p>
            </div>
            <div class="col-md-6" style="text-align: right;margin-bottom:20px">
                <!-- <button type="button" id="add-new-user-btn" class="btn btn-primary disabled "><i data-feather="plus"></i> Add New User</button> -->
            </div>
        </div>
        <!-- Breadcrumbs -->
        <div class="card input-checkbox">
            <div class="card-body">
                <div class="card-datatable">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer ">
                        <table class="datatables-users   table-hover table border-top dataTable no-footer dtr-column data_table" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Customer</th>
                                    <th scope="col">No. Of Queries</th>
                                    <th scope="col">Phone</th>
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



<!-- view customer detail -->
<div class="modal fade" id="viewCustomerModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewCustomerModalBody">
            </div>
        </div>
    </div>
</div>
<!-- view customer detail -->


@endsection
@push("scripts")
@include("admin.pages.customers.components.scripts")
@endpush