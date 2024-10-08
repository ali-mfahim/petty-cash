@extends("admin.layouts.master")
@push("title" , $title ?? '')
@section("content")
<section id="dashboard-ecommerce">
    <div class="content-wrapper">
        <!-- Breadcrumbs -->
        <div class="row">
            <div class="col-md-6">
                <h3>{{$title ?? ''}}</h3>
                <p>Organize products with a detailed categories list to streamline navigation and management.</p>
            </div>
            <div class="col-md-6" style="text-align: right;margin-bottom:20px">
                <button type="button" id="add-new-category-btn" class="btn btn-primary disabled "><i data-feather="plus"></i> Add New Category</button>
            </div>
        </div>
        <!-- Breadcrumbs -->
        <div class="card input-checkbox">
            <div class="card-body">
                <div class="card-datatable">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer ">
                        <table class="datatables-categories   table-hover table border-top dataTable no-footer dtr-column data_table" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">description</th>
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
@include("admin.pages.categories.components.createCategoryModal")
@include("admin.pages.categories.components.editCategoryModal")
@include("admin.pages.categories.components.viewCategoryModal")


@endsection
@push("scripts")
@include("admin.pages.categories.components.scripts")
@endpush