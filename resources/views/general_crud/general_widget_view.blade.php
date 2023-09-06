@extends('layouts.widget.app')
@section('page_header')
{{ Str::singular($page_header) }}
@endsection
@section('content')
<div class="container-fluid animatedParent animateOnce my-3">
    <div class="animated fadeInUpShort">
        <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            <div class="row">


                <div class="col-md-12">

                    @if (isset($include_translation_section) && $include_translation_section)
                    <div class="card no-b  no-r">
                        <div class="row card-body">
                            <div class="col-md-12">
                                <h5 class="box_heading">Language And Translation</h5>
                            </div>
                            @include('general_crud.languages_dropdown')
                            @include('general_crud.english_records_dropdown')
                        </div>
                    </div>
                    <br>
                    @endif
                    <div class="card no-b  no-r">
                        <div class="card-body">
                            @if (isset($reference))
                                @php
                                    if($reference){
                                        $getReferenceWidget = \App\Models\ReferenceWidget::find($reference->id);
                                        $selectedLanguage = $getReferenceWidget->page ? $getReferenceWidget->page->lang_id : $getReferenceWidget->article->lang_id;
                                    }else {
                                        $selectedLanguage = 1;
                                    }
                                @endphp

                                @can($module_slug.'-widgets-'.$folder_name.'-heading')
                                <div class="col-md-12 px-0 mb-3">
                                    <label for="reference_heading">Heading</label>
                                    <input type="text" name="reference_heading" id="reference_heading"
                                           value="{{ $reference ? $reference->heading : old('reference_heading') }}" class="form-control"
                                           @if($selectedLanguage != 1) placeholder="سرخی درج کریں" dir="rtl" @else placeholder="Enter Heading" @endif>
                                    @error('reference_heading')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan

                                @can($module_slug.'-widgets-'.$folder_name.'-description')
                                <div class="col-md-12 px-0 mb-3">
                                    <label for="reference_description">Description</label>
                                    <textarea name="reference_description" id="reference_description" class="form-control summernote"
                                              placeholder="Description">{{ $reference ? $reference->description : old('reference_description') }}</textarea>
                                    @error('reference_description')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan

                                @can($module_slug.'-widgets-'.$folder_name.'-redirect_url')
                                <div class="col-md-12 px-0 mb-3">
                                    <label for="reference_redirect_url">Redirection URL</label>
                                    <input type="text" name="reference_redirect_url" id="reference_redirect_url"
                                           value="{{ $reference ? $reference->redirect_url : old('reference_redirect_url') }}" class="form-control"
                                           placeholder="Enter Heading">
                                    @error('reference_redirect_url')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan

                                @php
                                    $getAds = App\Models\AdsWindow::where('status', true)->get();
                                @endphp

                                @can($module_slug.'-widgets-'.$folder_name.'-ad_window')
                                <div class="col-md-12 px-0 mb-3">
                                    <label for="reference_ad_window_id">Ad Window</label>
                                    <select class="form-control" name="reference_ad_window_id" id="reference_ad_window_id">
                                        <option value=""> Select Ads </option>
                                        @foreach ($getAds as $key => $value)
                                            <option value="{{ $value->id }}"
                                                    @if ($reference && $reference->ad_window_id == $value->id) selected="selected" @endif>
                                                {{ $value->name . ' ( ' . $value->dimensions . ' )' }}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan

                            @endif
                            @if ($input_elements)
                            @foreach ($input_elements as $key => $input_field)
                            @if ($input_field['element_type'] == 'input')
                            @include('general_crud.input', $input_field)
                            @elseif ($input_field['element_type'] == 'textarea')
                            @include('general_crud.textarea', $input_field)
                            @elseif ($input_field['element_type'] == 'dropdown')
                            @include('general_crud.dropdown', $input_field)
                            @elseif ($input_field['element_type'] == 'radio')
                            @include('general_crud.radio', $input_field)
                            @endif
                            @if ($input_field['element_type'] == 'image')
                            @include('general_crud.image', $input_field)
                            @endif
                            @endforeach
                            @endif

                            @can($module_slug.'-widgets-'.$folder_name.'-status')
                                @if ($include_status_radio)
                                    @include('general_crud.status_radio')
                                @endif
                            @endcan

                            @can($module_slug.'-widgets-'.$folder_name.'-mobile_show')
                                @if ($include_is_mobile_show_radio)
                                    @include('general_crud.is_mobile_show_radio')
                                @endif
                            @endcan

                            @can($module_slug.'-widgets-'.$folder_name.'-web_show')
                                @if ($include_is_web_show_radio)
                                    @include('general_crud.is_web_show_radio')
                                @endif
                            @endcan
                        </div>

                    </div>


                </div>

                <div class="form-group col-12 d-flex justify-content-start mt-4 text-left">
                    <button type="submit" class="btn btn-success mt-2"><i class="icon-save"></i>
                        {{ $result ? 'Update' : 'Save' }}
                    </button>
                </div>

                {{-- <div class="col-md-8 card">
                        <div class="row">

                            @foreach ($input_elements as $key => $input_field)
                                @if ($input_field['element_type'] == 'input')
                                    @include('general_crud.input',$input_field)
                                @elseif ($input_field['element_type'] == 'textarea')
                                    @include('general_crud.textarea',$input_field)
                                @elseif ($input_field['element_type'] == 'dropdown')
                                    @include('general_crud.dropdown',$input_field)
                                @elseif ($input_field['element_type'] == 'radio')
                                    @include('general_crud.radio',$input_field)
                                @endif
                            @endforeach
                            @if ($include_status_radio)
                                @include('general_crud.status_radio',$input_field)
                            @endif


                        </div>

                    </div>

                    <div class="col-md-4 card">
                        <div class="row">

                            @foreach ($input_elements as $key => $input_field)
                                @if ($input_field['element_type'] == 'image')
                                    @include('general_crud.image',$input_field)
                                @endif
                            @endforeach

                        </div>
                    </div>

                </div>
                <div class="bg-transparent">
                    <button type="submit" class="btn btn-primary"><i class="icon-save"></i>
                        {{ $result ? 'Update' : 'Save' }}
                </button>
            </div> --}}
        </form>
    </div>
</div>
@endsection


@push('scripts')
<script>
    $(document).ready(function() {
        $('.lang_id').change(function() {
            var lang_id = $(this).val();
            if (lang_id == '1') {
                $('.translation_of').attr('required', '');
                $('.translation_of_div').slideUp();

            } else {
                $('.translation_of').attr('required', 'required');
                $('.translation_of_div').slideDown();
            }
        })
    })

</script>
@endpush
