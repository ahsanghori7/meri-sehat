@extends('layouts.admin.app')
@section('page_header')
{{ $page_header }}
@endsection
@push('css')
<style>
    .wrap {
        width: 100%;
        height: 100%;
        padding: 0;
        overflow: hidden;
    }

    .frame {
        width: 160%;
        height: 150%;
        border: 0;
        -ms-transform: scale(0.65);
        -moz-transform: scale(0.65);
        -o-transform: scale(0.65);
        -webkit-transform: scale(0.65);
        transform: scale(0.65);
        -ms-transform-origin: 0 0;
        -moz-transform-origin: 0 0;
        -o-transform-origin: 0 0;
        -webkit-transform-origin: 0 0;
        transform-origin: 0 0;
    }
    .input-number-group {
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-justify-content: center;
      -ms-flex-pack: center;
          justify-content: center;
}

.input-number-group input[type=number]::-webkit-inner-spin-button,
.input-number-group input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
          appearance: none;
}

.input-number-group .input-group-button {
  line-height: calc(80px/2 - 5px);
}

.input-number-group .input-number {
  width: 80px;
  padding: 0 12px;
  vertical-align: top;
  text-align: center;
  outline: none;
  display: block;
  margin: 0;
}

.input-number-group .input-number,
.input-number-group .input-number-decrement,
.input-number-group .input-number-increment {
  border: 1px solid #cacaca;
  height: 40px;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
  border-radius: 0;
}

.input-number-group .input-number-decrement,
.input-number-group .input-number-increment {
  display: inline-block;
  width: 40px;
  background: #e6e6e6;
  color: #0a0a0a;
  text-align: center;
  font-weight: bold;
  cursor: pointer;
  font-size: 2rem;
  font-weight: 400;
}

.input-number-group .input-number-decrement {
  margin-right: 0.3rem;
}

.input-number-group .input-number-increment {
  margin-left: 0.3rem;
}

</style>
@endpush
@section('content')
<form method="post" class="needs-validation" id="disable_enter_submit" enctype="multipart/form-data" novalidate>
    @csrf
    <div class="container-fluid animatedParent animateOnce my-3">
        <div class="animated fadeInUpShort">
            <div class="row">

                <div class="col-md-8 pr-0">
                    @if ($result)
                    <div class="wrap">
                        <iframe src="{{ $path . '?admin=true' }}" class="frame" id="iView" frameborder="0"></iframe>
                    </div>
                    @endif
                </div>

                <div class="col-md-4">

                    <div class="col-md-12 ">
                        <div class="box_border">
                            <div class="row">
                                <div class="col-md-10">
                                    <h5 class="box_heading">General Information</h5>
                                </div>

                                @if ($result)
                                <div class="col-md-2">
                                    <span>
                                        <a class="btn-primary btn-sm my-2 btn cursor-pointer" title="Open Live Preview" onclick="window.open('{{ $path }}')">
                                            <i class="icon-document-edit2"></i> </a>
                                    </span>
                                </div>
                                @endif

                                @can('article-management-add-article-select_language')
                                    @include('general_crud.languages_dropdown')
                                @endcan
                                @can('article-management-add-article-translation_of')
                                    @include('general_crud.english_records_dropdown')
                                @endcan

                                @can('article-management-add-article-name')
                                <div class="col-md-12 mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" value="{{ $result ? $result->name : old('name') }}" class="form-control" placeholder="Enter Name" required>
                                    @error('name')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan

{{--                                @if(auth()->user()->role_id == 1)--}}
                                @can('article-management-general-info-slug')
                                <div class="col-md-12 mb-3">
                                    <label for="slug">Slug <em>(leave blank slug will auto generate)</em></label>
                                    <input type="text" name="slug" id="slug" value="{{ $result ? $result->slug : old('slug') }}" class="form-control" placeholder="Enter Slug Name">
                                    @error('slug')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan
{{--                                @endif--}}

                                @can('article-management-general-info-speciality_tag')
                                <div class="col-md-12 mb-3 spectiality_tag">
                                    <label for="type">Speciality Tag</label>
                                    <select class="form-control" name="speciality_id" id="speciality_id">
                                        <option value="">No Tags</option>
                                        @foreach ($specialities as $speciality)
                                            <option value="{{ $speciality->id }}" {{ isset($result->speciality_id) && $speciality->id == $result->speciality_id ? 'selected' : '' }}>
                                                {{ $speciality->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('speciality')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan

                                @can('article-management-general-info-type')
                                <div class="col-md-12 mb-3">
                                    <label for="type">Type</label>
                                    <select class="form-control parent_type" name="type" id="type" required>
                                        @foreach ($types as $type)
                                        <option value="{{ $type['value'] }}" @if ($result) @if ($result->type == $type['value'])
                                            selected @endif
                                            @endif>
                                            {{ $type['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('name')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan

                                @can('article-management-general-info-category')
                                <div class="col-md-12 mb-3">
                                    <label for="category">Category</label>
                                    <select class="form-control category" name="category" id="category">
                                        <option value="">No category selected</option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @if ($result && $result->parent && $result->parent->parent_id == 0 && $result->parent->id == $category->id)
                                            selected="selected"
                                            @elseif($result && $result->parent && $result->parent->parent_id != 0 && $result->parent->parent_id == $category->id)
                                            selected="selected"
                                            @endif>{{ $category->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan
                                @can('article-management-general-info-sub_category')
                                <div class="col-md-12 mb-3">
                                    <label for="parents">Sub-Category</label>
                                    <select class="form-control parents" name="parent_id" id="parents">
                                        <option value="">No sub-category selected</option>
                                        @foreach ($sub_categories as $sub_category)
                                        <option value="{{ $sub_category->id }}" @if ($result && $result->parent_id == $sub_category->id) selected="selected" @endif>
                                            {{ $sub_category->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('target')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan
                                {{-- <div class="col-md-12 mb-3">
                                        <label for="parent">Category</label>
                                        <select class="form-control parents" name="parent" id="parent" required>
                                            @foreach ($parents as $parent)
                                                <option value="{{ $parent->id }}"
                                @if ($result) @if ($result->parent_id == $parent->id)selected @endif
                                @endif>
                                @if ($parent->parent)
                                {{ $parent->parent->title . ' > ' . $parent->title }}
                                @else
                                {{ $parent->title }}
                                @endif
                                </option>
                                @endforeach

                                </select>
                                @error('parent')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div> --}}

                            @can('article-management-general-info-keywords')
                            <div class="col-md-12 mb-3">
                                <label for="keywords">Keywords</label>
                                <input type="text" name="keywords" id="keywords" value="{{ $result ? $result->keywords : old('keywords') }}" class="form-control" placeholder="Enter Keywords" required>
                                @error('keywords')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            @endcan

                            @can('article-management-general-info-description')
                            <div class="col-md-12 mb-3">
                                <label for="descripton">Description</label>
                                <textarea name="descripton" id="descripton" class="form-control" placeholder="Description">{{ $result ? $result->descripton : old('descripton') }}</textarea>
                                @error('descripton')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3" style="display: flex; justify-content: end;">
                                <label for="is_description_show">
                                    <input class="form-control-input" name="is_description_show" id="is_description_show" type="checkbox" value="1" @if($result && $result->is_description_show == 1) checked @endif> Show Text on Banner
                                </label>
                            </div>
                            @endcan

                            @can('article-management-general-info-reviewed_by')
                            <div class="col-md-12 mb-3 approved_by_doctor">
                                <label for="approved_by">Reviewed By</label>
                                <select class="form-control" name="approved_by_doctor">
                                    <option value=""> Select Doctor </option>
                                    @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor['id'] }}" {{ isset($result->approved_by) && $result->approved_by == $doctor['id'] ? 'selected' : '' }} >
                                        {{ $doctor['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('approved_by')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
{{--                            @dd($result)--}}
{{--                            <div class="col-md-12 mb-3 approved_by_wellness">--}}
{{--                                <label for="approved_by">Reviewed By</label>--}}
{{--                                <select class="form-control" name="approved_by_wellness">--}}
{{--                                    <option value=""> Select Wellness </option>--}}
{{--                                    @foreach ($wellness_profiles as $wellness_profile)--}}
{{--                                        <option value="{{ $wellness_profile['id'] }}" @if ($result) @if ($result->approved_by == $wellness_profile['id'])--}}
{{--                                            selected @endif--}}
{{--                                            @endif>--}}
{{--                                            {{ $wellness_profile['name'] }}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                                @error('approved_by_wellness')--}}
{{--                                <div class="validation-error"> {{ $message }}</div>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
                            @endcan

                            @can('article-management-general-info-written_by')
                            <div class="col-md-12 mb-3">
                                <label for="written_by">Written By</label>
                                <select class="form-control" name="written_by">
                                    <option value=""> Select Author </option>
                                    </option>
                                    @foreach ($authors as $author)
                                    <option value="{{ $author['id'] }}" @if ($result) @if ($result->written_by == $author['id'])
                                        selected @endif
                                        @endif>
                                        {{ $author['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('written_by')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            @endcan

                            @can('article-management-general-info-image')
                            <div class="col-md-12 mb-3">
                                <label for="image">Image (Dimensions : 1920 x 1080px.) </label>
                                <input type="file" data-max-file-size="2M" name="image" id="image" value="{{ isset($result->image) ? env('ASSETS_STORAGE').$result->image : null }}" data-default-file="{{ isset($result->image) ?  env('ASSETS_STORAGE').$result->image : '' }}" class="dropify" crossorigin="anonymous">
                                @error('image')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            @endcan
                            @can('article-management-general-info-image_alt_text')
                            <div class="col-md-12 mb-2 px-4 alternative">
                                <label for="alt">Alt <em>(Alt information for an image.)</em></label>
                                <input type="text" id="alt" name="alt" class="form-control" value="{{ $result ? $result->alt : null}}" placeholder="Enter alternative information of image.">

                                @error('image')
                                    <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            @endcan
                            @can('article-management-general-info-image')
                            <div class="col-md-12 mb-3" style="display: flex; justify-content: end;">
                                <label for="hide_image_in_detail">
                                    <input class="form-control-input" name="hide_image_in_detail" id="hide_image_in_detail" type="checkbox" value="1" @if($result && $result->hide_image_in_detail == 1) checked @endif> Hide image in detail
                                </label>
                            </div>
                            @endcan
                            @can('article-management-general-info-tags')
                            <div class="col-md-12 mb-3">
                                <label for="tags">Tags</label>
                                <select class="token_tags form-control" multiple="multiple" name="tags[]" id="tags">
                                    @foreach ($tags as $tag)
                                    <option @if (in_array($tag->name, $selected_tags)) selected @endif>
                                        {{ $tag->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('tags')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            @endcan

                            @can('article-management-general-info-labels')
                            <div class="col-md-12 mb-3">
                                <label for="label">Labels</label>
                                <select class="form-control" name="label">
                                    <option value=""> Select Label </option>
                                    </option>
                                    @foreach ($labels as $label)
                                    <option value="{{ $label['id'] }}" @if ($result) @if ($result->articleLabel && $result->articleLabel->article_fact_id == $label['id'])
                                        selected @endif
                                        @endif>
                                        {{ $label['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('label')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            @endcan

                            @if ($result)
                            @can('article-management-widgets-add')
                            <div class="col-md-12 mb-3">
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">See/Add available widgets</button>
                            </div>
                            @endcan
                            @endif

                            @can('article-management-general-info-meta_name')
                            <div class="col-md-12 mb-3">
                                <label for="meta_name">Meta Name</label>
                                <input type="text" name="meta_name" id="meta_name" value="{{ $result ? $result->meta_name : old('meta_name') }}" class="form-control" placeholder="Enter Meta Name">
                                @error('meta_name')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            @endcan

                            @can('article-management-general-info-meta_description')
                            <div class="col-md-12 mb-3">
                                <label for="meta_description">Meta Description</label>
                                <textarea class="form-control" id="meta_description" name="meta_description" placeholder="Enter Meta Description">{{ $result ? $result->meta_description : old('meta_description') }}</textarea>
                                @error('meta_description')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            @endcan

                            @can('article-management-general-info-canonical_title')
                                <div class="col-md-12 mb-3">
                                    <label for="canonical_title">Canonical Title</label>
                                    <textarea class="form-control" maxlength="1000" id="canonical_title" name="canonical_title" placeholder="Enter canonical title">{{ $result ? $result->canonical_title : old('canonical_title') }}</textarea>
                                    @error('canonical_title')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                            @endcan

                            @can('article-management-general-info-canonical_description')
                                <div class="col-md-12 mb-3">
                                    <label for="canonical_description">Canonical Description</label>
                                    <textarea class="form-control" maxlength="1000" id="canonical_description" name="canonical_description" placeholder="Enter description title">{{ $result ? $result->canonical_description : old('canonical_description') }}</textarea>
                                    @error('canonical_description')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                            @endcan

                            @can('article-management-general-info-canonical_link')
                            <div class="col-md-12 mb-3">
                                <label for="canonical_link">Canonical Link</label>
                                <textarea class="form-control" maxlength="1000" id="canonical_link" name="canonical_link" placeholder="Enter canonical link">{{ $result ? $result->canonical_link : old('canonical_link') }}</textarea>
                                @error('canonical_link')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            @endcan

                            @can('article-management-general-info-seo_image')
                            <div class="col-md-12 mb-3">
                                <label for="seo_image">SEO Image Url</label>
                                <textarea class="form-control" id="seo_image" maxlength="1000" name="seo_image" placeholder="Enter seo image url">{{ $result ? $result->seo_image : old('seo_image') }}</textarea>
                                @error('seo_image')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            @endcan

                            @can('article-management-general-info-hidden_description')
                            <div class="col-md-12 mb-3">
                                <label for="hidden_description">Hidden Description</label>
                                <textarea class="form-control" maxlength="1000" id="hidden_description" name="hidden_description" placeholder="Enter hidden description">{{ $result ? $result->hidden_description : old('hidden_description') }}</textarea>
                                @error('hidden_description')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            @endcan

{{--                            @if(auth()->user()->role_id == (new App\Http\Common\Constant)->SUPER_ADMIN_ROLE_ID || auth()->user()->role_id == (new App\Http\Common\Constant)->ADMIN_ROLE_ID || auth()->user()->role_id == (new App\Http\Common\Constant)->EDITOR_ROLE_ID)--}}
                            @can('article-management-general-info-publish_unpublish')
                            <div class="col-md-12">
                                <div class="col-md-6 px-0 mb-2">
                                    <label for="status" class="d-block">Status</label>
                                    @error('capacity')<div class="validation-error"> {{ $message }}</div> @enderror
                                    <div class="custom__radio mb-3">
                                        <div class="d-flex box p-0 justify-content-between form-check">
                                            <div class="w-100">
                                                <input class="form-check-input" name="status" type="radio" {{isset($result->status) == 1 ? 'checked' : '' }} value="1" id="enable">
                                                <label class="form-check-label" for="enable">
                                                    Published
                                                </label>
                                            </div>
                                            <div class="w-100">
                                                <input class="form-check-input" type="radio" name="status" value="0" {{isset($result->status) == 0 ? 'checked' : '' }} id="disable">
                                                <label class="form-check-label" for="disable">
                                                    UnPublished
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endcan

                            @can('article-management-general-info-class_name')
                            <div class="col-md-12 mb-3">
                                <label for="class_name">Class Name</label>
                                <input type="text" name="class_name" id="class_name" value="{{ $result ? $result->class_name : old('class_name') }}" class="form-control" placeholder="Enter Class Name">
                                @error('class_name')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            @endcan

                            @if($result && ($result->status == 0 || $result->draft == 1))
                                @can('article-management-general-info-goto_live')
                                <div class="col-md-12 mb-3" style="display: flex;">
                                    <label for="goto_live">
                                        <input class="form-control-input" name="goto_live" id="goto_live" type="checkbox" value="1" @if($result && $result->goto_live == 1) checked @endif> Goto Live
                                    </label>
                                </div>
                                @endcan
                            @endif

                            </div>
                    </div>


                    @can('article-management-general-info-update')
                    <div class="bg-transparent">
                        <button type="submit" class="cst_btn px-5 btn-sm">
                            {{-- <i class="icon-save"></i> --}}
                            {{ $result ? 'Update' : 'Save' }}
                        </button>
                    </div>
                    @endcan

                </div>

            </div>



        </div>
    </div>

    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title t-bold">Available widgets</h5>
                </div>
                <div class="row">
                    @if (!$result)
                    @foreach ($widgets as $widget)
                    <div class="col-md-3">
                        <div class="gallery-card">
                            <div class="gallery-card-body">
                                <label class="block-check d-flex">
                                    <img crossorigin="anonymous" src="{{ env('ASSETS_STORAGE'). $widget->web_image }}" class="img-responsive" />
                                    <input type="checkbox" value="{{ $widget->id }}" name='widgets[]'>
                                    <span class="checkmark"></span>
                                </label>
                                <div class="mycard-footer text-center">
                                    <h5 class="card-link text-white">{{ $widget->name }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                    @if ($result)
                    @php $articleWidgets = $result->adminArticleWidgets->pluck('widget_id')->toArray(); @endphp
                    @foreach ($widgets as $widget)
                    {{-- @if (!in_array($widget->id, $articleWidgets)) --}}
                    <div class="col-md-3">
                        <div class="gallery-card">
                            <div class="gallery-card-body">
                                <label class="block-check d-flex">
                                    <img crossorigin="anonymous" src="{{ env('ASSETS_STORAGE'). $widget->web_image }}" class="img-responsive" />
                                    <input type="checkbox" value="{{ $widget->id }}" name='widgets[]'>
                                    <span class="checkmark"></span>
                                </label>
                                <div class="mycard-footer text-center">
                                    <h5 class="card-link text-white">{{ $widget->name }}</h5>
                                </div>
                                <div class="input-group input-number-group">
                                    <div class="input-group-button">
                                      <span class="input-number-decrement">-</span>
                                    </div>
                                    {{-- <input class="input-number" type="number" value="1" min="0" max="1000"> --}}
                                        <input type="number" min="0" max="10" oninput="this.value = Math.abs(this.value)" data-id="num-{{ $widget->id }}" class="form-control input-number" value="0" name="count_widgets[]" id="num-{{ $widget->id }}">
                                    <div class="input-group-button">
                                      <span class="input-number-increment">+</span>
                                    </div>
                                  </div>
                            </div>
                        </div>
                    </div>
                    {{-- @endif --}}
                    @endforeach
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" id="insertWidget" class="cst_btn px-5 btn-sm">
                        {{-- <i class="icon-save"></i> --}}
                        Add
                    </button>
                </div>
            </div>

        </div>
    </div>

</form>


@if ($result)

<div class="col-md-12 mt-3 px-0">
    <div class="box_border">

        <h5 class="box_heading mt-3">Selected widgets</h5>
        <form method="post" id="update_sequence" action="{{ route('article-update_sequence') }}">
            @csrf
            <table id="" class="table table-bordered table-hover" style="width:100%;">
                <thead>
                    <tr>
                        <th width=" 10px">Sequence</th>
                        <th>Widget</th>
                        <th width="150px">Status</th>
                        <th width="150px">Action</th>
                    </tr>
                </thead>
                <tbody class="sortable">
                    @php $articleWidgets = $result->adminArticleWidgets; @endphp
                    @foreach ($articleWidgets as $articleWidget)
                    <tr>
                        <td>
                            <input type="hidden" name="sequence[]" value="{{ $articleWidget->id }}">
                            {{ $articleWidget->sequence }}
                        </td>
                        <td>
                            <div class="gallery-card center-block" style="width: 25% !important">
                                <div class="gallery-card-body">
                                    <label class="d-flex">
                                        <img crossorigin="anonymous" src="{{ env('ASSETS_STORAGE'). $articleWidget->widget->web_image }}" class="img-responsive" />
                                    </label>
                                    <div class="mycard-footer text-center">
                                        <h5 class="card-link text-white">{{ $articleWidget->widget->name }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if ($articleWidget->status)
                            <span class="badge p-2 badge-success">Enable</span>
                            @else
                            <span class="badge p-2 badge-danger">Disable</span>
                            @endif
                        </td>
                        <td>
                            @can('article-management-widgets-update')
                            <button class="cst_btn-outline btn-sm cursor-pointer" type="button" onclick="open_module_modal_{{ $articleWidget->widget_id }}({{ $articleWidget->id }})">
                                <i class="icon-edit"></i> </button>
                            @endcan
                            @can('article-management-widgets-delete')
                            <a onclick="return confirm('Are you sure you want to delete?')" class="cst_btn-outline btn-sm cursor-pointer" href="{{route('article-delete-widget', ['reference_id' => $articleWidget->id]) }}">
                                <i class="icon-delete"></i>
                            </a>
                            @endcan
                            {{-- {{ route('admin.sponsor-edit', ['id' => $result->e_id]) }} --}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if(count($articleWidgets) > 0)
            <button type="submit" form="update_sequence" class="btn btn-sm mb-2 btn-outline-success">
                <i class="icon-refresh"></i>
                Update sequence
            </button>
            @endif
        </form>
    </div>
</div>
@endif
@endsection



@push('scripts')
<script>
    $(document).ready(function() {
        // $('.approved_by_doctor').hide();
        // $('.approved_by_wellness').hide();
        // if ($(".category option:selected" ).text() == 'Wellness') {
        //     $('.spectiality_tag').hide();
        //     $('.approved_by_doctor').hide();
        //     $('.approved_by_wellness').show();
        // } else {
        //     $('.spectiality_tag').show();
        //     $('.approved_by_doctor').show();
        //     $('.approved_by_wellness').hide();
        // }
        // $(document).on('change', '.category', function () {
        //     if ($(".category option:selected" ).text() == 'Wellness') {
        //         $('.spectiality_tag').hide();
        //         $('.approved_by_doctor').hide();
        //         $('.approved_by_wellness').show();
        //     } else {
        //         $('.spectiality_tag').show();
        //         $('.approved_by_doctor').show();
        //         $('.approved_by_wellness').hide();
        //     }
        // });

        
        $('.input-number-increment').click(function() {
            var $input = $(this).parents('.input-number-group').find('.input-number');
            var val = parseInt($input.val(), 10);
            if(val >= 0){
                var checkbox = $(this).parents('.gallery-card-body').find('input[type="checkbox"]').attr('checked', true);
            }
            $input.val(val + 1);
            });

            $('.input-number-decrement').click(function() {
            var $input = $(this).parents('.input-number-group').find('.input-number');
            var val = parseInt($input.val(), 10);
            if (val != 0) {
                val = val - 1;
                $input.val(val);
            }
            if(val == 0){
                var checkbox = $(this).parents('.gallery-card-body').find('input[type="checkbox"]').attr('checked', false);
            }
            });

        $('#insertWidget').click(function() {
            let selected_widgets = [];
            let selected_widgets_count = [];
            let reference_widget_id = $(this).data('reference_widget_id') ? $(this).data('reference_widget_id') : 0;
            let article_id = "{{$result->id ?? 0}}";
            console.log(reference_widget_id);
            $('input:checkbox[name="widgets[]"]:checked').each(function() {
                selected_widgets.push($(this).val());
                let id = $("#num-"+$(this).val()).val();
                    if(id == '' || undefined || null){
                        id = '1';
                    }
                selected_widgets_count.push(id);
            });

            var res = selected_widgets_count.reduce(function(result, field, index){
                result[selected_widgets[index]] = field;
                return result;
            }, {});
            res = JSON.stringify(res);

            $('#loader').show();
            $.ajax({
                url: "{{ route('article-addWidget') }}"
                , type: 'POST'
                , data: {
                    article_id
                    , reference_widget_id
                    , res
                    , '_token': '{{ csrf_token() }}'
                }
                , success: function(response) {
                    window.location.reload();
                }
            , })
        });

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


        $(".image-checkbox").each(function() {
            if ($(this).find('input[type="checkbox"]').first().attr("checked")) {
                $(this).addClass('image-checkbox-checked');
            } else {
                $(this).removeClass('image-checkbox-checked');
            }
        });

        // sync the state to the input
        $(".image-checkbox").on("click", function(e) {
            $(this).toggleClass('image-checkbox-checked');
            var $checkbox = $(this).find('input[type="checkbox"]');
            $checkbox.prop("checked", !$checkbox.prop("checked"))

            e.preventDefault();
        });



    })

</script>
@endpush
