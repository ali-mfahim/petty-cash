<div class="btn-group dropstart">
    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
    </button>
    <div class="dropdown-menu">

        @can("coorporate-form-view")
        <a class="dropdown-item " href="{{route('coorporate-forms.show' , $form->id)}}"> View</a>
        @endcan

        @can("coorporate-form-status")
        @if(isset($form->status) && !empty($form->status) && $form->status != 5)
        <a class="dropdown-item add-followup" href="javascript:;" data-route="{{route('coorporate-forms.addFollowup')}}" data-form-id="{{$form->id ?? ''}}"> Add Followup</a>
        @endif
        @endcan


        @can("coorporate-form-viewfollowups")
        <a class="dropdown-item view-followup" href="javascript:;" data-route="{{route('coorporate-forms.viewFollowups')}}" data-form-id="{{$form->id ?? ''}}"> View History </a>
        @endcan


        @can("coorporate-form-sendemail")
        @if(isset($form->status) && !empty($form->status) && $form->status != 5)
        <a class="dropdown-item send-email-to-customer" href="javascript:;" data-route="{{route('coorporate-forms.sendEmailToCustomer')}}" data-form-id="{{$form->id ?? ''}}" data-customer-email="{{$form->email ?? ''}}"> Send Email </a>
        @endif
        @endcan
    </div>
</div>