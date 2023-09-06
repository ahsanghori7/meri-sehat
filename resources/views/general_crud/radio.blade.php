<div class="col-md-12 px-0 mb-3">
    <label for="{{ $input_field['name'] }}" class="d-block">{{ $input_field['label'] }}</label>
    <div class="d-flex justify-content-between">
        <div class="col-md-12">
            @foreach ($input_field['buttons'] as $key => $button)
                <div class="col-md-6">
                    <input
                        class="form-check-input @foreach ($button['additional_classes'] as $class) {{ $class }} @endforeach"
                        name="{{ $input_field['name'] }}" type="radio"
                        @if ($result) @if ($result[$input_field['name']] == $button['value'])
                    checked @endif
                    @elseif($button['checked_on_null']) checked @endif
                    value="{{ $button['value'] }}"
                    id="{{ Str::slug($button['label']) }} @foreach ($button['additional_ids'] as $ids) {{ $ids }} @endforeach">
                    <label class="form-check-label" for="{{ Str::slug($button['label']) }}">
                        {{ $button['label'] }}
                    </label>
                </div>
            @endforeach
        </div>
    </div>
</div>
