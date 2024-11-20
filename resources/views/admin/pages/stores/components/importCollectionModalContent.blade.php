<div class="bs-stepper-content shadow-none">
    <div id="create-app-details" class="" role="tabpanel" aria-labelledby="create-app-details-trigger">
        <input type="hidden" class="export_store_id" value="{{$store->id}}">
        <h5 class="mt-2 pt-1">Stores</h5>
        <ul class="list-group list-group-flush">
            @if(isset($stores) && !empty($stores))
            @foreach($stores as $index => $value)
            <li class="list-group-item border-0 px-0  @if($value->id == $store->id) disabled_import_store_row @endif" @if($value->id == $store->id) style="background-color: #201f1f;" @endif>
                <label for="storeRadio_{{$index}}" class="d-flex cursor-pointer">
                    <span class="avatar avatar-tag bg-light-info me-1" style="margin-left:15px">
                        <img src="{{asset(config('project.upload_path.store_logo_thumb')  . $value->logo  )}}" alt="" style="max-width:20px;">
                    </span>
                    <span class="d-flex align-items-center justify-content-between flex-grow-1">
                        <span class="me-1">
                            <span class="h5 d-block fw-bolder">{{$value->name ?? '-'}}</span>
                            <span>{{$value->domain ?? '-'}}</span>
                            @if($value->id == $store->id)
                            <div class="text-danger" style="font-weight: bold;">Can no select this store</div>
                            @endif
                        </span>
                        <span>
                            <div class="form-check me-3 me-lg-5">
                                <input class="form-check-input storeImportCollectionCheckbox " data-store-id="{{$value->id}}" name="storeRadio" type="checkbox" value="{{$value->id}}" id="storeRadio_{{$index}}" @if($value->id == $store->id) disabled @endif />
                            </div>
                        </span>
                    </span>
                </label>
            </li>
            @endforeach
            @endif
        </ul>
        <div class="text-center  mt-2">
            <button class="btn btn-outline-secondary  " type="reset" data-bs-dismiss="modal" aria-label="Close">
                <span class="align-middle d-sm-inline-block d-none">Cancel</span>
            </button>
            <button class="btn btn-primary import_submit_button " style="margin-left:20px" disabled>
                <span class="align-middle d-sm-inline-block d-none">Import</span>
            </button>
        </div>
    </div>


</div>