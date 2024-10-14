@extends("admin.layouts.master")
@push("title" , $title ?? '')
@section("content")
<section id="dashboard-ecommerce">
    <div class="content-wrapper">
        <!-- Breadcrumbs -->
        <div class="row">
            <div class="col-md-6">
                <h3>{{$title ?? ''}}</h3>
                <!-- <p>Manage Your Stores. Only 1 store can be activated at a time.</p> -->
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mt-2 mb-2">
                    <div class="col-md-4">
                        <label for="keyword" class="mb-2">Search</label>
                        <input type="text" name="keyword" id="keyword" class="form-control keyword" placeholder="Type your keword to search">
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="mb-2">Status</label>
                        <select class="form-select status" name="status">
                            <option value="">All</option>
                            <option value="0">Pending</option>
                            <option value="1">In Process</option>
                            <option value="2">Completed</option>
                            <option value="3">Skipped</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="button" id="search_btn" style="margin-top: 43px;" class="btn btn-danger btn-sm"> <i data-feather="search"></i></button>
                        <button type="button" id="reset_btn" style="margin-top: 43px;" class="btn btn-success btn-sm"> <i data-feather="refresh-ccw"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breadcrumbs -->
        <div class="card input-checkbox">



            <div class="card-body">
                <div class="card-datatable">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer ">
                        <table class="datatables-categories table-hover table border-top dataTable no-footer dtr-column data_table" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Order ID</th>
                                    <th scope="col">Customer ID</th>
                                    <th scope="col">Tags</th>
                                    <th scope="col">Updated At</th>
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
@include("admin.pages.reports.components.viewCustomerModal")
@endsection

@push("scripts")
@include("admin.pages.reports.components.scripts")
@endpush