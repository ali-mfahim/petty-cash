@extends('admin.layouts.master')
@push('title', $title ?? '')
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/app-assets/css/pages/dashboard-ecommerce.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/app-assets/vendors/css/charts/apexcharts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/app-assets/css/plugins/charts/chart-apex.css') }}">
@endpush
@section('content')
    <section id="dashboard-ecommerce">
        <div class="row mb-2">
            <div class="col-md-12">
                <h3>Current Month Data</h3>
            </div>

        </div>
        <div class="row match-height">
            {{-- Paid --}}
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="javascript:;" target="_blank" class="view-paid-details" data-user-id="{{ getUser()->id }}">
                    <div class="card" style="min-height:200px">
                        <div class="card-header flex-column align-items-start pb-0" style="margin-bottom:30px">
                            <div class="avatar bg-light-danger p-50 m-0">
                                <div class="avatar-content">
                                    <i data-feather="users" class="font-medium-5"></i>
                                </div>
                            </div>
                            <h2 class="fw-bolder mt-1">Rs.<span id="paid_sum">0</span></h2>
                            <p class="card-text text-white  ">Paid</p>
                        </div>
                        <div id="paid-chart"></div>
                    </div>
                </a>
            </div>
            {{-- Paid --}}


            {{-- unpaid --}}
            <div class="col-lg-3 col-sm-6 col-12">
                <a href="javascript:;" target="_blank"  class="view-unpaid-details" data-user-id="{{ getUser()->id }}">
                    <div class="card" style="min-height:200px">
                        <div class="card-header flex-column align-items-start pb-0" style="margin-bottom:30px">
                            <div class="avatar bg-light-danger p-50 m-0">
                                <div class="avatar-content">
                                    <i data-feather="users" class="font-medium-5"></i>
                                </div>
                            </div>
                            <h2 class="fw-bolder mt-1">Rs.<span id="unpaid_sum">0</span></h2>
                            <p class="card-text text-white  ">Un Paid</p>
                        </div>
                        <div id="unPaid-chart"></div>
                    </div>
                </a>
            </div>
            {{-- unpaid --}}
            {{-- my personal Expense --}}
            <div class="col-md-6">
                <a href="javascript:;" target="_blank"  class="view-expense-details" data-user-id="{{ getUser()->id }}">
                    <div class="card" style="min-height:200px">
                        <div class="card-header flex-column align-items-start pb-0" style="margin-bottom:30px">
                            <div class="avatar bg-light-danger p-50 m-0">
                                <div class="avatar-content">
                                    <i data-feather="users" class="font-medium-5"></i>
                                </div>
                            </div>
                            <h2 class="fw-bolder mt-1">Rs.<span id="pe_sum">0</span></h2>
                            <p class="card-text text-white  ">Personal Expense</p>
                        </div>
                        <div id="pe-chart"></div>
                    </div>
                </a>
            </div>
            {{-- my personal Expense --}}





        </div>
    </section>
@endsection
@push('scripts')
    {{-- <script src="{{ asset('admin/assets/app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script> --}}
    <script src="{{ asset('admin/assets/app-assets/js/scripts/pages/dashboard-analytics.js') }}"></script>
    <script src="{{ asset('admin/assets/app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: "{{ route('dashboard.graphData') }}",
                data: {
                    "user_id": "{{ getUser()->id }}",
                },
                beforeSend: function() {},
                success: function(res) {
                    if (res.data.unPaid) {
                        var unPaidData = Object.values(res.data.unPaid);
                        renderLineGraph(unPaidData, "#ff0000", "unPaid-chart"); // red for un paid
                        $("#unpaid_sum").html(res.data.unPaidSum);
                    }

                    if (res.data.paid) {
                        var paidData = Object.values(res.data.paid);
                        renderLineGraph(paidData, "#0bdf36", "paid-chart"); // green for paid
                        $("#paid_sum").html(res.data.paidSum);
                    }
                    if (res.data.expense) {
                        var expenseData = Object.values(res.data.expense);
                        renderLineGraph(expenseData, "#0bdf36",
                            "pe-chart"); // purple for personal expense
                        $("#pe_sum").html(res.data.expenseSum);
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr, status, error)
                }
            });
        });


        function renderLineGraph(data, color, selector) {

            var options = {
                chart: {
                    height: 100,
                    type: 'area',
                    toolbar: {
                        show: false
                    },
                    sparkline: {
                        enabled: true
                    },
                    grid: {
                        show: false,
                        padding: {
                            left: 0,
                            right: 0
                        }
                    }
                },
                colors: [color], // Dynamic color
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2.5
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 0.9,
                        opacityFrom: 0.7,
                        opacityTo: 0.5,
                        stops: [0, 80, 100]
                    }
                },
                series: [{
                    name: 'Data',
                    data: data // Dynamic data
                }],
                xaxis: {
                    labels: {
                        show: true
                    },
                    axisBorder: {
                        show: false
                    }
                },
                yaxis: [{
                    y: 0,
                    offsetX: 0,
                    offsetY: 0,
                    padding: {
                        left: 0,
                        right: 0
                    }
                }],
                tooltip: {
                    x: {
                        show: false
                    }
                }
            };

            // Render chart
            var chart = new ApexCharts(document.querySelector("#" + selector), options);
            chart.render();
        }
    </script>
@endpush
