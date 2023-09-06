@php
$input_field = [
    'label' => 'Select Language',
    'name' => 'lang_id',
    'options' => App\Models\Language::where('status', 1)->get(),
    'value_element' => 'id',
    'label_element' => 'name',
    'html_params' => ['required' => 'required'],
];
@endphp

<div class="col-md-6 mb-3">
    @if ($result)
        <input type="hidden" name="{{ $input_field['name'] }}" value="{{$result['lang_id']}}">
        <h6>Language: {{ $result->lang_id != App\Models\Language::ENGLISH ? $result->language->name : 'English' }}
        </h6>
    @else
        <label for="{{ $input_field['name'] }}">{{ $input_field['label'] }}</label>
        <select id="{{ $input_field['name'] }}" name="{{ $input_field['name'] }}" class="form-control lang_id">
            @foreach ($input_field['options'] as $key => $option)
                <option value="{{ $option[$input_field['value_element']] }}"
                    @if ($result) @if ($result['lang_id'] == $option[$input_field['value_element']])
                     selected @endif
                    @endif>{{ $option[$input_field['label_element']] }}</option>
            @endforeach
        </select>
        @error($input_field['name'])
            <div class="validation-error"> {{ $message }}</div>
        @enderror
    @endif
</div>
