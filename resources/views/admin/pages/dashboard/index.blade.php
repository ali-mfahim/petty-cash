@extends('admin.layouts.master')
@push('title', $title ?? '')
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/app-assets/css/pages/dashboard-ecommerce.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/app-assets/vendors/css/charts/apexcharts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/app-assets/css/plugins/charts/chart-apex.css') }}">
@endpush
@section('content')
    <section id="dashboard-ecommerce">
        <div class="row match-height">
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header flex-column align-items-start pb-0">
                        <div class="avatar bg-light-primary p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="users" class="font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="fw-bolder mt-1">92.6k</h2>
                        <p class="card-text">Credit</p>
                    </div>
                    <div id="credit-chart"></div>
                </div>
            </div>
            <!--/ Statistics Card -->
        </div>



    </section>
@endsection
@push('scripts')
    {{-- <script src="{{ asset('admin/assets/app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script> --}}
    <script src="{{ asset('admin/assets/app-assets/js/scripts/pages/dashboard-analytics.js') }}"></script>
    {{-- <script src="{{ asset('admin/assets/app-assets/vendors/js/charts/apexcharts.min.js') }}"></script> --}}
    <script>
        $(document).ready(function() {
            $.ajax({
                url: "{{ route('entries.dashboardData') }}",
                data: {
                    "type": "credit",
                    "user_id": "{{ getUser()->id }}",
                },
                beforeSend: function() {
                    console.log("WORKING");
                },
                success: function(res) {
                    console.log(res);
                },
                error: function(xhr, status, error) {
                    console.log(xhr, status, error)
                }
            });
        });
        // gainedChartOptions = {
        //     chart: {
        //         height: 100,
        //         type: 'area',
        //         toolbar: {
        //             show: false
        //         },
        //         sparkline: {
        //             enabled: true
        //         },
        //         grid: {
        //             show: false,
        //             padding: {
        //                 left: 0,
        //                 right: 0
        //             }
        //         }
        //     },
        //     colors: [window.colors.solid.primary],
        //     dataLabels: {
        //         enabled: false
        //     },
        //     stroke: {
        //         curve: 'smooth',
        //         width: 2.5
        //     },
        //     fill: {
        //         type: 'gradient',
        //         gradient: {
        //             shadeIntensity: 0.9,
        //             opacityFrom: 0.7,
        //             opacityTo: 0.5,
        //             stops: [0, 80, 100]
        //         }
        //     },
        //     series: [{
        //         name: 'Subscribers',
        //         data: [28, 40, 36, 52, 38, 60, 55]
        //     }],
        //     xaxis: {
        //         labels: {
        //             show: false
        //         },
        //         axisBorder: {
        //             show: false
        //         }
        //     },
        //     yaxis: [{
        //         y: 0,
        //         offsetX: 0,
        //         offsetY: 0,
        //         padding: {
        //             left: 0,
        //             right: 0
        //         }
        //     }],
        //     tooltip: {
        //         x: {
        //             show: false
        //         }
        //     }
        // };
        // gainedChart = new ApexCharts($gainedChart, gainedChartOptions);
        // gainedChart.render();
    </script>
@endpush
