<div class="row" style="padding:20px;">
    <div class="text-center mb-2">
        <img src="{{asset('logos/white.png')}}" alt="" style="max-width :170px;">
        <h1 class="mb-1">Customer Details</h1>
        <p>View the details of customer</p>
    </div>

    <div class="row" style="margin-bottom:35px">
        <div class="col-md-6">
            <label for="first_name" style="font-size: 16px;font-weight: bold;">Store Name</label>
            <div id="first_name" style="font-size: 15px;">{{$customer->store_name ?? ''}}</div>
        </div>
        <div class="col-md-6">
            <label for="last_name" style="font-size: 16px;font-weight: bold;">Store URL</label>
            <div id="last_name" style="font-size: 15px;"> {{$customer->store_url ?? '' }} </div>
        </div>
    </div>

    <div class="row" style="margin-bottom:35px ; margin-top:30px;">
        <div class="col-md-6">
            <label for="first_name" style="font-size: 16px;font-weight: bold;">First Name</label>
            <div id="first_name" style="font-size: 15px;">{{$customer->first_name ?? ''}}</div>
        </div>
        <div class="col-md-6">
            <label for="last_name" style="font-size: 16px;font-weight: bold;">Last Name</label>
            <div id="last_name" style="font-size: 15px;"> {{$customer->last_name ?? '' }} </div>
        </div>
    </div>

    <div class="row" style="margin-bottom:35px">
        <div class="col-md-6">
            <label for="first_name" style="font-size: 16px;font-weight: bold;">Email</label>
            <div id="first_name" style="font-size: 15px;">{{$customer->email ?? ''}}</div>
        </div>
    </div>

    <div class="row" style="margin-bottom:35px">
        <div class="col-md-6">
            <label for="first_name" style="font-size: 16px;font-weight: bold;">Country Code</label>
            <div id="first_name" style="font-size: 15px;">+{{$customer->country->phonecode ?? ''}}</div>
        </div>
        <div class="col-md-6">
            <label for="last_name" style="font-size: 16px;font-weight: bold;">Phone Number</label>
            <div id="last_name" style="font-size: 15px;"> {{$customer->phone_number ?? '' }} </div>
        </div>
    </div>


    <div class="row" style="margin-bottom:35px">
        <div class="col-md-12">
            <table class="datatables-users   table-hover table border-top dataTable no-footer dtr-column ">
                <thead>
                    <th>#</th>
                    <th>Form</th>
                    <th>Status</th>
                </thead>
                <tbody>
                    @if(isset($customer->forms) && !empty($customer->forms) && count($customer->forms))
                    @foreach($customer->forms as $index => $value)
                    <tr>
                        <td>{{++$index }}</td>
                        <td>
                            <a href="{{route('coorporate-forms.show' , $value->id)}}" target="_blank">VIEW</a>
                        </td>
                        <td>
                            <span class="badge bg-{{$value->hasStatus->color ?? 'info'}}">{{$value->hasStatus->name ?? ''}}</span>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>






</div>