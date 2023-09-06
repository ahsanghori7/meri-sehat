    <div class="col-md-12 px-0 mb-3">

        <label for="{{ $input_field['name'] }}">{{ $input_field['label'] }}</label>
        <textarea name="{{ $input_field['name'] }}" id="{{ $input_field['name'] }} @foreach ($additional_ids as $ids) {{ $ids }} @endforeach"
                  rows="{{ $input_field['rows'] }}" class="form-control  @if ($input_field['editor']) summernote @endif @foreach ($additional_classes as $class) {{ $class }} @endforeach" @foreach ($input_field['html_params'] as $key => $value) {{ $key }} = "{{ $value }}" @endforeach
        placeholder="{{ $input_field['placeholder'] }}">{{ $result ? $result[$input_field['name']] : old($input_field['name']) }}</textarea>
        @error($input_field['name'])<div class="validation-error"> {{ $message }}</div> @enderror

    </div>
