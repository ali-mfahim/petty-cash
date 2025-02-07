@extends('admin.layouts.master')
@push('title', $title ?? '')
@section('content')
    <!-- Timeline Starts -->
    <section class="basic-timeline">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Monthly Reports</h4>
                    </div>
                    <div class="card-body">
                        <ul class="timeline">
                            @if (isset($monthlyData) && !empty($monthlyData))
                                @foreach ($monthlyData as $index => $value)
                                    @php
                                        if (isset($value->month_year) && !empty($value->month_year)) {
                                            $seperator = monthYearSeperator($value->month_year);
                                        } else {
                                            $seperator = [];
                                        }

                                    @endphp
                                    <li class="timeline-item">
                                        <span class="timeline-point timeline-point-danger timeline-point-indicator"></span>
                                        <div class="timeline-event">
                                            <div
                                                class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                                <h6>{{ isset($value->month_year) && !empty($value->month_year) ? formatMonthYear($value->month_year) : '-' }}
                                                </h6>
                                                <span class="timeline-event-time">Last Updated
                                                    {{ $value->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="mb-50">There are total {{ $value->total_entries }}
                                                @if ($value->total_entries > 1)
                                                    entries
                                                @else
                                                    entry
                                                @endif
                                            </p>
                                            <a class="btn btn-primary btn-sm"
                                                @if (isset($seperator[0]) && !empty($seperator[0]) && (isset($seperator[1]) && !empty($seperator[1]))) href="{{ route('monthly-reports.detail', ['month' => $seperator[0], 'year' => $seperator[1]]) }}">
                                                View Details </a> @endif
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
    @include('admin.pages.monthly-reports.components.createUserModal')
    @include('admin.pages.monthly-reports.components.editUserModal')
@endsection
@push('scripts')
    @include('admin.pages.monthly-reports.components.scripts')
@endpush
