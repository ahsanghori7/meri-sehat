@extends('layouts.widget.app')
@section('page_header')
    All {{ Str::plural($module_name) }}
@endsection
@php
    $image_positions = [
        [
            "type" => "left",
            "name" => "Left",
        ],
        [
            "type" => "right",
            "name" => "Right",
        ]
    ];
@endphp
@section('content')
    <form method="post" class="needs-validation" id="disable_enter_submit" enctype="multipart/form-data">
        @csrf
        <div class="container-fluid animatedParent animateOnce my-3">
            <div class="animated fadeInUpShort">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="box_border">
                            <div class="row">
                                @if (isset($reference))
                                    @php
                                        if($reference){
                                            $getReferenceWidget = \App\Models\ReferenceWidget::find($reference->id);
                                            $selectedLanguage = $getReferenceWidget->page ? $getReferenceWidget->page->lang_id : $getReferenceWidget->article->lang_id;
                                        }else {
                                            $selectedLanguage = 1;
                                        }
                                    @endphp

                                    @can($module_slug.'-widgets-call-by-reference-card-custom-heading')
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

                                    @can($module_slug.'-widgets-call-by-reference-card-custom-description')
                                        <div class="col-md-12 px-0 mb-3">
                                            <label for="reference_description">Description</label>
                                            <textarea name="reference_description" id="reference_description" class="form-control summernote"
                                                      placeholder="Description">{{ $reference ? $reference->description : old('reference_description') }}</textarea>
                                            @error('reference_description')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endcan

                                    @can($module_slug.'-widgets-call-by-reference-card-custom-redirect_url')
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

                                    @can($module_slug.'-widgets-call-by-reference-card-custom-ad_window')
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
                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_type')
                                <div class="col-md-12 mb-3 px-0">
                                    <label for="type">Card Type</label>
                                    <select class="form-control type" name="card_type" id="card_type" required >
                                        @foreach ($card_types as $card_type)
                                            <option value="{{ $card_type['type'] }}"
                                                    @if (count($result) && $result[0]->card_type == $card_type['type']) selected @endif> {{ $card_type['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('card_type')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan
                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-home_type')
                                    <div class="col-md-12 mb-3 px-0">
                                        <label for="type">Home Type</label>
                                        <select class="form-control type" name="home_type" id="home_type" required >
                                            @foreach ($home_types as $home_type)
                                                <option value="{{ $home_type['type'] }}"
                                                        @if (count($result) && $result[0]->home_type == $home_type['type']) selected @endif> {{ $home_type['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('card_type')
                                        <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                @endcan
                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-type')
                                <div class="col-md-12 mb-3 px-0">
{{--                                    @dd($result[0]->type)--}}
                                    <label for="type">Type</label>
                                    <select class="form-control type" name="type" id="type" required @if (count($result))
                                        disabled
                                        @endif>
                                        <option value=""> Select Type </option>
                                        @foreach ($types as $type)
                                            <option value="{{ $type['type'] }}"
                                                    @if (count($result) && $result[0]->type == $type['type']) selected @endif> {{ $type['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan
                                @if (count($result) > 0)
                                    @foreach($result as $data)
                                        <div class="col-md-12 mb-3 px-0 clone-div">
                                            @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_heading')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="heading">Card Heading</label>
                                                <input type="text" id="heading" name="heading[]" class="form-control" value="{{$data->heading ?? ''}}" placeholder="Heading">
                                                @error('heading')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                            @endcan
                                            @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_sub_head')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="sub_head">Card Sub-Heading</label>
                                                <input type="text" id="sub_head" name="sub_head[]" class="form-control" value="{{$data->sub_head ?? ''}}" placeholder="Sub Heading">
                                                @error('sub_head')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                            @endcan
                                            @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_description')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="description">Card Description</label>
                                                <input type="text" id="description" name="description[]" class="form-control" value="{{$data->description ?? '' }}" placeholder="Description">
                                                @error('description')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                            @endcan
                                            @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_image_redirect_url')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="redirect_url">Image Redirect URL</label>
                                                <input type="text" id="redirect_url" name="redirect_url[]" class="form-control" value="{{$data->redirect_url ?? ''}}" placeholder="Heading">
                                                @error('redirect_url')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                            @endcan
                                            @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_redirect_button_text')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="button_text">Redirect Button Text</label>
                                                <input type="text" id="button_text" name="button_text[]" class="form-control" value="{{$data->button_text ?? ''}}" placeholder="Heading">
                                                @error('button_text')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                            @endcan
                                            @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_image')
                                            <div class="col-md-12 mb-2 px-4">
                                                <label for="image">Image</label>
                                                <input type="file" data-max-file-size="2M" name="image[]" id="image"
                                                       value="{{ env('ASSETS_STORAGE').$data->image ?? null }}"
                                                       data-default-file="{{ $data->image ? env('ASSETS_STORAGE'). $data->image : '' }}"
                                                       class="dropify">
                                                @error('image')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                            @endcan
                                            <div class="with_cards">
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_head')
                                                <div class="col-md-12 mb-3 px-0">
                                                    <label for="card_1_head">Card Heading 1</label>
                                                    <input type="text" id="card_1_head" name="card_1_head" class="form-control" value="{{$data->card_1_head ?? ''}}" placeholder="Card Heading 1">
                                                    @error('card_1_head')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_desc')
                                                <div class="col-md-12 mb-3 px-0">
                                                    <label for="card_1_desc">Card Description 1</label>
                                                    <input type="text" id="card_1_desc" name="card_1_desc" class="form-control" value="{{$data->card_1_desc ?? ''}}" placeholder="Card Description 1">
                                                    @error('card_1_desc')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_redirect_url')
                                                <div class="col-md-12 mb-3 px-0">
                                                    <label for="card_1_link">Card Redirect URL 1</label>
                                                    <input type="text" id="card_1_link" name="card_1_link" class="form-control" value="{{$data->card_1_link ?? ''}}" placeholder="Card Link 1">
                                                    @error('card_1_link')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_color')
                                                    <div class="col-md-12 mb-3 px-0">
                                                        <label for="card_1_color">Card Color 1</label>
                                                        <input type="color" id="card_1_color" name="card_1_color" class="form-control" value="{{$data->card_1_color ?? ''}}" placeholder="Card Color 1">
                                                        @error('card_1_color')
                                                        <div class="validation-error"> {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_inner_color')
                                                    <div class="col-md-12 mb-3 px-0">
                                                        <label for="card_1_inner_color">Card Inner Color 1</label>
                                                        <input type="color" id="card_1_inner_color" name="card_1_inner_color" class="form-control" value="{{$data->card_1_inner_color ?? ''}}" placeholder="Card Inner Color 1">
                                                        @error('card_1_inner_color')
                                                        <div class="validation-error"> {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_icon')
                                                <div class="col-md-12 mb-2 px-4">
                                                    <label for="card_1_icon">Card Icon Image 1</label>
                                                    <input type="file" data-max-file-size="2M" name="card_1_icon" id="card_1_icon"
                                                           value="{{ $data->card_1_icon ?? null }}"
                                                           data-default-file="{{ $data->card_1_icon ? env('ASSETS_STORAGE'). $data->card_1_icon : '' }}"
                                                           class="dropify">
                                                    @error('card_1_icon')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_head')
                                                <div class="col-md-12 mb-3 px-0">
                                                    <label for="card_2_head">Card Heading 2</label>
                                                    <input type="text" id="card_2_head" name="card_2_head" class="form-control" value="{{$data->card_2_head ?? ''}}" placeholder="Card Heading 2">
                                                    @error('card_2_head')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_desc')
                                                <div class="col-md-12 mb-3 px-0">
                                                    <label for="card_2_desc">Card Description 2</label>
                                                    <input type="text" id="card_2_desc" name="card_2_desc" class="form-control" value="{{$data->card_2_desc ?? ''}}" placeholder="Card Description 2">
                                                    @error('card_2_desc')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_redirect_url')
                                                <div class="col-md-12 mb-3 px-0">
                                                    <label for="card_2_link">Card Redirect URL 2</label>
                                                    <input type="text" id="card_2_link" name="card_2_link" class="form-control" value="{{$data->card_2_link ?? ''}}" placeholder="Card Link 2">
                                                    @error('card_2_link')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_color')
                                                    <div class="col-md-12 mb-3 px-0">
                                                        <label for="card_2_color">Card Color 2</label>
                                                        <input type="color" id="card_2_color" name="card_2_color" class="form-control" value="{{$data->card_2_color ?? ''}}" placeholder="Card Color 2">
                                                        @error('card_2_color')
                                                        <div class="validation-error"> {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_inner_color')
                                                    <div class="col-md-12 mb-3 px-0">
                                                        <label for="card_2_inner_color">Card Inner Color 2</label>
                                                        <input type="color" id="card_2_inner_color" name="card_2_inner_color" class="form-control" value="{{$data->card_2_inner_color ?? ''}}" placeholder="Card Inner Color 2">
                                                        @error('card_2_inner_color')
                                                        <div class="validation-error"> {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_icon')
                                                <div class="col-md-12 mb-2 px-4">
                                                    <label for="card_2_icon">Card Icon Image 2</label>
                                                    <input type="file" data-max-file-size="2M" name="card_2_icon" id="card_1_icon"
                                                           value="{{ $data->card_2_icon ?? null }}"
                                                           data-default-file="{{ $data->card_2_icon ? env('ASSETS_STORAGE').$data->card_2_icon : '' }}"
                                                           class="dropify">
                                                    @error('card_2_icon')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_head')
                                                <div class="col-md-12 mb-3 px-0">
                                                    <label for="card_3_head">Card Heading 3</label>
                                                    <input type="text" id="card_3_head" name="card_3_head" class="form-control" value="{{$data->card_3_head ?? ''}}" placeholder="Card Heading 3">
                                                    @error('card_3_head')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_desc')
                                                <div class="col-md-12 mb-3 px-0">
                                                    <label for="card_3_desc">Card Description 3</label>
                                                    <input type="text" id="card_3_desc" name="card_3_desc" class="form-control" value="{{$data->card_3_desc ?? ''}}" placeholder="Card Description 3">
                                                    @error('card_3_desc')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_redirect_url')
                                                <div class="col-md-12 mb-3 px-0">
                                                    <label for="card_3_link">Card Redirect URL 3</label>
                                                    <input type="text" id="card_3_link" name="card_3_link" class="form-control" value="{{$data->card_3_link ?? ''}}" placeholder="Card Link 3">
                                                    @error('card_3_link')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_color')
                                                    <div class="col-md-12 mb-3 px-0">
                                                        <label for="card_3_color">Card Color 3</label>
                                                        <input type="color" id="card_3_color" name="card_3_color" class="form-control" value="{{$data->card_3_color ?? ''}}" placeholder="Card Color 3">
                                                        @error('card_3_color')
                                                        <div class="validation-error"> {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_inner_color')
                                                    <div class="col-md-12 mb-3 px-0">
                                                        <label for="card_3_inner_color">Card Inner Color 3</label>
                                                        <input type="color" id="card_3_inner_color" name="card_3_inner_color" class="form-control" value="{{$data->card_3_inner_color ?? ''}}" placeholder="Card Inner Color 3">
                                                        @error('card_3_inner_color')
                                                        <div class="validation-error"> {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_icon')
                                                <div class="col-md-12 mb-2 px-4">
                                                    <label for="card_3_icon">Card Icon Image 3</label>
                                                    <input type="file" data-max-file-size="2M" name="card_3_icon" id="card_1_icon"
                                                           value="{{ $data->card_3_icon ?? null }}"
                                                           data-default-file="{{ $data->card_3_icon ? env('ASSETS_STORAGE'). $data->card_3_icon : '' }}"
                                                           class="dropify">
                                                    @error('card_3_icon')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @endcan

                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_head')
                                                <div class="col-md-12 mb-3 px-0">
                                                    <label for="card_4_head">Card Heading 4</label>
                                                    <input type="text" id="card_4_head" name="card_4_head" class="form-control" value="{{$data->card_4_head ?? ''}}" placeholder="Card Heading 4">
                                                    @error('card_4_head')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_desc')
                                                    <div class="col-md-12 mb-3 px-0">
                                                    <label for="card_4_desc">Card Description 4</label>
                                                    <input type="text" id="card_4_desc" name="card_4_desc" class="form-control" value="{{$data->card_4_desc ?? ''}}" placeholder="Card Description 4">
                                                    @error('card_4_desc')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_redirect_url')
                                                <div class="col-md-12 mb-3 px-0">
                                                    <label for="card_4_link">Card Redirect URL 4</label>
                                                    <input type="text" id="card_4_link" name="card_4_link" class="form-control" value="{{$data->card_4_link ?? ''}}" placeholder="Card Link 4">
                                                    @error('card_4_link')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_color')
                                                    <div class="col-md-12 mb-3 px-0">
                                                        <label for="card_4_color">Card Color 4</label>
                                                        <input type="color" id="card_4_color" name="card_4_color" class="form-control" value="{{$data->card_4_color ?? ''}}" placeholder="Card Color 4">
                                                        @error('card_4_color')
                                                        <div class="validation-error"> {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_inner_color')
                                                    <div class="col-md-12 mb-3 px-0">
                                                        <label for="card_4_inner_color">Card Inner Color 4</label>
                                                        <input type="color" id="card_4_inner_color" name="card_4_inner_color" class="form-control" value="{{$data->card_4_inner_color ?? ''}}" placeholder="Card Inner Color 4">
                                                        @error('card_4_inner_color')
                                                        <div class="validation-error"> {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_icon')
                                                <div class="col-md-12 mb-2 px-4">
                                                    <label for="card_4_icon">Card Icon Image 4</label>
                                                    <input type="file" data-max-file-size="2M" name="card_4_icon" id="card_4_icon"
                                                           value="{{ $data->card_4_icon ?? null }}"
                                                           data-default-file="{{ $data->card_4_icon ? env('ASSETS_STORAGE'). $data->card_4_icon : '' }}"
                                                           class="dropify">
                                                    @error('card_4_icon')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @endcan

                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_head')
                                                    <div class="col-md-12 mb-3 px-0">
                                                        <label for="card_5_head">Card Heading 5</label>
                                                        <input type="text" id="card_5_head" name="card_5_head" class="form-control" value="{{$data->card_5_head ?? ''}}" placeholder="Card Heading 5">
                                                        @error('card_5_head')
                                                        <div class="validation-error"> {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_desc')
                                                    <div class="col-md-12 mb-3 px-0">
                                                        <label for="card_5_desc">Card Description 5</label>
                                                        <input type="text" id="card_5_desc" name="card_5_desc" class="form-control" value="{{$data->card_5_desc ?? ''}}" placeholder="Card Description 5">
                                                        @error('card_5_desc')
                                                        <div class="validation-error"> {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_redirect_url')
                                                    <div class="col-md-12 mb-3 px-0">
                                                        <label for="card_5_link">Card Redirect URL 5</label>
                                                        <input type="text" id="card_5_link" name="card_5_link" class="form-control" value="{{$data->card_5_link ?? ''}}" placeholder="Card Link 5">
                                                        @error('card_5_link')
                                                        <div class="validation-error"> {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_icon')
                                                    <div class="col-md-12 mb-2 px-4">
                                                        <label for="card_5_icon">Card Icon Image 5</label>
                                                        <input type="file" data-max-file-size="2M" name="card_5_icon" id="card_5_icon"
                                                               value="{{ $data->card_5_icon ?? null }}"
                                                               data-default-file="{{ $data->card_5_icon ? env('ASSETS_STORAGE'). $data->card_5_icon : '' }}"
                                                               class="dropify">
                                                        @error('card_5_icon')
                                                        <div class="validation-error"> {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_color')
                                                    <div class="col-md-12 mb-3 px-0">
                                                        <label for="card_5_color">Card Color 5</label>
                                                        <input type="color" id="card_5_color" name="card_5_color" class="form-control" value="{{$data->card_5_color ?? ''}}" placeholder="Card Color 5">
                                                        @error('card_5_color')
                                                        <div class="validation-error"> {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_inner_color')
                                                    <div class="col-md-12 mb-3 px-0">
                                                        <label for="card_5_inner_color">Card Inner Color 5</label>
                                                        <input type="color" id="card_5_inner_color" name="card_5_inner_color" class="form-control" value="{{$data->card_5_inner_color ?? ''}}" placeholder="Card Inner Color 5">
                                                        @error('card_5_inner_color')
                                                        <div class="validation-error"> {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endcan

                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_head')
                                                    <div class="col-md-12 mb-3 px-0">
                                                        <label for="card_6_head">Card Heading 6</label>
                                                        <input type="text" id="card_6_head" name="card_6_head" class="form-control" value="{{$data->card_6_head ?? ''}}" placeholder="Card Heading 6">
                                                        @error('card_6_head')
                                                        <div class="validation-error"> {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_desc')
                                                    <div class="col-md-12 mb-3 px-0">
                                                        <label for="card_6_desc">Card Description 6</label>
                                                        <input type="text" id="card_6_desc" name="card_6_desc" class="form-control" value="{{$data->card_6_desc ?? ''}}" placeholder="Card Description 6">
                                                        @error('card_6_desc')
                                                        <div class="validation-error"> {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_redirect_url')
                                                    <div class="col-md-12 mb-3 px-0">
                                                        <label for="card_6_link">Card Redirect URL 6</label>
                                                        <input type="text" id="card_6_link" name="card_6_link" class="form-control" value="{{$data->card_6_link ?? ''}}" placeholder="Card Link 6">
                                                        @error('card_6_link')
                                                        <div class="validation-error"> {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_icon')
                                                    <div class="col-md-12 mb-2 px-4">
                                                        <label for="card_6_icon">Card Icon Image 6</label>
                                                        <input type="file" data-max-file-size="2M" name="card_6_icon" id="card_6_icon"
                                                               value="{{ $data->card_6_icon ?? null }}"
                                                               data-default-file="{{ $data->card_6_icon ? env('ASSETS_STORAGE'). $data->card_6_icon : '' }}"
                                                               class="dropify">
                                                        @error('card_6_icon')
                                                        <div class="validation-error"> {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_color')
                                                    <div class="col-md-12 mb-3 px-0">
                                                        <label for="card_6_color">Card Color 6</label>
                                                        <input type="color" id="card_6_color" name="card_6_color" class="form-control" value="{{$data->card_6_color ?? ''}}" placeholder="Card Color 6">
                                                        @error('card_6_color')
                                                        <div class="validation-error"> {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endcan
                                                @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_inner_color')
                                                    <div class="col-md-12 mb-3 px-0">
                                                        <label for="card_6_inner_color">Card Inner Color 6</label>
                                                        <input type="color" id="card_6_inner_color" name="card_6_inner_color" class="form-control" value="{{$data->card_6_inner_color ?? ''}}" placeholder="Card Inner Color 6">
                                                        @error('card_6_inner_color')
                                                        <div class="validation-error"> {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endcan

                                            </div>

                                        </div>
                                   @endforeach
                                @else
                                <div class="col-md-12 mb-3 px-0 clone-div">
                                    @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_heading')
                                    <div class="col-md-12 mb-3 px-0">
                                        <label for="heading">Card Heading</label>
                                        <input type="text" id="heading" name="heading[]" class="form-control" value="{{count($result) ? ($result[0]->heading ? :"") :''}}" placeholder="Heading">
                                        @error('heading')
                                            <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endcan
                                    @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_sub_head')
                                    <div class="col-md-12 mb-3 px-0">
                                        <label for="sub_head">Card Sub-Heading</label>
                                        <input type="text" id="sub_head" name="sub_head[]" class="form-control" value="{{count($result) ? ($result[0]->sub_head ? :"") :''}}" placeholder="Sub Heading">
                                        @error('sub_head')
                                        <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endcan
                                    @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_description')
                                    <div class="col-md-12 mb-3 px-0">
                                        <label for="description">Card Description</label>
                                        <input type="text" id="description" name="description[]" class="form-control" value="{{count($result) ? ($result[0]->description ? :"") :''}}" placeholder="Description">
                                        @error('description')
                                            <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endcan
                                    @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_redirect_url')
                                    <div class="col-md-12 mb-3 px-0">
                                        <label for="redirect_url">Image Redirect URL</label>
                                        <input type="text" id="redirect_url" name="redirect_url[]" class="form-control" value="{{count($result) ? ($result[0]->redirect_url ? :"") :''}}" placeholder="Heading">
                                        @error('redirect_url')
                                            <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endcan
                                    @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_redirect_button_text')
                                    <div class="col-md-12 mb-3 px-0">
                                        <label for="button_text">Redirect Button Text</label>
                                        <input type="text" id="button_text" name="button_text[]" class="form-control" value="{{count($result) ? ($result[0]->button_text ? :"") :''}}" placeholder="Heading">
                                        @error('button_text')
                                            <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endcan
                                    @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_image')
                                    <div class="col-md-12 mb-2 px-4">
                                        <label for="image">Image</label>
                                        <input type="file" data-max-file-size="2M" name="image[]" id="image"
                                            value="{{ count($result) ? env('ASSETS_STORAGE').$result[0]->image : null }}"
                                            data-default-file="{{ count($result) ? env('ASSETS_STORAGE'). $result[0]->image : '' }}"
                                            class="dropify">
                                        @error('image')
                                            <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endcan

                                    <div class="with_cards">
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_head')
                                        <div class="col-md-12 mb-3 px-0">
                                            <label for="card_1_head">Card Heading 1</label>
                                            <input type="text" id="card_1_head" name="card_1_head" class="form-control" value="{{$data->card_1_head ?? ''}}" placeholder="Card Heading 1">
                                            @error('card_1_head')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_desc')
                                        <div class="col-md-12 mb-3 px-0">
                                            <label for="card_1_desc">Card Description 1</label>
                                            <input type="text" id="card_1_desc" name="card_1_desc" class="form-control" value="{{$data->card_1_desc ?? ''}}" placeholder="Card Description 1">
                                            @error('card_1_desc')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_redirect_url')
                                        <div class="col-md-12 mb-3 px-0">
                                            <label for="card_1_link">Card Redirect URL 1</label>
                                            <input type="text" id="card_1_link" name="card_1_link" class="form-control" value="{{$data->card_1_link ?? ''}}" placeholder="Card Link 1">
                                            @error('card_1_link')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endcan

                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_color')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="card_4_color">Card Color 1</label>
                                                <input type="color" id="card_1_color" name="card_1_color" class="form-control" value="{{$data->card_1_color ?? ''}}" placeholder="Card Color 1">
                                                @error('card_1_color')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_inner_color')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="card_1_inner_color">Card Inner Color 1</label>
                                                <input type="color" id="card_1_inner_color" name="card_1_inner_color" class="form-control" value="{{$data->card_1_inner_color ?? ''}}" placeholder="Card Inner Color 1">
                                                @error('card_1_inner_color')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endcan

                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_icon')
                                        <div class="col-md-12 mb-2 px-4">
                                            <label for="card_1_icon">Card Icon Image 1</label>
                                            <input type="file" data-max-file-size="2M" name="card_1_icon" id="card_1_icon"
                                                   value="{{ $data->card_1_icon ?? null }}"
                                                   class="dropify">
                                            @error('card_1_icon')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_head')
                                        <div class="col-md-12 mb-3 px-0">
                                            <label for="card_2_head">Card Heading 2</label>
                                            <input type="text" id="card_2_head" name="card_2_head" class="form-control" value="{{$data->card_2_head ?? ''}}" placeholder="Card Heading 2">
                                            @error('card_2_head')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_desc')
                                        <div class="col-md-12 mb-3 px-0">
                                            <label for="card_2_desc">Card Description 2</label>
                                            <input type="text" id="card_2_desc" name="card_2_desc" class="form-control" value="{{$data->card_2_desc ?? ''}}" placeholder="Card Description 2">
                                            @error('card_2_desc')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_redirect_url')
                                        <div class="col-md-12 mb-3 px-0">
                                            <label for="card_2_link">Card Redirect URL 2</label>
                                            <input type="text" id="card_2_link" name="card_2_link" class="form-control" value="{{$data->card_2_link ?? ''}}" placeholder="Card Link 2">
                                            @error('card_2_link')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_color')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="card_2_color">Card Color 2</label>
                                                <input type="color" id="card_2_color" name="card_2_color" class="form-control" value="{{$data->card_2_color ?? ''}}" placeholder="Card Color 2">
                                                @error('card_2_color')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_inner_color')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="card_2_inner_color">Card Inner Color 2</label>
                                                <input type="color" id="card_2_inner_color" name="card_2_inner_color" class="form-control" value="{{$data->card_2_inner_color ?? ''}}" placeholder="Card Inner Color 2">
                                                @error('card_2_inner_color')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_icon')
                                        <div class="col-md-12 mb-2 px-4">
                                            <label for="card_2_icon">Card Icon Image 2</label>
                                            <input type="file" data-max-file-size="2M" name="card_2_icon" id="card_1_icon"
                                                   value="{{ $data->card_2_icon ?? null }}"
                                                   class="dropify">
                                            @error('card_2_icon')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_head')
                                        <div class="col-md-12 mb-3 px-0">
                                            <label for="card_3_head">Card Heading 3</label>
                                            <input type="text" id="card_3_head" name="card_3_head" class="form-control" value="{{$data->card_3_head ?? ''}}" placeholder="Card Heading 3">
                                            @error('card_3_head')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_desc')
                                        <div class="col-md-12 mb-3 px-0">
                                            <label for="card_3_desc">Card Description 3</label>
                                            <input type="text" id="card_3_desc" name="card_3_desc" class="form-control" value="{{$data->card_3_desc ?? ''}}" placeholder="Card Description 3">
                                            @error('card_3_desc')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_redirect_url')
                                        <div class="col-md-12 mb-3 px-0">
                                            <label for="card_3_link">Card Redirect URL 3</label>
                                            <input type="text" id="card_3_link" name="card_3_link" class="form-control" value="{{$data->card_3_link ?? ''}}" placeholder="Card Link 3">
                                            @error('card_3_link')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_color')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="card_3_color">Card Color 3</label>
                                                <input type="color" id="card_3_color" name="card_3_color" class="form-control" value="{{$data->card_3_color ?? ''}}" placeholder="Card Color 3">
                                                @error('card_3_color')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_inner_color')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="card_3_inner_color">Card Inner Color 3</label>
                                                <input type="color" id="card_3_inner_color" name="card_3_inner_color" class="form-control" value="{{$data->card_3_inner_color ?? ''}}" placeholder="Card Inner Color 3">
                                                @error('card_3_inner_color')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_icon')
                                        <div class="col-md-12 mb-2 px-4">
                                            <label for="card_3_icon">Card Icon Image 3</label>
                                            <input type="file" data-max-file-size="2M" name="card_3_icon" id="card_1_icon"
                                                   value="{{ $data->card_3_icon ?? null }}"
                                                   class="dropify">
                                            @error('card_3_icon')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_head')
                                        <div class="col-md-12 mb-3 px-0">
                                            <label for="card_4_head">Card Heading 4</label>
                                            <input type="text" id="card_4_head" name="card_4_head" class="form-control" value="{{$data->card_4_head ?? ''}}" placeholder="Card Heading 4">
                                            @error('card_4_head')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_desc')
                                        <div class="col-md-12 mb-3 px-0">
                                            <label for="card_4_desc">Card Description 4</label>
                                            <input type="text" id="card_4_desc" name="card_4_desc" class="form-control" value="{{$data->card_4_desc ?? ''}}" placeholder="Card Description 4">
                                            @error('card_4_desc')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_redirect_url')
                                        <div class="col-md-12 mb-3 px-0">
                                            <label for="card_4_link">Card Redirect URL 4</label>
                                            <input type="text" id="card_4_link" name="card_4_link" class="form-control" value="{{$data->card_4_link ?? ''}}" placeholder="Card Link 4">
                                            @error('card_4_link')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_color')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="card_4_color">Card Color 4</label>
                                                <input type="color" id="card_4_color" name="card_4_color" class="form-control" value="{{$data->card_4_color ?? ''}}" placeholder="Card Color 4">
                                                @error('card_4_color')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_inner_color')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="card_4_inner_color">Card Inner Color 4</label>
                                                <input type="color" id="card_4_inner_color" name="card_4_inner_color" class="form-control" value="{{$data->card_4_inner_color ?? ''}}" placeholder="Card Inner Color 4">
                                                @error('card_4_inner_color')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_icon')
                                        <div class="col-md-12 mb-2 px-4">
                                            <label for="card_4_icon">Card Icon Image 4</label>
                                            <input type="file" data-max-file-size="2M" name="card_4_icon" id="card_4_icon"
                                                   value="{{ $data->card_4_icon ?? null }}"
                                                   class="dropify">
                                            @error('card_4_icon')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_head')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="card_5_head">Card Heading 5</label>
                                                <input type="text" id="card_5_head" name="card_5_head" class="form-control" value="{{$data->card_5_head ?? ''}}" placeholder="Card Heading 5">
                                                @error('card_5_head')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_desc')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="card_5_desc">Card Description 5</label>
                                                <input type="text" id="card_5_desc" name="card_5_desc" class="form-control" value="{{$data->card_5_desc ?? ''}}" placeholder="Card Description 5">
                                                @error('card_5_desc')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_redirect_url')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="card_5_link">Card Redirect URL 5</label>
                                                <input type="text" id="card_5_link" name="card_5_link" class="form-control" value="{{$data->card_5_link ?? ''}}" placeholder="Card Link 5">
                                                @error('card_5_link')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_color')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="card_5_color">Card Color 5</label>
                                                <input type="color" id="card_5_color" name="card_5_color" class="form-control" value="{{$data->card_5_color ?? ''}}" placeholder="Card Color 5">
                                                @error('card_5_color')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_inner_color')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="card_5_inner_color">Card Inner Color 5</label>
                                                <input type="color" id="card_5_inner_color" name="card_5_inner_color" class="form-control" value="{{$data->card_5_inner_color ?? ''}}" placeholder="Card Inner Color 5">
                                                @error('card_5_inner_color')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_icon')
                                            <div class="col-md-12 mb-2 px-4">
                                                <label for="card_5_icon">Card Icon Image 5</label>
                                                <input type="file" data-max-file-size="2M" name="card_5_icon" id="card_5_icon"
                                                       value="{{ $data->card_5_icon ?? null }}"
                                                       class="dropify">
                                                @error('card_5_icon')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_head')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="card_6_head">Card Heading 6</label>
                                                <input type="text" id="card_6_head" name="card_6_head" class="form-control" value="{{$data->card_6_head ?? ''}}" placeholder="Card Heading 6">
                                                @error('card_6_head')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_desc')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="card_6_desc">Card Description 6</label>
                                                <input type="text" id="card_6_desc" name="card_6_desc" class="form-control" value="{{$data->card_6_desc ?? ''}}" placeholder="Card Description 6">
                                                @error('card_6_desc')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_redirect_url')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="card_6_link">Card Redirect URL 6</label>
                                                <input type="text" id="card_6_link" name="card_6_link" class="form-control" value="{{$data->card_6_link ?? ''}}" placeholder="Card Link 6">
                                                @error('card_6_link')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_color')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="card_6_color">Card Color 6</label>
                                                <input type="color" id="card_6_color" name="card_6_color" class="form-control" value="{{$data->card_6_color ?? ''}}" placeholder="Card Color 6">
                                                @error('card_6_color')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_inner_color')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="card_6_inner_color">Card Inner Color 6</label>
                                                <input type="color" id="card_6_inner_color" name="card_6_inner_color" class="form-control" value="{{$data->card_6_inner_color ?? ''}}" placeholder="Card Inner Color 6">
                                                @error('card_6_inner_color')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_card_icon')
                                            <div class="col-md-12 mb-2 px-4">
                                                <label for="card_6_icon">Card Icon Image 6</label>
                                                <input type="file" data-max-file-size="2M" name="card_6_icon" id="card_6_icon"
                                                       value="{{ $data->card_6_icon ?? null }}"
                                                       class="dropify">
                                                @error('card_6_icon')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endcan
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-12 mb-3 px-0">
                                    <div class="col-md-6 px-0 mb-3">
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_add')
                                        <button type="button" class="btn btn-primary btn-sm add-more">Add More</button>
                                        @endcan
                                        @can($module_slug.'-widgets-call-by-reference-card-custom-custom-card_delete')
                                        <button type="button" class="btn btn-danger btn-sm delete">Remove</button>
                                        @endcan
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3 px-0">
                                    @can($module_slug.'-widgets-call-by-reference-card-custom-custom-status')
                                        @if ($include_status_radio)
                                        <div class="col-md-6 px-0 mb-3">
                                            <label for="status" class="d-block">Status</label>
                                            @error('capacity')<div class="validation-error"> {{ $message }}</div> @enderror
                                            <div class="custom__radio mb-3">
                                                <div class="d-flex box p-0 justify-content-between form-check">
                                                    <div class="w-100">
                                                        <input class="form-check-input" name="status" type="radio"
                                                            @if (count($result))  @if ($result[0]->status) checked @endif @else checked @endif value="1" id="enable">
                                                        <label class="form-check-label" for="enable">
                                                            Enable
                                                        </label>
                                                    </div>
                                                    <div class="w-100">
                                                        <input class="form-check-input" type="radio" name="status" value="0"
                                                            @if (count($result))  @if (!$result[0]->status) checked @endif @endif id="disable">
                                                        <label class="form-check-label" for="disable">
                                                            Disable
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endcan
                                    @can($module_slug.'-widgets-call-by-reference-card-custom-custom-mobile_show')
                                        @if ($include_is_mobile_show_radio)
                                            <div class="col-md-6 px-0 mb-3">
                                                <label for="is_mobile_show" class="d-block">Is Mobile Show</label>
                                                @error('is_mobile_show')<div class="validation-error"> {{ $message }}</div> @enderror
                                                <div class="custom__radio mb-3">
                                                    <div class="d-flex box p-0 justify-content-between form-check">
                                                        <div class="w-100">
                                                            <input class="form-check-input" name="is_mobile_show" type="radio"
                                                                   @if (count($result))  @if ($result[0]->is_mobile_show) checked @endif @else checked @endif value="1" id="yes">
                                                            <label class="form-check-label" for="yes">
                                                                Yes
                                                            </label>
                                                        </div>
                                                        <div class="w-100">
                                                            <input class="form-check-input" type="radio" name="is_mobile_show" value="0"
                                                                   @if (count($result))  @if (!$result[0]->is_mobile_show) checked @endif @endif id="no">
                                                            <label class="form-check-label" for="no">
                                                                No
                                                            </label>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endcan
                                    @can($module_slug.'-widgets-call-by-reference-card-custom-custom-web_show')
                                        @if ($include_is_web_show_radio)
                                            <div class="col-md-6 px-0 mb-3">
                                                <label for="is_web_show" class="d-block">Is Web Show</label>
                                                @error('is_web_show')<div class="validation-error"> {{ $message }}</div> @enderror
                                                <div class="custom__radio mb-3">
                                                    <div class="d-flex box p-0 justify-content-between form-check">
                                                        <div class="w-100">
                                                            <input class="form-check-input" name="is_web_show" type="radio"
                                                                   @if (count($result))  @if ($result[0]->is_web_show) checked @endif @else checked @endif value="1" id="web_yes">
                                                            <label class="form-check-label" for="web_yes">
                                                                Yes
                                                            </label>
                                                        </div>
                                                        <div class="w-100">
                                                            <input class="form-check-input" type="radio" name="is_web_show" value="0"
                                                                   @if (count($result))  @if (!$result[0]->is_web_show) checked @endif @endif id="web_no">
                                                            <label class="form-check-label" for="web_no">
                                                                No
                                                            </label>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endcan
                                </div>
                            </div>
                        </div>
                        <div class="bg-transparent">
                            <button type="submit" class="cst_btn px-5 btn-sm">
                                {{ count($result) ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
    </form>
@endsection

@push('scripts')
    <script>
        $('.with_cards').hide();
        if ($('#type').val() == 'single-column-cards' || $('#type').val() == 'double-column-cards') {
            $('.with_cards').show();
        }
        $(document).ready(function(){
            $(document).on('change', '#type', function () {
                if ($(this).val() == 'double-column-cards' || $(this).val() == 'single-column-cards') {
                    $('.with_cards').show();
                } else {
                    $('.with_cards').hide();
                    $('#card_1_head').val('');
                    $('#card_1_icon').val('');
                    $('#card_1_desc').val('');
                    $('#card_1_link').val('');
                    $('#card_1_color').val('');
                    $('#card_1_inner_color').val('');

                    $('#card_2_head').val('');
                    $('#card_2_icon').val('');
                    $('#card_2_desc').val('');
                    $('#card_2_link').val('');
                    $('#card_2_color').val('');
                    $('#card_2_inner_color').val('');

                    $('#card_3_head').val('');
                    $('#card_3_icon').val('');
                    $('#card_3_desc').val('');
                    $('#card_3_link').val('');
                    $('#card_3_color').val('');
                    $('#card_3_inner_color').val('');

                    $('#card_4_head').val('');
                    $('#card_4_icon').val('');
                    $('#card_4_desc').val('');
                    $('#card_4_link').val('');
                    $('#card_4_color').val('');
                    $('#card_4_inner_color').val('');

                    $('#card_5_head').val('');
                    $('#card_5_icon').val('');
                    $('#card_5_desc').val('');
                    $('#card_5_link').val('');
                    $('#card_5_color').val('');
                    $('#card_5_inner_color').val('');

                    $('#card_6_head').val('');
                    $('#card_6_icon').val('');
                    $('#card_6_desc').val('');
                    $('#card_6_link').val('');
                    $('#card_6_color').val('');
                    $('#card_6_inner_color').val('');
                }
            });

            $(document).on('click', '.add-more', function () {
                $(".clone-div:last").clone().insertAfter(".clone-div:last");
                $(".clone-div:last").find('input').val('');
            });

            $(document).on('click', '.delete', function () {
                if ($(".clone-div").length > 1) {
                    $(".clone-div:last").remove();
                }
            });
        });

    </script>
@endpush
