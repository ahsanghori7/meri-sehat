    <div class="col-md-12 px-0 mb-3">
        <label for="{{ $input_field['name'] }}">{{ $input_field['label'] }}</label>
        <input type="{{ $input_field['input_type'] }}" name="{{ $input_field['name'] }}"
               id="{{ $input_field['name'] }} @foreach ($additional_ids as $ids) {{ $ids }} @endforeach"
               value="{{ $result ? $result[$input_field['name']] : old($input_field['name']) }}"
               class="form-control  @foreach ($additional_classes as $class)
          {{ $class }} @endforeach"
               placeholder="{{ $input_field['placeholder'] }}" @foreach ($input_field['html_params'] as $key => $value) {{ $key }} = "{{ $value }}"
        @endforeach>
        @error($input_field['name'])<div class="validation-error"> {{ $message }}</div> @enderror
    </div>
