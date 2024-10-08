<div class="btn-group">
    <button type="button" id="status-button_{{$form->id}}" class="btn btn-primary btn-sm dropdown-toggle " data-bs-toggle="dropdown" aria-expanded="false">
        {{$form->hasStatus->name ?? '-'}}
    </button>
    <div class="dropdown-menu" class="status_change">
        @if(isset($statuses) && !empty($statuses) && count($statuses) > 0)
        @foreach($statuses as $index => $value)
        <a class="dropdown-item status_item" href="#"
            data-status-id="{{$value->id ?? ''}}"
            data-status-name="{{$value->name ?? ''}}"
            data-form-id="{{$form->id ?? ''}}"
            data-old-status-name="{{$form->hasStatus->name ?? '' }}"
            data-old-status-id="{{$form->hasStatus->id ?? '' }}">{{$value->name ?? ''}}</a>
        @endforeach
        @endif
    </div>
</div>