@extends('admin.layouts.master')
@push('title', $title ?? '')
@section('content')
    <!-- Timeline Starts -->
    <section class="basic-timeline">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $title ?? '-' }}</h4>
                    </div>
                    <div class="card-body">
                        <ul class="timeline">
                            @if (isset($monthlyData) && !empty($monthlyData))
                                @foreach ($monthlyData as $index => $value)
                                    @php
                                        $myIndividual = myCalculation($value['month_year']);
                                        if (isset($value['month_year']) && !empty($value['month_year'])) {
                                            $seperator = monthYearSeperator($value['month_year']);
                                        } else {
                                            $seperator = [];
                                        }
                                    @endphp
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
                                            @if (isset($seperator[0]) && !empty($seperator[0]) && (isset($seperator[1]) && !empty($seperator[1])))
                                                <a class="btn btn-primary btn-sm"
                                                    href="{{ route('entries.details', ['month' => $seperator[0], 'year' => $seperator[1], getUser()->id]) }}"
                                                    target="_blank">
                                                    View Details </a>
                                            @endif
                                            <button class="btn btn-outline-danger btn-sm" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#my-stats" aria-expanded="true"
                                                aria-controls="my-stats">
                                                Stats
                                            </button>
                                            @if (getMyRole(getUser()->id) == 'Super Admin')
                                                <button class="btn btn-outline-danger btn-sm" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#all-stats"
                                                    aria-expanded="true" aria-controls="all-stats">
                                                    All Stats
                                                </button>
                                            @endif
                                            <div class="collapse" id="my-stats">
                                                <ul class="list-group list-group-flush mt-1">
                                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                                        <span>Credit : <span class="fw-bold text-success">Rs.
                                                                {{ isset($value['month_year']) && !empty($value['month_year']) ? $myIndividual->myTotalPaid : '-' }}
                                                            </span></span>
                                                        {{-- <i data-feather="trending-down"
                                                            class="cursor-pointer font-medium-2"></i> --}}
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                                        <span> Debt : <span class="fw-bold text-danger">Rs.
                                                                {{ isset($value['month_year']) && !empty($value['month_year']) ? $myIndividual->myTotalUnPaid : '-' }}
                                                            </span></span>
                                                        {{-- <i data-feather="trending-up"
                                                            class="cursor-pointer font-medium-2"></i> --}}
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                                        <span> Total : <span
                                                                class="fw-bold  @if (isset($value['month_year']) && !empty($value['month_year'])) text-{{ $myIndividual->totalClass }} @endif">Rs.
                                                                {{ isset($value['month_year']) && !empty($value['month_year']) ? $myIndividual->total : '-' }}
                                                            </span></span>
                                                        <strong
                                                            class="  text-@if (isset($value['month_year']) && !empty($value['month_year'])) text-{{ $myIndividual->totalClass }} @endif">
                                                            {!! isset($value['month_year']) && !empty($value['month_year']) ? $myIndividual->message : '-' !!}

                                                        </strong>
                                                        {{-- <i data-feather="trending-up"
                                                            class="cursor-pointer font-medium-2"></i> --}}
                                                    </li>
                                                </ul>
                                            </div>


                                            @if (getMyRole(getUser()->id) == 'Super Admin')
                                                @php
                                                    $users = getUsersOfThisMonth($value['month_year']);

                                                @endphp
                                                <div class="collapse" id="all-stats">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h4>All Statistics of users this month</h4>
                                                        </div>
                                                        <div class="card-body">
                                                            <table class="table table-hover  ">
                                                                <thead>
                                                                    <th>#</th>
                                                                    <th>Name</th>
                                                                    <th>Credit</th>
                                                                    <th>Debt</th>
                                                                    <th>Balance</th>
                                                                    <th>Actions</th>
                                                                </thead>
                                                                <tbody>
                                                                    @if (isset($users) && !empty($users) && count($users) > 0)
                                                                        @foreach ($users as $i => $v)
                                                                            @php
                                                                                $userCalculation = myCalculation(
                                                                                    $value['month_year'],
                                                                                    $v->id,
                                                                                );
                                                                            @endphp

                                                                            <tr>
                                                                                <th>{{ ++$index }}</th>
                                                                                <th>
                                                                                    {{ getUserName($v) }}
                                                                                </th>
                                                                                <th><span class="text-success">Rs.
                                                                                        {{ $userCalculation->myTotalPaid }}</span>
                                                                                </th>
                                                                                <th> <span class="text-danger">Rs.
                                                                                        {{ $userCalculation->myTotalUnPaid ?? 0 }}</span>
                                                                                </th>
                                                                                <th>
                                                                                    <strong
                                                                                        class="  text-@if (isset($userCalculation) && !empty($userCalculation)) text-{{ $userCalculation->totalClass }} @endif">
                                                                                        Rs.
                                                                                        {{ $userCalculation->total ?? 0 }}
                                                                                    </strong>
                                                                                </th>
                                                                                <th>
                                                                                    @if (isset($seperator[0]) && !empty($seperator[0]) && (isset($seperator[1]) && !empty($seperator[1])))
                                                                                        <a class="btn btn-primary btn-sm"
                                                                                            href="{{ route('entries.details', ['month' => $seperator[0], 'year' => $seperator[1], $v->id]) }}"
                                                                                            target="_blank">
                                                                                            <i data-feather="search"></i>
                                                                                        </a>
                                                                                    @endif
                                                                                </th>
                                                                            </tr>
                                                                        @endforeach
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endif

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
    @include('admin.pages.entries.components.createUserModal')
    @include('admin.pages.entries.components.editUserModal')
@endsection
@push('scripts')
    @include('admin.pages.entries.components.scripts')
@endpush
