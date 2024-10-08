@extends("admin.layouts.master")
@push("title" , $title ?? '')

@section("content")

<input type="hidden" name="" id="page_reload" value="1">
<div id="dashboard-ecommerce">

    <!-- Bread crubmbs -->
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Coorporate Form Details</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard.index')}}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('coorporate-forms.index')}}">Coorporate Forms</a>
                            </li>
                            <li class="breadcrumb-item active">Details
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        @canany(['coorporate-form-status' , 'coorporate-form-viewfollowups' , 'coorporate-form-sendemail'])
        <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
            <div class="mb-1 breadcrumb-right">
                <div class="dropdown">
                    <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="grid"></i></button>
                    <div class="dropdown-menu dropdown-menu-end">
                        @can("coorporate-form-status")
                        @if(isset($form->status) && !empty($form->status) && $form->status != 5)
                        <a class="dropdown-item  add-followup" href="javascript:;" data-route="{{route('coorporate-forms.addFollowup')}}" data-form-id="{{$form->id ?? ''}}">
                            <i class="me-1" data-feather="message-square"></i>
                            <span class="align-middle">Add Followup</span>
                        </a>
                        @endif
                        @endcan
                        @can("coorporate-form-viewfollowups")
                        <a class="dropdown-item  view-followup" href="javascript:;" data-route="{{route('coorporate-forms.viewFollowups')}}" data-form-id="{{$form->id ?? ''}}">
                            <i class="me-1" data-feather="clock"></i>
                            <span class="align-middle">View History</span>
                        </a>
                        @endcan
                        @can("coorporate-form-sendemail")
                        @if(isset($form->status) && !empty($form->status) && $form->status != 5)
                        <a class="dropdown-item  send-email-to-customer" href="javascript:;" data-route="{{route('coorporate-forms.sendEmailToCustomer')}}" data-form-id="{{$form->id ?? ''}}" data-customer-email="{{$form->email ?? ''}}">
                            <i class="me-1" data-feather="mail"></i>
                            <span class="align-middle">Send Email</span>
                        </a>
                        @endif
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        @endcanany
    </div>
    <!-- Bread crubmbs -->


    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">

                <div class="row" style="padding:20px;">
                    <div class="text-center mb-2">
                        <img src="{{asset('logos/white.png')}}" alt="" style="max-width :170px;">
                        <h1 class="mb-1">Coorporate Form</h1>
                        <p>View the details submitted by customer</p>
                    </div>
                    <div class="row" style="margin-bottom:35px">
                        <div class="col-md-6">
                            <label for="first_name" style="font-size: 16px;font-weight: bold;">Store Name</label>
                            <div id="first_name" style="font-size: 15px;">{{$form->store_name ?? ''}}</div>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" style="font-size: 16px;font-weight: bold;">Store URL</label>
                            <div id="last_name" style="font-size: 15px;"> {{$form->store_url ?? '' }} </div>
                        </div>
                        <div class="col-md-6" style="margin-top: 30px;">
                            <label for="last_name" style="font-size: 16px;font-weight: bold;">IP</label>
                            <div id="last_name" style="font-size: 15px;"> {{$form->ip ?? '' }} </div>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom:35px ; margin-top:30px;">
                        <div class="col-md-6">
                            <label for="first_name" style="font-size: 16px;font-weight: bold;">First Name
                                @if(isset($form->customer) && !empty($form->customer))
                                <a href="{{route('customers.customerDetail' , $form->customer->id)}}" target="_blank">View Customer</a>
                                @endif

                            </label>
                            <div id="first_name" style="font-size: 15px;">{{$form->first_name ?? ''}}</div>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" style="font-size: 16px;font-weight: bold;">Last Name</label>
                            <div id="last_name" style="font-size: 15px;"> {{$form->last_name ?? '' }} </div>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom:35px">
                        <div class="col-md-6">
                            <label for="first_name" style="font-size: 16px;font-weight: bold;">Email</label>
                            <div id="first_name" style="font-size: 15px;">{{$form->email ?? ''}}</div>
                        </div>
                        <div class="col-md-6 ">
                            <label for="last_name" style="font-size: 16px;font-weight: bold;">Color</label>
                            <div class="row">
                                <div class="col-md-2">
                                    <div id="last_name" style="font-size: 15px;"> {{!empty($form->hasColor->name) ? strtoupper($form->hasColor->name) : '-' }}</div>
                                </div>
                                @if(isset($form->hasColor->code) && !empty($form->hasColor->code))
                                <div class="col-md-2">
                                    <div style="width: 15px;height:15px;background:{{$form->hasColor->code ?? ''}};border-radius: 100%;"></div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="row" style="margin-bottom:35px">
                        <div class="col-md-6 mb-4">
                            <label for="first_name" style="font-size: 16px;font-weight: bold;">Country </label>
                            <div id="first_name" style="font-size: 15px;">
                                @if(isset($form->country->name) && !empty($form->country->name))
                                {{$form->country->name}}
                                @else
                                -
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="last_name" style="font-size: 16px;font-weight: bold;">Phone Number</label>
                            <div id="last_name" style="font-size: 15px;">
                                @if(isset($form->country->phonecode) && !empty($form->country->phonecode))
                                +{{$form->country->phonecode}} -
                                @endif
                                {{$form->phone_number ?? '' }}
                            </div>
                        </div>
                    </div>


                    <div class="row" style="margin-bottom:35px">
                        <div class="col-md-6">
                            <label for="first_name" style="font-size: 16px;font-weight: bold;">Quantity</label>
                            <div id="first_name" style="font-size: 15px;">{{$form->quantity ?? ''}}

                                @if(isset($form->quantity) && !empty($form->quantity) )
                                @if($form->quantity > 1)
                                units
                                @elseif($form->quantity < 2)
                                    unit
                                    @elseif($form->quantity == 0)
                                    unit
                                    @endif
                                    @else
                                    unit
                                    @endif
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom:35px">
                        <div class="col-md-6">
                            <label for="first_name" style="font-size: 16px;font-weight: bold;">Date</label>
                            <div id="first_name" style="font-size: 15px;">{{formatDate($form->date) ?? ''}}</div>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" style="font-size: 16px;font-weight: bold;">Submitted At</label>
                            <div id="last_name" style="font-size: 15px;"> {{formatDateTime($form->created_at) ?? '' }} </div>
                        </div>
                    </div>



                    <div class="row" style="margin-bottom:35px">
                        <div class="col-md-12">
                            <label for="first_name" style="font-size: 16px;font-weight: bold;">Interested Categories</label>
                            <div class="row" style="margin-top: 20px;">
                                @if(isset($form->categories) && !empty($form->categories) && count($form->categories) > 0)
                                @foreach($form->categories as $a => $b)
                                <div class="col-md-2">
                                    <a @if(isset($b->category->image) && !empty($b->category->image)) href="{{asset(config('project.upload_path.categories') . $b->category->image ?? '')}}" target="_blank" @else href="javascript:;" @endif>
                                        <div class="card">
                                            <div class="card-body" style="padding: 0px;">
                                                @if(isset($b->category->image) && !empty($b->category->image) && checkFileExists($b->category->image, config("project.upload_path.categories")) == true )
                                                <img src="{{asset(config('project.upload_path.categories') . $b->category->image ?? '')}}" alt="" style="width: 100%;height: 100%;object-fit: cover;max-height: 250px !important;min-height: 250px !important;">
                                                @endif
                                            </div>
                                            <span style="color: white;font-size: 16px;text-align: center;">{{$b->category->title ?? ''}}</span>
                                        </div>
                                    </a>
                                </div>
                                @endforeach
                                @else
                                <span>No Categories found</span>
                                @endif
                            </div>
                        </div>
                    </div>



                    <div class="row" style="margin-bottom:35px">
                        <div class="col-md-12">
                            <label for="first_name" style="font-size: 16px;font-weight: bold;">Files</label>
                            <div class="row" style="margin-top: 20px;" id="files_row">

                            </div>
                        </div>

                    </div>




                    <div class="row" style="margin-bottom:35px">
                        <div class="col-md-12">
                            <label for="first_name" style="font-size: 16px;font-weight: bold;">Description</label>
                            <div id="first_name" style="font-size: 15px;">{{$form->long_description ?? '-'}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Customer IP Details
                @if(isset($ip_data->country_flag) && !empty($ip_data->country_flag))
                <img src="{{$ip_data->country_flag ?? ''}}" alt="" style="width: 70px;heighg:50px">
                @endif
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="type">Type</label>
                        <div id="type">{{$ip_data->type ?? '-'}}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="type">Continent</label>
                        <div id="type">{{$ip_data->continent ?? '-'}}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="type">Continet Code</label>
                        <div id="type">{{$ip_data->continent_code ?? '-'}}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="type">Country</label>
                        <div id="type">{{$ip_data->country ?? '-'}}


                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="type">Country Code</label>
                        <div id="type">{{$ip_data->country_code ?? '-'}}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="type">Country Capital</label>
                        <div id="type">{{$ip_data->country_capital ?? '-'}}</div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="type">Country Phone Code</label>
                        <div id="type">{{$ip_data->country_phone ?? '-'}}</div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="type">Country Region</label>
                        <div id="type">{{$ip_data->region ?? '-'}}</div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="type">Country City</label>
                        <div id="type">{{$ip_data->city ?? '-'}}</div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="type">Country ISP</label>
                        <div id="type">{{$ip_data->isp ?? '-'}}</div>
                    </div>


                    <div class="col-md-4 mb-3">
                        <label for="type">Country Timezone</label>
                        <div id="type">{{$ip_data->timezone ?? '-'}}</div>
                    </div>


                    <div class="col-md-4 mb-3">
                        <label for="type">Country Currency</label>
                        <div id="type">{{$ip_data->currency ?? '-'}}</div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="type">Country Currency Code</label>
                        <div id="type">{{$ip_data->currency_code ?? '-'}}</div>
                    </div>



                    <div class="col-md-4 mb-3">
                        <label for="type">Country Currency Symbol</label>
                        <div id="type">{{$ip_data->currency_symbol ?? '-'}}</div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="type">Country Currency Rates</label>
                        <div id="type">{{$ip_data->currency_rates ?? '-'}}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Follow Up Histories
            </div>
            <div class="card-body">
                <section class="basic-timeline">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Followup History</h4>
                                </div>
                                <div class="card-body" style="max-height: 600px !important;overflow-y: scroll;">
                                    <ul class="timeline">
                                        @if(isset($followups) && !empty($followups) && count($followups) > 0)
                                        @foreach($followups as $index => $value)
                                        <li class="timeline-item">
                                            <span class="timeline-point timeline-point-indicator"></span>
                                            <div class="timeline-event">
                                                <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                                    <h6>{{getUserName($value->user) ?? '-'}}</h6>
                                                    <span class="timeline-event-time" style="font-weight: bold;">{{formatDateTime($value->created_at)}} / {{$value->created_at->diffForHumans()}}</span>
                                                </div>
                                                <span class="badge bg-danger">{{$value->title ?? ''}} </span> <span class="badge bg-success">{{ getFormStatus($value)->name ?? '' }}</span>
                                                <p>{{$value->remarks ?? '-'}}</p>
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
            </div>
        </div>
    </div>
</div>



<!-- send email -->
<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-send-email">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="sendEmailModalBody">
                <form method="POST" id="sendEmailModalForm">
                    @csrf
                    <input type="hidden" name="email_form_id" id="email_form_id" value="">
                    <input type="hidden" name="email_form_route" id="email_form_route" value="">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="to_email">To <span class="text-danger">*</span></label>
                            <input type="text" name="to_email" id="to_email" class="form-control">
                            <div class="text-danger" id="to_email_error"></div>
                            <!-- <small class="text-warning">This field can not be changed</small> -->
                        </div>
                        <div class="col-md-12 mb-2 ">
                            <label for="subject">Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" id="subject" class="form-control" placeholder="Enter subject of email">
                            <div class="text-danger" id="subject_error"></div>
                        </div>
                        <div class="col-md-12 mb-2 ">
                            <label for="message">Message <span class="text-danger">*</span></label>
                            <textarea name="message" id="message" cols="30" rows="10" class="form-control" placeholder="Enter message of email"></textarea>
                            <div class="text-danger" id="message_error"></div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-success w-100 mt-2">
                                Save
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button type="reset" class="btn btn-danger w-100 mt-2" data-bs-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- send email -->




<!-- add followup modal -->
<div class="modal fade" id="addFollowupModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                Add Followup
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="addFollowupModalBody">

            </div>
        </div>
    </div>
</div>
<!-- add followup modal -->



<!-- view followup modal -->
<div class="modal fade" id="viewFollowups" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewFollowupsBody">
            </div>
        </div>
    </div>
</div>
<!-- view followup modal -->


@endsection
@push("scripts")
@include("admin.pages.customer_forms.components.scripts")

<script>
    $(document).ready(function() {
        setTimeout(() => {
            getFormFiles("{{$form->id}}", "files_row" , "col-md-1");
        }, 500);
    });
</script>
@endpush