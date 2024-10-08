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
            <label for="first_name" style="font-size: 16px;font-weight: bold;">First Name</label>
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
        <div class="col-md-6">
            <label for="last_name" style="font-size: 16px;font-weight: bold;">Color</label>
            <div class="row">
                <div class="col-md-3">
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
                                <img src="{{asset(config('project.upload_path.categories') . $b->category->image ?? '')}}" alt="" style="width: 100%;height: 100%;object-fit: cover;">
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




<!-- 

    <div class="row" style="margin-bottom:35px">
        <div class="col-md-12">
            <label for="first_name" style="font-size: 16px;font-weight: bold;">Files</label>
            <div class="row" style="margin-top: 20px;" id="files_row">

            </div>
        </div>

    </div>
 -->



    <div class="row" style="margin-bottom:35px">
        <div class="col-md-12">
            <label for="first_name" style="font-size: 16px;font-weight: bold;">Description</label>
            <div id="first_name" style="font-size: 15px;">{{$form->long_description ?? '-'}}</div>
        </div>
    </div>
</div>