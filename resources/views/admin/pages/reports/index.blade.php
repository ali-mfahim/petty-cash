@extends("admin.layouts.master")
@push("title" , $title ?? '')
@section("content")
<section id="dashboard-ecommerce">
    <div class="content-wrapper">
        <!-- Breadcrumbs -->
        <div class="row">
            <div class="col-md-6">
                <h3>{{$title ?? ''}}</h3>
                <p>This report is based on orders fetched from shopify store.</p>
            </div>
        </div>
        <!-- Breadcrumbs -->
        <div class="card input-checkbox">
            <div class="card-body">
                <div class="card-datatable">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer ">
                        <table class="table-hover table border-top   no-footer dtr-column  ">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Store</th>
                                    <th scope="col">App</th>
                                    <th scope="col">Total Orders</th>
                                    <th scope="col">Orders Pending</th>
                                    <th scope="col">Orders Completed</th>
                                    <th scope="col">Orders Skipped</th>
                                    <th scope="col">Orders In Process</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($report) && !empty($report) && count($report))
                                @foreach($report as $index => $value)
                                <tr>
                                    <td>{{++$index}}</td>
                                    <td>{{$value->store->name ?? '-'}}</td>
                                    <td>{{$value->app->app_name ?? '-'}}</td>
                                    <td>{{$value->total ?? '0'}}</td>
                                    <td>{{$value->pending ?? '0'}}</td>
                                    <td>{{$value->in_process ?? '0'}}</td>
                                    <td>{{$value->completed ?? '0'}}</td>
                                    <td>{{$value->skipped ?? '0'}}</td>
                                    <td>
                                        @if(isset($value->store->slug) && !empty($value->store->slug))
                                        <div class="btn-group dropstart">
                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item " href="{{route('reports.details' , $value->store->slug )}}" target="_blank"> Details </a>
                                            </div>
                                        </div>
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection