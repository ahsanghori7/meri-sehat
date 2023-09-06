@extends('layouts.admin.app')
@section('page_header')
{{ Str::singular($page_header) }}
@endsection
@section('content')
<div class="container-fluid animatedParent animateOnce my-3">
    <div class="animated fadeInUpShort">
        <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            <div class="row">


                <div class="col-md-8">

                    @if (isset($include_translation_section) && $include_translation_section)
                    <div class="card no-b  no-r">
                        <div class="row card-body">
                            <div class="col-md-12">
                                <h5 class="box_heading">Language And Translation</h5>
                            </div>
                            @if (Request::segment(2) == 'disease')
                                @can('default-page-general-info-select_language')
                                    @include('general_crud.languages_dropdown')
                                @endcan
                                @can('default-page-general-info-translation_of')
                                    @include('general_crud.english_records_dropdown')
                                @endcan
                            @elseif (Request::segment(2) == 'info-model')
                                @can('info-modal-management-add-select_language')
                                    @include('general_crud.languages_dropdown')
                                    @include('general_crud.english_records_dropdown')
                                @endcan
                            @else
                                @include('general_crud.languages_dropdown')
                                @include('general_crud.english_records_dropdown')
                            @endif
                        </div>
                    </div>
                    <br>
                    @endif
                    <div class="card no-b  no-r">
                        <div class="card-body">
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
                            @endforeach

                            @if ($include_status_radio)
                                @include('general_crud.status_radio', $input_field)
                            @endif
{{--                            @if ($include_is_mobile_show_radio)--}}
{{--                                @include('general_crud.is_mobile_show_radio')--}}
{{--                            @endif--}}
{{--                            @if ($include_is_web_show_radio)--}}
{{--                                @include('general_crud.is_web_show_radio')--}}
{{--                            @endif--}}
                        </div>

                    </div>


                </div>

                <div class="col-md-4">
                    <div class="card no-b  no-r">
                        <div class="card-body">
                            @foreach ($input_elements as $key => $input_field)
                            @if ($input_field['element_type'] == 'image')
                            @include('general_crud.image', $input_field)
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @if (Request::segment(2) == 'disease')
                    @can('disease-page-add-disease-save')
                    <div class="form-group col-12 d-flex justify-content-start mt-4 text-left">
                        <button type="submit" class="btn btn-success mt-2"><i class="icon-save"></i>
                            {{ $result ? 'Update' : 'Save' }}
                        </button>
                    </div>
                    @endcan
                @elseif (Request::segment(2) == 'health-scan-management')
                    @can('scan-management-general-save')
                        <div class="form-group col-12 d-flex justify-content-start mt-4 text-left">
                            <button type="submit" class="btn btn-success mt-2"><i class="icon-save"></i>
                                {{ $result ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    @endcan
                @elseif (Request::segment(2) == 'info-model')
                    @can('info-modal-management-add-save')
                        <div class="form-group col-12 d-flex justify-content-start mt-4 text-left">
                            <button type="submit" class="btn btn-success mt-2"><i class="icon-save"></i>
                                {{ $result ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    @endcan
                @elseif (Request::segment(2) == 'language')
                    @can('language-management-add-save')
                        <div class="form-group col-12 d-flex justify-content-start mt-4 text-left">
                            <button type="submit" class="btn btn-success mt-2"><i class="icon-save"></i>
                                {{ $result ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    @endcan
                @elseif (Request::segment(2) == 'media-language')
                @can('media-language-management-add-save')
                    <div class="form-group col-12 d-flex justify-content-start mt-4 text-left">
                        <button type="submit" class="btn btn-success mt-2"><i class="icon-save"></i>
                            {{ $result ? 'Update' : 'Save' }}
                        </button>
                    </div>
                @endcan
                @elseif (Request::segment(2) == 'widget')
                @can('widgets-management-save')
                    <div class="form-group col-12 d-flex justify-content-start mt-4 text-left">
                        <button type="submit" class="btn btn-success mt-2"><i class="icon-save"></i>
                            {{ $result ? 'Update' : 'Save' }}
                        </button>
                    </div>
                @endcan     
                @elseif (Request::segment(2) == 'speciality')
                @can('speciality-management-add-save')
                    <div class="form-group col-12 d-flex justify-content-start mt-4 text-left">
                        <button type="submit" class="btn btn-success mt-2"><i class="icon-save"></i>
                            {{ $result ? 'Update' : 'Save' }}
                        </button>
                    </div>
                @endcan
                @elseif (Request::segment(2) == 'service')
                @can('services-management-add-save')
                    <div class="form-group col-12 d-flex justify-content-start mt-4 text-left">
                        <button type="submit" class="btn btn-success mt-2"><i class="icon-save"></i>
                            {{ $result ? 'Update' : 'Save' }}
                        </button>
                    </div>
                @endcan        
                @else
                    <div class="form-group col-12 d-flex justify-content-start mt-4 text-left">
                        <button type="submit" class="btn btn-success mt-2"><i class="icon-save"></i>
                            {{ $result ? 'Update' : 'Save' }}
                        </button>
                    </div>
                @endif

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
    var test = function(that) {
        let test = $(that).val();
        console.log(test);
    }
</script>
@endpush
