       <div class="col-md-6 px-0 mb-3">
           <label for="{{ $input_field['name'] }}">{{ $input_field['label'] }}</label>
           <select id="{{ $input_field['name'] }} @foreach ($additional_ids as $ids) {{ $ids }} @endforeach" @foreach ($input_field['html_params'] as $key=> $value) {{ $key }}="{{ $value }}" @endforeach
               name="{{ $input_field['name'] }}"
               class="form-control @foreach ($additional_classes as $class) {{ $class }} @endforeach">
               <option value="">Select {{ $input_field['label'] }}</option>
               @if ($input_field['element_type'] == 'key_value')
               @foreach ($input_field['options'] as $key => $option)
               <option value="{{ $key }}" @if ($result) @if ($result[$input_field['name']]==$option) selected @endif @endif>{{ $option }}</option>
               @endforeach
               @else
               @foreach ($input_field['options'] as $key => $option)
               <option value="{{ $option[$input_field['value_element']] }}" @if ($result) @if (isset($input_field['select_element'])) @if ($result[$input_field['select_element']]==$option[$input_field['value_element']]) selected @endif @else @if ($result[$input_field['value_element']]==$option[$input_field['value_element']]) selected @endif @endif @endif>{{ $option[$input_field['label_element']] }}</option>
               @endforeach
               @endif
           </select>
           @error($input_field['name'])
           <div class="validation-error"> {{ $message }}</div>
           @enderror
       </div>
