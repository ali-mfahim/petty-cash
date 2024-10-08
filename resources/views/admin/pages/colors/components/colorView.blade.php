<div class="d-flex align-items-center">
    <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
        @if(isset($record->code ) && !empty($record->code ))
        <div id="" style="width: 50px;height: 50px;background: {{$record->code ?? 'ffffff'}} ;border-radius: 100%;"></div>
        @else
        {!! getWordInitial($record->name) !!}
        @endif
    </div>
    <div class="d-flex flex-column">
        <span>{{ isset($record->name) && !empty($record->name)  ? strtoupper($record->name) :   "-" }}</span>
    </div>
</div>