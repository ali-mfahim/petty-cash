@extends("admin.layouts.master")
@push("title" , $title ?? '')
@section("content")
<section id="dashboard-ecommerce">
    <input type="hidden" name="page_url" id="page_url" value="{{route('stores.apps' , $store->slug)}}">
    <div class="content-wrapper">
        <!-- Breadcrumbs -->
        <div class="row">
            <div class="col-md-6">
                <h3>{{$title ?? ''}}</h3>
                <p>Manage Your Apps. Only 1 app can be activated at a time from each store</p>
            </div>
            <div class="col-md-6" style="text-align: right;margin-bottom:20px">
                <button type="button" id="add-new-app-btn" class="btn btn-primary disabled "><i data-feather="plus"></i>Add New App</button>
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
                                    <th scope="col">Name</th>
                                    <th scope="col">APP Key</th>
                                    <th scope="col">App Secret</th>
                                    <th scope="col">Access Token</th>
                                    <th scope="col">Api Version</th>
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
@include("admin.pages.stores.components.apps.createAppModal")
@include("admin.pages.stores.components.apps.editAppModal")
@include("admin.pages.stores.components.apps.viewAppModal")


@endsection
@push("scripts")
@include("admin.pages.stores.components.apps.scripts")
@endpush