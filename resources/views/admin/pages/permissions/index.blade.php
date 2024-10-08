@extends("admin.layouts.master")
@push("title" , $title ?? '')
@section("content")
<div class="content-body">


    <div class="row">
        <div class="col-md-6">
            <h3>Permissions List</h3>
            <p>View and manage user roles and permissions for Basic, Professional, and Business categories.</p>

        </div>
        <div class="col-md-6" style="text-align: right;">
            <button type="button" id="add-new-permission-btn" class="btn btn-primary disabled "><i data-feather="plus"></i> Add New Permission</button>
        </div>
    </div>
    <!-- Permission Table -->
    <div class="card">
        <div class="card-body">
            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                <table class="datatables-users  table-hover table border-top dataTable no-footer dtr-column data_table" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Permissions</th>
                            <th>Created At</th>
                            <th> </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!--/ Permission Table -->



    <!-- Add Permission Modal -->
    @include("admin.pages.permissions.components.addNewPermissionModal")
    <!--/ Add Permission Modal -->


    <!-- Add Permission TO Group Modal -->
    @include("admin.pages.permissions.components.addPermissionToGroupModal")
    <!--/ Add Permission TO Group Modal -->


</div>
@endsection
@push("scripts")
@include("admin.pages.permissions.components.scripts")
@endpush