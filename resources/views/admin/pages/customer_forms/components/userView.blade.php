<div class="d-flex align-items-center">
    <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
        <a href="javascript:void(0);">
            <div class="symbol-label">
                @if (isset($record->image) && !empty($record->image))
                <img src="{{ asset(config('project.upload_path.users').$record->image ?? null) }}" alt="{{ getUserName($recordF) ?? null }}" class="w-100" />
                @else
                {!! getWordInitial($record->first_name) !!}
                @endif
            </div>
        </a>
    </div>
    <div class="d-flex flex-column">
        <span>{{ getUserName($record) ?? "" }}</span>
        <span style="opacity: .7;">{{ $record->email ?? "" }}</span>
    </div>
</div>