@extends('layouts.admin.app')
@section('page_header')
{{ $page_header }}
@endsection
@php
$input_types = [
'text_box' => 'Text Box',
'text_area' => 'Text Area',
'dropdown' => 'Dropdown',
];
@endphp
@section('content')
<form method="post" class="needs-validation" id="disable_enter_submit" enctype="multipart/form-data" novalidate>
    @csrf
    <input type="hidden" class="option_index" value="{{ $result ? ($result->fields ? $result->fields->count() : 1) : 1}}">
    <div class="container-fluid animatedParent animateOnce my-3">
        <div class="animated fadeInUpShort">
            <div class="row">
                <div class="col-md-8 ">
                    <div class="box_border">
                        <div class="row">
                            <div class="col-md-12"></div>

                            @can('questionaire-form-add-speciality_with_add')
                            <div class="col-md-12 mb-3">
                                <label for="type">Speciality</label>
                                <select class="form-control" name="speciality_id" required>
                                    <option value=""> Select Speciality </option>
                                    @if($result && $result->speciality)
                                    <option value="{{$result->speciality_id}}" selected>{{$result->speciality->name}}</option>
                                    @endif
                                    @foreach ($specialities as $speciality)
                                    <option value="{{ $speciality->id }}">
                                        {{ $speciality->name }}</option>
                                    @endforeach
                                    @if($result && !$result->speciality)
                                    <option value="none" selected>None</option>
                                    @endif
                                </select>
                                @error('speciality_id')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-12 mb-3">
                                <div class="bg-transparent">
                                    <button type="button" class="btn btn-primary px-3 btn-sm add_field">
                                        <i class="icon-add"></i>Add</button>
                                </div>
                            </div>
                            @endcan

                            @if($result && $result->fields && $result->fields->count() > 0)

                            <div class="col-md-12 m-0 p-0 row fields_div">
                                @foreach ($result->fields as $key => $field)
                                <div class="col-md-12 m-0 p-0 row">
                                    <div class="col-md-12 mb-3">
                                        <div class="bg-transparent">
                                            <button type="button" class="btn btn-danger px-3 btn-sm delete_field">
                                                <i class="icon-trash mr-2"></i>Delete Field</button>
                                        </div>
                                    </div>

                                    @can('questionaire-form-add-title')
                                    <div class="col-md-6 mb-3">
                                        <label for="title">Title</label>
                                        <input type="text" name="title[]" id="title" class="form-control" value="{{$field->title}}" placeholder="Enter Title" required>
                                        @error('title')
                                        <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endcan

                                    @can('questionaire-form-add-description')
                                    <div class="col-md-6 mb-3">
                                        <label for="description">Description</label>
                                        <input type="text" name="description[]" id="description" value="{{$field->description}}" class="form-control" placeholder="Enter Description" required>
                                        @error('description')
                                        <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endcan

                                    @can('questionaire-form-add-type')
                                    <div class="col-md-12 mb-3">
                                        <label for="input_type">Type</label>
                                        <select class="form-control input_type" name="input_type[]" id="input_type" required>
                                            @foreach ($input_types as $input_type_key => $input_type)
                                            <option value="{{ $input_type_key }}" @if ($field->input_type == $input_type_key) selected="selected" @endif>
                                                {{ $input_type }}</option>
                                            @endforeach
                                        </select>
                                        @error('input_type')
                                        <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endcan

                                    <div class="dropdown_options col-md-12 mb-3 px-0 mx-0 row" @if ($field->input_type != 'dropdown') style="display: none" @endif >
                                        <div class="col-md-12 mb-3">
                                            <div class="bg-transparent">
                                                <button type="button" data-option_index="{{$key}}" class="btn btn-warning btn-sm add_dropdown_option">
                                                    <i class="icon-add"></i></button>
                                            </div>
                                        </div>
                                        @if ($field->input_type == 'dropdown')
                                        @foreach (json_decode($field->json_params) as $option_key => $option)
                                        <div class="col-md-6 mb-3">
                                            <div>
                                                <div class="row d-flex m-1 justify-content-between">
                                                    <label for="option">Option</label>
                                                    <button type="button" class="btn btn-danger btn-sm delete_dropdown_option">
                                                        <i class="icon-trash"></i>
                                                    </button>
                                                </div>
                                                <input type="text" name="option[{{$key}}][]" id="option" class="form-control" value="{{$option}}" placeholder="Enter Option" required>
                                            </div>
                                        </div>
                                        @endforeach
                                        @endif
                                    </div>


                                </div>
                                @endforeach
                                @else
                                <div class="col-md-12 m-0 p-0 row fields_div">
                                    @can('questionaire-form-add-title')
                                        <div class="col-md-6 mb-3">
                                            <label for="title">Title</label>
                                            <input type="text" name="title[]" id="title" class="form-control" placeholder="Enter Title" required>
                                            @error('title')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endcan

                                    @can('questionaire-form-add-description')
                                        <div class="col-md-6 mb-3">
                                            <label for="description">Description</label>
                                            <input type="text" name="description[]" id="description" class="form-control" placeholder="Enter Description" required>
                                            @error('description')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endcan

                                    @can('questionaire-form-add-type')
                                    <div class="col-md-12 mb-3">
                                        <label for="input_type">Type</label>
                                        <select class="form-control input_type" name="input_type[]" id="input_type" required>
                                            @foreach ($input_types as $key => $input_type)
                                            <option value="{{ $key }}" @if ($result && $result->input_type == $key) selected="selected" @endif>
                                                {{ $input_type }}</option>
                                            @endforeach
                                        </select>
                                        @error('input_type')
                                        <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endcan


                                    <div class="dropdown_options col-md-12 mb-3 px-0 mx-0 row" style="display: none">
                                        <div class="col-md-12 mb-3">
                                            <div class="bg-transparent">
                                                <button type="button" data-option_index="0" class="btn btn-warning btn-sm add_dropdown_option">
                                                    <i class="icon-add"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="row d-flex m-1 py-1 justify-content-between"><label for="option">Option</label></div>
                                            <input type="text" name="option[0][]" id="option" class="form-control" placeholder="Enter Option">
                                        </div>
                                    </div>


                                </div>

                                @endif




                            </div>
                            @can('questionaire-form-add-status')
                                @include('general_crud.status_radio')
                            @endcan
                        </div>






                        <div class="bg-transparent">
                            @can('questionaire-form-add-save')
                                <button type="submit" class="cst_btn px-5 btn-sm">
                                    {{-- <i class="icon-save"></i> --}}
                                    {{ $result ? 'Update' : 'Save' }}
                                </button>
                            @endcan
                        </div>

                    </div>

                    {{-- <div class="col-md-4">
                        <div class="box_border">
                            <div class="row">


                            </div>
                        </div>

                    </div> --}}



                </div>
            </div>
</form>
@endsection



@push('scripts')
<script>
    $(document).ready(function() {
        $(document).on('change', '.input_type', function() {
            let value = $(this).val();
            if (value == 'dropdown') {
                $(this).parent().next().show();
            } else {
                $(this).parent().next().hide();
            }
        })
    })

    $(document).on('click', '.add_dropdown_option', function() {
        let option_index = parseInt($(this).data('option_index'))
        $(this).parent().parent().parent().append(`
                            <div class="col-md-6 mb-3">
                                <div>
                                    <div class="row d-flex m-1 justify-content-between">
                                        <label for="option">Option</label>
                                        <button type="button" class="btn btn-danger btn-sm delete_dropdown_option">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </div>
                                    <input type="text" name="option[${option_index}][]" id="option" class="form-control" placeholder="Enter Option" required>
                                </div>
                            </div>`)
    })

    $(document).on('click', '.delete_dropdown_option', function() {
        $(this).parent().parent().parent().remove();
    })

    $('.add_field').click(function() {
        let option_index = parseInt($('.option_index').val())
        $('.option_index').val(option_index + 1)

        $('.fields_div').append(` <div class="col-md-12 m-0 p-0 row">
                                    <div class="col-md-12 mb-3">
                                        <div class="bg-transparent">
                                            <button type="button" class="btn btn-danger px-3 btn-sm delete_field">
                                                <i class="icon-trash mr-2"></i>Delete Field</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="title">Title</label>
                                        <input type="text" name="title[]" id="title" class="form-control" placeholder="Enter Title" required>
                                        @error('title')
                                        <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="description">Description</label>
                                        <input type="text" name="description[]" id="description" class="form-control" placeholder="Enter Description" required>
                                        @error('description')
                                        <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="input_type">Type</label>
                                        <select class="form-control input_type" name="input_type[]" id="input_type" required>
                                            @foreach ($input_types as $key => $input_type)
                                            <option value="{{ $key }}" @if ($result && $result->input_type == $key) selected="selected" @endif>
                                                {{ $input_type }}</option>
                                            @endforeach
                                        </select>
                                        @error('input_type')
                                        <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="dropdown_options col-md-12 mb-3 px-0 mx-0 row" style="display: none">
                                        <div class="col-md-12 mb-3">
                                            <div class="bg-transparent">
                                                <button type="button" data-option_index="${option_index}" class="btn btn-warning btn-sm add_dropdown_option">
                                                    <i class="icon-add"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="row d-flex m-1 py-1 justify-content-between"><label for="option">Option</label></div>
                                            <input type="text" name="option[${option_index}][]" id="option" class="form-control" placeholder="Enter Option">
                                        </div>
                                    </div>


                                </div>`)
    })

    $(document).on('click', '.delete_field', function() {
        $(this).parent().parent().parent().remove();
    })

</script>
@endpush
