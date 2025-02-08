@extends('admin.layouts.master')
@push('title', $title ?? '')
@section('content')
    <!-- Timeline Starts -->
    <section class="basic-timeline">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{$title ?? '-'}}</h4>
                    </div>
                    <div class="card-body">
                        <ul class="timeline">
                            @if (isset($monthlyData) && !empty($monthlyData))
                                @foreach ($monthlyData as $index => $value)
                                    <li class="timeline-item">
                                        <span class="timeline-point timeline-point-danger timeline-point-indicator"></span>
                                        <div class="timeline-event">
                                            <div
                                                class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                                <h6>{{ isset($value['month_year']) && !empty($value['month_year']) ? formatMonthYear($value['month_year']) : '-' }}
                                                </h6>
                                                <span class="timeline-event-time">Last Updated
                                                </span>
                                            </div>
                                            <p class="mb-50">There are total {{ $value['total_records'] }}
                                                @if ($value['total_records'] > 1)
                                                    entries
                                                @else
                                                    entry
                                                @endif
                                            </p>
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ route('payment-forms.show', $value['month_year']) }}"> View Details
                                            </a>
                                            <button class="btn btn-outline-danger btn-sm" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#my-stats"
                                                aria-expanded="true" aria-controls="my-stats">
                                                Stats
                                            </button>
                                            <div class="collapse" id="my-stats">
                                                <ul class="list-group list-group-flush mt-1">
                                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                                        <span>Contribute : <span class="fw-bold text-success">Rs.
                                                                {{ isset($value['month_year']) && !empty($value['month_year']) ? myCalculation($value['month_year'])->myTotalPaid : '-' }}
                                                            </span></span>
                                                        {{-- <i data-feather="trending-down"
                                                            class="cursor-pointer font-medium-2"></i> --}}
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                                        <span> Credit : <span class="fw-bold text-danger">Rs.
                                                                {{ isset($value['month_year']) && !empty($value['month_year']) ? myCalculation($value['month_year'])->myTotalUnPaid : '-' }}
                                                            </span></span>
                                                        {{-- <i data-feather="trending-up"
                                                            class="cursor-pointer font-medium-2"></i> --}}
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                                        <span> Total : <span
                                                                class="fw-bold  @if (isset($value['month_year']) && !empty($value['month_year'])) text-{{ myCalculation($value['month_year'])->totalClass }} @endif">Rs.
                                                                {{ isset($value['month_year']) && !empty($value['month_year']) ? myCalculation($value['month_year'])->total : '-' }}
                                                            </span></span>
                                                        <strong
                                                            class="  text-@if (isset($value['month_year']) && !empty($value['month_year'])) text-{{ myCalculation($value['month_year'])->totalClass }} @endif">
                                                            {!! isset($value['month_year']) && !empty($value['month_year'])
                                                                ? myCalculation($value['month_year'])->message
                                                                : '-' !!}

                                                        </strong>
                                                        {{-- <i data-feather="trending-up"
                                                            class="cursor-pointer font-medium-2"></i> --}}
                                                    </li>
                                                </ul>
                                            </div>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- Timeline Ends -->
    @include('admin.pages.payment-forms.components.createUserModal')
    @include('admin.pages.payment-forms.components.editUserModal')
@endsection
@push('scripts')
    @include('admin.pages.payment-forms.components.scripts')
@endpush
