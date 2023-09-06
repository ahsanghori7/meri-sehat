@php
$input_field = [
    'label' => 'Translation Of',
    'name' => 'translation_of',
    'options' => $english_records,
    'value_element' => 'id',
    'label_element' => $english_records_name_element,
    'html_params' => ['required' => 'required'],
];
@endphp

@if ($result)
    @if (Request::segment(2) == 'article')
        @can('article-management-add-article-select_language')
            <h6>{{ $result->lang_id != App\Models\Language::ENGLISH ? (isset($result->translationOf) ? 'Translation of: ' . $result->translationOf[$english_records_name_element] : 'Parent Record') : 'Parent Record' }}</h6>
            <input type="hidden" name="{{ $input_field['name'] }}" value="{{$result['translation_of']}}">
        @endcan
    @else
        <h6>{{ $result->lang_id != App\Models\Language::ENGLISH ? (isset($result->translationOf) ? 'Translation of: ' . $result->translationOf[$english_records_name_element] : 'Parent Record') : 'Parent Record' }}</h6>
        <input type="hidden" name="{{ $input_field['name'] }}" value="{{$result['translation_of']}}">
    @endif
@else
    @if (Request::segment(2) == 'article')
        @can('article-management-add-article-select_language')
            <div class="col-md-6 mb-3 translation_of_div" style="display: none;">
                <label for="{{ $input_field['name'] }}">{{ $input_field['label'] }}</label>
                <select id="{{ $input_field['name'] }}" name="{{ $input_field['name'] }}" class="form-control translation_of">
                    <option value=''>Select Parent</option>
                    <option value='parent'>Parent Record</option>
                    @foreach ($input_field['options'] as $key => $option)
                        <option value="{{ $option[$input_field['value_element']] }}"
                                @if ($result) @if ($result['translation_of'] == $option[$input_field['value_element']])
                                    selected @endif
                            @endif>{{ $option[$input_field['label_element']] }}</option>
                    @endforeach
                </select>
                @error($input_field['name'])
                <div class="validation-error"> {{ $message }}</div>
                @enderror
            </div>
        @endcan
    @else
        <div class="col-md-6 mb-3 translation_of_div" style="display: none;">
            <label for="{{ $input_field['name'] }}">{{ $input_field['label'] }}</label>
            <select id="{{ $input_field['name'] }}" name="{{ $input_field['name'] }}" class="form-control translation_of">
                <option value=''>Select Parent</option>
                <option value='parent'>Parent Record</option>
                @foreach ($input_field['options'] as $key => $option)
                    <option value="{{ $option[$input_field['value_element']] }}"
                            @if ($result) @if ($result['translation_of'] == $option[$input_field['value_element']])
                                selected @endif
                        @endif>{{ $option[$input_field['label_element']] }}</option>
                @endforeach
            </select>
            @error($input_field['name'])
            <div class="validation-error"> {{ $message }}</div>
            @enderror
        </div>
    @endif
@endif

@push('scripts')
<script>
    $(document).ready(function() {
        $('.lang_id').change(function() {
            var lang_id = $(this).val();
            if (lang_id == '1') {
                $('.translation_of').removeAttr('required');
                $('.translation_of_div').slideUp();

            } else {
                $('.translation_of').attr('required','required');
                $('.translation_of_div').slideDown();
            }
        })
    })
</script>
@endpush
