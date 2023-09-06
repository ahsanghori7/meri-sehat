@extends('layouts.admin.app')
@php
    $types = [
        'nav-link' => 'Main Link',
        'nav-link-with-dropdown' => 'Dropdown',
        'dropdown-header' => 'Dropdown Heading',
        'dropdown-nav-link' => 'Dropdown Link',
    ];
    $target = [
        '_self' => 'Self',
        '_blank' => 'Open in a new Window',
    ];
    $lang_id = request()->route()->parameters['lang_id'];
    ($language = App\Models\Language::find(decrypt($lang_id)));
@endphp
@section('page_header')
    {{ $page_header . " (".$language->name.")"}}
@endsection
@section('content')
    <form method="post" class="needs-validation" id="disable_enter_submit" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="container-fluid animatedParent animateOnce my-3">
            <div class="animated fadeInUpShort">
                <div class="row">
                    <div class="col-md-8 ">
                        <div class="box_border">
                            <div class="row">
                                <div class="col-md-12">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name"
                                        value="{{ $result ? $result->name : old('name') }}" class="form-control"
                                        placeholder="Enter Name" required>
                                    @error('name')
                                        <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="type">Type</label>
                                    <select class="form-control type" name="type" id="type" required>
                                        @foreach ($types as $key => $value)
                                            <option value="{{ $key }}"
                                                    @if ($result && $result->type == $key) selected="selected" @endif>
                                                {{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3 link_div">
                                    <label for="link">Link</label>
                                    <input type="text" name="link" id="link"
                                        value="{{ $result ? $result->link : old('link') }}" class="form-control link"
                                        placeholder="Enter Link" required>
                                    @error('link')
                                        <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3 col_width_div" style="display: block;">
                                    <label for="col_width">Column Width</label>
                                    <input type="number" max="4" min="1" name="col_width" id="col_width"
                                           value="{{ $result ? $result->col_width : old('col_width') }}"
                                           class="form-control col_width" placeholder="Enter Column Width (Max:4)">
                                    @error('col_width')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3 parent_div" style="display: block;">
                                    <label for="parent">Parent</label>
                                    <select class="form-control parents" name="parent" id="parent">
                                        <option value="">Select Parent</option>
                                    </select>
                                    @error('parent')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3 mb-3" >
                                    <label for="parent">Link Target</label>
                                    <select class="form-control type" name="target" id="type" required>
                                        @foreach ($target as $key => $value)
                                            <option value="{{ $key }}"
                                                    @if ($result && $result->target == $key) selected="selected" @endif>
                                                {{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('target')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-2 px-4">
                                    <label for="image"> Image </label>
                                    <input type="file" data-max-file-size="2M" name="image" id="image"
                                           value="{{ $result ? env('ASSETS_STORAGE').$result->image : old('image') }}"
                                           data-default-file="{{ isset($result->image) ? env('ASSETS_STORAGE').$result->image : '' }}"
                                           class="dropify">
                                    @error('image')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>

                                @include('general_crud.status_radio')

                            </div>
                        </div>






                        <div class="bg-transparent">
                            <button type="submit" class="cst_btn px-5 btn-sm">
                                {{-- <i class="icon-save"></i> --}}
                                {{ $result ? 'Update' : 'Save' }}
                            </button>
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
            let type = "{{$result->type ?? 'nav-link'}}"
            ShowHideFieldsByType(type);
            $('.type').change(function() {
                let type = $(this).val();
                ShowHideFieldsByType(type);
            })

            function ShowHideFieldsByType(type){
                if (type == 'nav-link') {
                    showLink();
                    // showButtonText();
                    hideParent();
                    hideColumnWidth();
                } else if (type == 'nav-link-with-dropdown') {
                    showLink();
                    // showButtonText();
                    getParents(type);
                    showParent();
                    showColumnWidth();
                } else if (type == 'dropdown-header') {
                    showLink();
                    // showButtonText();
                    getParents(type);
                    showParent();
                    showColumnWidth();
                    $('.parent_div').find("label[for=parent]").text('Dropdown');
                } else if (type == 'dropdown-nav-link') {
                    showLink();
                    // showButtonText();
                    getParents(type);
                    showParent();
                    showColumnWidth();
                    $('.parent_div').find("label[for=parent]").text('Dropdown Heading');
                }

            }

            function showLink() {
                $('.link_div').slideDown();
                $('.link').attr('required', 'required');
            }

            function showButtonText() {
                $('.button_text_div').slideDown();
                $('.button_text').attr('required', 'required');
            }

            function hideLink() {
                $('.link_div').slideUp();
                $('.link').removeAttr('required');
            }

            function hideButtonText() {
                $('.button_text_div').slideUp();
                $('.button_text').removeAttr('required');
            }

            function hideParent() {
                $('.parent_div').slideUp();
            }

            function showParent() {
                $('.parent_div').slideDown();
            }


            function hideColumnWidth() {
                $('.col_width_div').slideUp();
            }

            function showColumnWidth() {
                $('.col_width_div').slideDown();
            }

            function getLangId(){
                return "{{decrypt($lang_id)}}";
            }

            function getParents(type) {
                $('#loader').show();
                lang_id = getLangId();
                menu_id = {{$result->id}};
                $.ajax({
                    url: "{{ route('footer-get-parent-by-type') }}",
                    type: 'GET',
                    data: {
                        type,
                        lang_id,
                        menu_id,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status) {
                            $("#loader").fadeOut();
                            $('.parents').html(response.html);
                            {{--alert({{$result->parent_id}});--}}
                            $('.parents option[value="{{isset($result->parent_id) ?? ''}}"]').attr("selected", "selected");

                        } else {
                            $("#loader").fadeOut();
                            swal({
                                title: response.message,
                                icon: "error"
                            })
                        }
                    },
                })
            }


        })
    </script>
@endpush
