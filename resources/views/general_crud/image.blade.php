    <div class="col-md-12 px-0">
        <label for="{{ $input_field['name'] }}">{{ $input_field['label'] }}</label>
        <input type="file" data-max-file-size="2M" name="{{ $input_field['name'] }}"
            id="{{ $input_field['name'] }} @foreach ($additional_ids as $ids) {{ $ids }} @endforeach"
            value="{{ $result ? $result[$input_field['name']] : null }}"
            data-default-file="@if ($result)@if (!isset($input_field['include_asset_function']) || $input_field['include_asset_function']){{env('ASSETS_STORAGE').$result[$input_field['name']]}}@else{{$result[$input_field['name']]}}@endif @endif"
            class="dropify @foreach ($additional_classes as $class) {{ $class }} @endforeach"
            @foreach ($input_field['html_params'] as $key => $value) {{ $key }}="{{ $value }}" @endforeach>
        @error($input_field['name'])
            <div class="validation-error"> {{ $message }}</div>
        @enderror
    </div>

   
