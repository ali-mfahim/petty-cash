@extends("admin.layouts.master")
@push("title" , $title ?? '')
@push("styles")
<!-- <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/css/pages/dashboard-ecommerce.css')}}"> -->
<!-- <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/vendors/css/charts/apexcharts.css')}}"> -->
<!-- <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/app-assets/css/plugins/charts/chart-apex.css')}}"> -->
@endpush
@section("content")
<section id="dashboard-ecommerce">
    <div class="row match-height">
        <!-- Medal Card -->
        <div class="col-xl-4 col-md-6 col-12">
            <div class="card card-congratulation-medal">
                <div class="card-body">
                    <h5>Welcome 🎉 {{ getUserName(getUser()) }}!</h5>
                    <p class="card-text font-small-3">We're thrilled to have you on board.
                        <br> Explore your dashboard to get started.
                        <br> Feel free to reach out if you need any assistance.

                    </p>
                    <!-- <h3 class="mb-75 mt-2 pt-50">
                        <a href="#">View Profile</a>
                    </h3> -->
                    <a href="{{route('profiles.index')}}" class="btn btn-primary">View Profile</a>
                    <img src="{{asset('medal.svg')}}" class="congratulation-medal" alt="Medal Pic" style="max-width: 100px !important;" />
                </div>
            </div>
        </div>
        <!--/ Medal Card -->

        <!-- Statistics Card -->
        <div class="col-xl-8 col-md-6 col-12  ">
            <div class="card card-statistics">
                <div class="card-header">
                    <h4 class="card-title">Statistics</h4>
                    <div class="d-flex align-items-center">
                        <!-- <p class="card-text font-small-2 me-25 mb-0">Updated 1 month ago</p> -->
                    </div>
                </div>
                <div class="card-body statistics-body">
                    <div class="row">
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-primary me-2">
                                    <div class="avatar-content">
                                        <i data-feather="trending-up" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{$queries ?? 0}}</h4>
                                    <p class="card-text font-small-3 mb-0">Queries</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-info me-2">
                                    <div class="avatar-content">
                                        <i data-feather="users" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{$customers ?? ''}}</h4>
                                    <p class="card-text font-small-3 mb-0">Customers</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-danger me-2">
                                    <div class="avatar-content">
                                        <i data-feather="users" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">{{$users ?? 0}}</h4>
                                    <p class="card-text font-small-3 mb-0">System Users</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-success me-2">
                                    <div class="avatar-content">
                                        <i data-feather="dollar-sign" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <h4 class="fw-bolder mb-0">0</h4>
                                    <p class="card-text font-small-3 mb-0">Orders</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Statistics Card -->
    </div>


</section>
@endsection
@push("scripts")
<!-- <script src="{{asset('admin/assets/app-assets/js/scripts/pages/dashboard-ecommerce.js')}}"></script> -->
<!-- <script src="{{asset('admin/assets/app-assets/vendors/js/charts/apexcharts.min.js')}}"></script> -->
@endpush