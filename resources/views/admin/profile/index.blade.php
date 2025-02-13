@extends('admin.layouts.master')
@push('title', $title ?? '')
@section('content')
    <div class="content-wrapper">
        <!-- Content -->
        <div class="row">
            <div class="col-md-6">
                <h3>{{ $title ?? '' }}</h3>
                <p>Manage your personal information and settings here.</p>
            </div>
            <div class="col-md-6" style="text-align: right;margin-bottom:20px">
                <!-- <button type="button" id="add-new-user-btn" class="btn btn-primary disabled "><i data-feather="plus"></i> Add New User</button> -->
            </div>
        </div>
        <div class="container-fluid flex-grow-1 container-p-y">
            <div class="row">
                <!-- User Sidebar -->
                <div class="col-md-3">
                    <div class="card mb-4 h-100">
                        <div class="card-body">
                            <div class="user-avatar-section">
                                <div class="d-flex align-items-center flex-column" style="margin-top: 50px;">
                                    @if (isset($model->image) && !empty($model->image))
                                        <img src="{{ asset(config('project.upload_path.users') . $model->image ?? null) }}"
                                            alt="{{ getUserName($record) ?? null }}" class="w-100" />
                                    @else
                                        {!! getWordInitial($model->first_name, '100px', '40px') !!}
                                    @endif
                                    <div class="user-info text-center" style="margin-top: 25px;">
                                        <h4 style="font-size: 20px;">{{ getUserName($model) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-4 small text-uppercase text-muted">Details</p>
                            <div class="info-container">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <span class="fw-semibold me-1" style="font-weight: bold">Phone:</span>
                                        <span>
                                            {{ $model->phone ?? '' }}
                                        </span>
                                    </li>
                                    <li class="mb-2 pt-1">
                                        <span class="fw-semibold me-1" style="font-weight: bold">Email:</span>
                                        <span>{{ $model->email ?? '-' }}</span>
                                    </li>
                                    <li class="mb-2 pt-1">
                                        <span class="fw-semibold me-1" style="font-weight: bold">Role:</span>
                                        <span>
                                            @if (!empty($model->getRoleNames()))
                                                @foreach ($model->getRoleNames() as $roleName)
                                                    {{ $roleName }},
                                                @endforeach
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </li>
                                    <li class="mb-2 pt-1">
                                        <span class="fw-semibold me-1" style="font-weight: bold">Status:</span>
                                        @if ($model->status)
                                            <span class="badge bg-label-success">Active</span>
                                        @else
                                            <span class="badge bg-label-danger">In-Active</span>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-8">
                            <ul class="nav nav-pills flex-column flex-md-row mb-4 profile-tabs">
                                <li class="nav-item"></li>
                                <li class="nav-item">
                                    <button type="button" class="btn btn-primary  nav-link active " role="tab"
                                        data-bs-toggle="tab" data-bs-target="#navs-edit-account"
                                        aria-controls="navs-edit-account" aria-selected="true">
                                        <i data-feather="user"></i> Edit Profile
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4" style="text-align: right;">
                            <button type="button" class="btn btn-primary generate-form-link "   
                                data-user-id="{{ getUser()->id }}">
                                <i data-feather="link"></i> Generate Form Link
                            </button>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="navs-edit-account" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <form id="edit-profile" class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework"
                                        action="" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="first_name">First Name <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" id="first_name"
                                                    placeholder="First Name" value="{{ $model->first_name ?? '' }}"
                                                    name="first_name">
                                                <span id="first_name_error" class="text-danger error"></span>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="last_name">Last Name</label>
                                                <input type="text" class="form-control" id="last_name"
                                                    placeholder="Last Name" value="{{ $model->last_name ?? '' }}"
                                                    name="last_name">
                                                <span id="last_name_error" class="text-danger error"></span>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="email">Email <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="email"
                                                    placeholder="Email" value="{{ $model->email ?? '' }}"
                                                    name="email">
                                                <span id="email_error" class="text-danger error"></span>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="address">Address</label>
                                                <input type="text" class="form-control" id="address"
                                                    placeholder="Address" value="{{ $model->address ?? '' }}"
                                                    name="address">
                                                <span id="address_error" class="text-danger error"></span>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="phone_number">Phone Number</label>
                                                <input type="text" class="form-control" id="phone_number"
                                                    placeholder="Phone Number" value="{{ $model->phone ?? '' }}"
                                                    name="phone_number">
                                                <span id="phone_number_error" class="text-danger error"></span>
                                            </div>
                                            <div class="col-12 order-2 order-xl-0">
                                                <button id="updateButton" type="submit"
                                                    class="btn btn-primary me-2">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $("#edit-profile").on("submit", function(event) {
                event.preventDefault();
                $("#first_name_error").html("");
                $("#last_name_error").html("");
                $("#email_error").html("");
                $("#address_error").html("");
                $("#phone_code_error").html("");
                $("#phone_number_error").html("");
                var formData = $(this).serialize();
                $.ajax({
                    data: formData,
                    url: "{{ route('profiles.update') }}",
                    method: "POST",
                    beforeSend: function() {
                        console.log("working")
                        showFancyBox();
                    },
                    success: function(res) {
                        hideFancyBox();
                        if (res.success == true) {
                            showToastr("success", "Success", res.message);
                            setTimeout(() => {
                                location.reload()
                            }, 1000);
                        }

                        if (res.success == false) {
                            showToastr("error", "Error", res.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        hideFancyBox()
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            if (errors.first_name) {
                                $('#first_name_error').text(errors.first_name[0]);
                            }
                            if (errors.email) {
                                $('#email_error').text(errors.email[0]);
                            }
                            if (errors.phone_number) {
                                $('#phone_number_error').text(errors.phone_number[0]);
                            }
                        } else {
                            console.log('An error occurred:', xhr);
                        }

                    }
                });

            });
            


        });
    </script>
@endpush
