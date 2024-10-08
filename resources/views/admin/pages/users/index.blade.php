@extends("admin.layouts.master")
@push("title" , $title ?? '')
@section("content")
<section id="dashboard-ecommerce">
    <div class="content-wrapper">
        <!-- Breadcrumbs -->
        <div class="row">
            <div class="col-md-6">
                <h3>{{$title ?? ''}}</h3>
                <p>Manage user roles across Basic, Professional, and Business categories with predefined access levels.</p>
            </div>
            <div class="col-md-6" style="text-align: right;margin-bottom:20px">
                <button type="button" id="add-new-user-btn" class="btn btn-primary disabled "><i data-feather="plus"></i> Add New User</button>
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
                                    <!-- <th scope="col">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="selectAll" value="1">
                                        </div>
                                    </th> -->
                                    <th scope="col">User</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Role</th>
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
@include("admin.pages.users.components.createUserModal")
@include("admin.pages.users.components.editUserModal")


@endsection
@push("scripts")
@include("admin.pages.users.components.scripts")
@endpush