@extends("admin.layouts.master")
@push("title" , $title ?? '')
@section("content")
<section id="dashboard-ecommerce">
    <div class="content-wrapper">
        <!-- Breadcrumbs -->
        <div class="row">
            <div class="col-md-6">
                <h3>{{$title ?? ''}}</h3>
                <p>Manage Your Stores. Only 1 store can be activated at a time.</p>
            </div>
            <div class="col-md-6" style="text-align: right;margin-bottom:20px">
                @can("store-create")
                <button type="button" id="add-new-store-btn" class="btn btn-primary disabled "><i data-feather="plus"></i> Add New Store</button>
                @endcan
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
                                    <th scope="col">Created By</th>
                                    <th scope="col">Store</th>
                                    <th scope="col">Domain</th>
                                    <th scope="col">Base Url</th>
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
@include("admin.pages.stores.components.createStoreModal")
@include("admin.pages.stores.components.editStoreModal")
@include("admin.pages.stores.components.viewStoreModal")


@endsection
@push("scripts")
@include("admin.pages.stores.components.scripts")
@endpush