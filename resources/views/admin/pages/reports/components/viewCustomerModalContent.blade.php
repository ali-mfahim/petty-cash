<div class="row" style="padding:20px;">
    <div class="text-center mb-2">
        <img src="{{asset('logos/white.png')}}" alt="" style="max-width :170px;">
        <h1 class="mb-1">Customer Details</h1>
    </div>

    <div class="row" style="margin-bottom:35px">
        <div class="col-md-6" style="margin-top: 30px;">
            <label for="first_name" style="font-size: 16px;font-weight: bold;">Customer ID</label>
            <div id="last_name" style="font-size: 15px;">
                {{ isset($customer['id']) && !empty($customer['id']) ? eliminateGid($customer['id']) : "N/A" }}
            </div>
        </div>
        <div class="col-md-6" style="margin-top: 30px;">
            <label for="first_name" style="font-size: 16px;font-weight: bold;">Customer Name</label>
            <div id="first_name" style="font-size: 15px;">
                @if(isset($customer['firstName']) && !empty($customer['firstName']))
                {{$customer['firstName'] ?? ''}}
                @endif
                @if(isset($customer['lastName']) && !empty($customer['lastName']))
                - {{$customer['lastName'] ?? ''}}
                @endif
            </div>
        </div>
        <div class="col-md-6" style="margin-top: 30px;">
            <label for="last_name" style="font-size: 16px;font-weight: bold;">Email</label>
            <div id="last_name" style="font-size: 15px;"> {{$customer['email'] ?? "N/A" }} </div>
        </div>
        <div class="col-md-6" style="margin-top: 30px;">
            <label for="last_name" style="font-size: 16px;font-weight: bold;">Address 1 </label>
            <div id="last_name" style="font-size: 15px;"> {{isset($customer['defaultAddress']['address1']) && !empty($customer['defaultAddress']['address1'])  ? $customer['defaultAddress']['address1'] : "N/A" }} </div>
        </div>
        <div class="col-md-6" style="margin-top: 30px;">
            <label for="last_name" style="font-size: 16px;font-weight: bold;">Address 2 </label>
            <div id="last_name" style="font-size: 15px;"> {{isset($customer['defaultAddress']['formattedArea']) && !empty($customer['defaultAddress']['formattedArea']) ? $customer['defaultAddress']['formattedArea'] :  'N/A' }} </div>
        </div>
      
         
        <div class="col-md-6" style="margin-top: 30px;">
            <label for="last_name" style="font-size: 16px;font-weight: bold;">Phone</label>
            <div id="last_name" style="font-size: 15px;"> {{$customer['phone'] ?? 'N/A' }} </div>
        </div>
        <div class="col-md-6" style="margin-top: 30px;">
            <label for="last_name" style="font-size: 16px;font-weight: bold;">Total Number of Orders</label>
            <div id="last_name" style="font-size: 15px;"> {{$customer['numberOfOrders'] ?? 'N/A' }} </div>
        </div>
        <div class="col-md-6" style="margin-top: 30px;">
            <label for="last_name" style="font-size: 16px;font-weight: bold;">Tags </label>
            <div id="last_name" style="font-size: 15px;">
                @if(isset($customer['tags']) && !empty($customer['tags']))
                @foreach($customer['tags'] as $index => $value)
                {!! formatAsTag($value) !!}
                @endforeach
                @endif
            </div>
        </div>
            
        <div class="col-md-6" style="margin-top: 30px;">
            <label for="last_name" style="font-size: 16px;font-weight: bold;">Created At </label>
            <div id="last_name" style="font-size: 15px;"> {{isset($customer['createdAt']) && !empty($customer['createdAt']) ? date("M d, Y / h:i A" ,strtotime($customer['createdAt'])) :  'N/A' }} </div>
        </div>
        <div class="col-md-6" style="margin-top: 30px;">
            <label for="last_name" style="font-size: 16px;font-weight: bold;">Updated At </label>
            <div id="last_name" style="font-size: 15px;"> {{isset($customer['updatedAt']) && !empty($customer['updatedAt']) ? date("M d, Y / h:i A" ,strtotime($customer['updatedAt']))  :  'N/A' }} </div>
        </div>

    </div>

</div>