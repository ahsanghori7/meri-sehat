@extends('layouts.admin.app')
@section('page_header')
{{ $page_header }}
@endsection
@php
$types = [
'nav-link' => 'Nav Link',
'nav-link-with-dropdown' => 'Nav Link With Dropdowwn',
'dropdown-header' => 'Dropdown Header',
'dropdown-link' => 'Dropdown Link',
];
$target = [
'_self' => 'Self',
'_blank' => 'Open in a new Window',
];
@endphp
@section('content')
<form method="post" class="needs-validations" id="disable_enter_submit" enctype="multipart/form-data" novalidate>
    @csrf
    <div class="container-fluid animatedParent animateOnce my-3">
        <div class="animated fadeInUpShort">
            @if (isset($include_translation_section) && $include_translation_section)
            <div class="card no-b  no-r">
                <div class="row card-body">
                    <div class="col-md-12">
                        <h5 class="box_heading">Language And Translation</h5>
                    </div>
                    @can('category-management-add-category-select_language')
                        @include('general_crud.languages_dropdown')
                        @include('general_crud.english_records_dropdown')
                    @endcan
                </div>
            </div>
            <br>
            @endif
            <div class="row">
                <div class="col-md-8 ">
                    <div class="box_border">
                        <div class="row">
                            <div class="col-md-12">
                            </div>
                            @can('category-management-add-category-category')
                            <div class="col-md-6 mb-3 category_div">
                                <label for="category">Category</label>
                                <select class="form-control category" name="category" id="category" @if($result && $result->lang_id != 1 )readonly @endif>
                                    <option value="">No category selected</option>
                                    @foreach ($categories as $key => $category)
                                    <option value="{{ $category->id }}" @if ($result && $result->parent && $result->parent->parent_id == 0 && $result->parent->id == $category->id) selected="selected"
                                        @elseif($result && $result->parent && $result->parent->parent_id != 0 && $result->parent->parent_id == $category->id)
                                        selected="selected" @endif>
                                        {{ $category->title }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            @endcan

                            @can('category-management-add-category-sub_category')
                            <div class="col-md-6 mb-3 parents_div">
                                <label for="parents">Sub-Category</label>
                                <select class="form-control parents" name="parent_id" id="parents"  @if($result && $result->lang_id != 1 ) readonly @endif>
                                    <option value="">No sub-category selected</option>
                                    @foreach ($sub_categories as $key => $sub_category)
                                    <option value="{{ $sub_category->id }}" @if ($result && $result->parent_id == $sub_category->id) selected="selected" @endif>
                                        {{ $sub_category->title }}</option>
                                    @endforeach
                                </select>
                                @error('target')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            @endcan

                            @can('category-management-add-category-title')
                            <div class="col-md-6 mb-3">
                                <label for="title">Title</label>
                                <input type="text" name="title" id="title" value="{{ $result ? $result->title : old('title') }}" class="form-control" placeholder="Enter Title" required>
                                @error('title')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            @endcan

                            @can('category-management-add-category-color_code')
                            <div class="col-md-6 mb-3">
                                <label for="color_code">Color Code</label>
                                <input type="color" name="color_code" id="color_code" value="{{ $result ? $result->color_code : old('color_code') }}" class="form-control" placeholder="Enter color_code" required>
                                @error('color_code')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            @endcan

                            @can('category-management-add-category-outer_text')
                            <div class="col-md-12 mb-3">
                                <label for="outer_text">Outer Text</label>
                                <textarea name="outer_text" id="outer_text" class="form-control" required placeholder="Outer Text">{{ $result ? $result->outer_text : old('outer_text') }}</textarea>
                                @error('outer_text')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            @endcan

                            @can('category-management-add-category-banner_text')
                            <div class="col-md-12 mb-3">
                                <label for="banner_text">Banner Text</label>
                                <textarea name="banner_text" id="banner_text" class="form-control" required placeholder="Banner Text">{{ $result ? $result->banner_text : old('banner_text') }}</textarea>
                                @error('banner_text')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            @endcan

                            @can('category-management-add-category-description')
                            <div class="col-md-12 mb-3">
                                <label for="banner_text">Description</label>
                                <textarea name="meta_description" id="meta_description" class="form-control" placeholder="Description">{{ $result ? $result->meta_description : old('meta_description') }}</textarea>
                                @error('meta_description')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            @endcan
{{--
                            <div class="col-md-6 mb-3 parent_div" style="display: none;">
                                <label for="parent">Parent</label>
                                <select class="form-control parents" name="parent" id="parent">
                                    <option value="">Select Parent</option>
                                </select>
                                @error('parent')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div> --}}

                            @can('category-management-add-category-enable_disable')
                                @include('general_crud.status_radio')
                            @endcan

                            @can('category-management-general-info-goto_live')
                                <div class="col-md-6 mb-3">
                                    <label for="goto_live">Goto Live</label>
                                    <input type="checkbox" name="goto_live" id="goto_live" value="{{ $result ? $result->goto_live : old('goto_live') }}" class="form-control">
                                    @error('goto_live')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                            @endcan


                        </div>

                    </div>

                    <div class="bg-transparent">
                        @can('category-management-add-category-save')
                        <button type="submit" class="cst_btn px-5 btn-sm">
                            {{-- <i class="icon-save"></i> --}}
                            {{ $result ? 'Update' : 'Save' }}
                        </button>
                        @endcan
                    </div>

                </div>

                <div class="col-md-4">
                    <div class="box_border">
                        @can('category-management-add-category-outer_image')
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="outer_image">Outer Image<span class="text-danger">*</span> (Dimensions : 400 X 400 px)</label>
                                <input type="file" data-max-file-size="2M" name="outer_image" id="outer_image" value="{{ isset($result->outer_image) ? env('ASSETS_STORAGE').$result->outer_image : null }}" data-default-file="{{ isset($result->outer_image) ? env('ASSETS_STORAGE').$result->outer_image : '' }}" class="dropify">
                                @error('outer_image')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endcan

                        @can('category-management-add-category-outer_home_image')
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="outer_home_image">Outer Home Image<span class="text-danger">*</span> (Dimensions : 400 X 400 px)</label>
                                <input type="file" data-max-file-size="2M" name="outer_home_image" id="outer_home_image" value="{{ isset($result->outer_home_image) ? env('ASSETS_STORAGE').$result->outer_home_image : null }}" data-default-file="{{ isset($result->outer_home_image) ? env('ASSETS_STORAGE').$result->outer_home_image : '' }}" class="dropify">
                                @error('outer_home_image')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endcan
                    </div>
                </div>

            </div>
        </div>
</form>
@endsection



@push('scripts')
<script>
    $(document).ready(function() {

        let type = "{{ $result->type ?? 'nav-link' }}"

        $('#category').change(function() {
            let value = $(this).val();
            getParents(value);
        })


        function getParents(type) {
            $('#loader').show();
            $.ajax({
                url: "{{ route('topics-get-parent-by-type') }}"
                , type: 'GET'
                , data: {
                    type
                    , '_token': '{{ csrf_token() }}'
                }
                , success: function(response) {
                    if (response.status) {
                        $("#loader").fadeOut();

                        $('.parents').html(response.html);

                    } else {
                        $("#loader").fadeOut();
                        swal({
                            title: response.message
                            , icon: "error"
                        })
                    }
                }
            , })
        }


        $('.lang_id').change(function() {
            var lang_id = $(this).val();
            if (lang_id == '1') {
                $('.category').attr('required', 'required');
                $('.category_div').slideDown();
                $('.parents').attr('required', 'required');
                $('.parents_div').slideDown();
            } else {
                $('.category').attr('required', '');
                $('.category_div').slideUp();
                $('.parents').attr('required', '');
                $('.parents_div').slideUp();
            }
        })



    })

</script>
@endpush
