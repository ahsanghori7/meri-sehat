@extends('layouts.widget.app')
@section('page_header')
    {{ Str::plural($page_header) }}
@endsection
@php
@endphp
@section('content')
    <form method="post" class="needs-validation" id="disable_enter_submit" enctype="multipart/form-data" novalidate>
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

                                    @can($module_slug.'-widgets-media-custom-heading')
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

                                    @can($module_slug.'-widgets-media-custom-description')
                                        <div class="col-md-12 px-0 mb-3">
                                            <label for="reference_description">Description</label>
                                            <textarea name="reference_description" id="reference_description" class="form-control summernote"
                                                      placeholder="Description">{{ $reference ? $reference->description : old('reference_description') }}</textarea>
                                            @error('reference_description')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endcan

                                    @can($module_slug.'-widgets-media-custom-redirect_url')
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

                                    @can($module_slug.'-widgets-media-custom-ad_window')
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
                                @can($module_slug.'-widgets-media-custom-type')
                                <div class="col-md-12 mb-3 px-0">
                                    <label for="type">Type</label>
                                    <select class="form-control type" name="type" id="type" required>
                                        <option value="video"  @if($result && $result->type == 'video') selected @endif> Video </option>
                                        <option value="image"  @if($result && $result->type == 'image') selected @endif> Image </option>
                                        <option value="popup_video"  @if($result && $result->type == 'popup_video') selected @endif> Popup Video </option>
                                        <option value="popup_image"  @if($result && $result->type == 'popup_image') selected @endif> Popup Image </option>
                                    </select>
                                    @error('type')
                                        <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan
                                @if (count($videos) > 0)
                                    @foreach($videos as $video)
                                        <div id="div_{{$loop->iteration	}}" class="element w-100">
                                            <div class="col-md-12 mb-3 px-0 link">
                                                <div class="row align-items-center px-0">
                                                    @can($module_slug.'-widgets-media-custom-language')
                                                    <div class="col-md-3 px-0 link">
                                                        <label for="type">Language</label>
                                                        <select class="form-control type" name="language[]" id="language" required>
                                                            @foreach($media_language as $language)
                                                                <option value="{{ $language->id }}" {{$video->language_id == $language->id ? 'selected' : ''}} >{{ $language->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('language')
                                                        <div class="validation-error"> {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    @endcan
                                                    @can($module_slug.'-widgets-media-custom-is_default')
                                                    <div class="col-md-3 px-0 link">
                                                        <label for="is_default_{{$loop->iteration}}" id="label_is_default_{{$loop->iteration}}">Default Language</label>
                                                        <input type="checkbox" name="is_default[{{$loop->index}}]" id="is_default_{{$loop->iteration}}" {{$video->is_default == 1 ? 'checked' : ''}} class="checkboxes" value="1">
                                                        @error('is_default')
                                                        <div class="validation-error"> {{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    @endcan
                                                    @can($module_slug.'-widgets-media-custom-add_language')
                                                    <div class="col-md-3 px-0 link">
                                                        <button type="button" class="add">Add Language</button>
                                                    </div>
                                                    @endcan
                                                </div>
                                            </div>

                                            @can($module_slug.'-widgets-media-custom-heading')
                                            <div class="col-md-12 mb-3 px-0 link">
                                                <label for="heading">Heading</label>
                                                <input type="text" id="link" name="heading[]" class="form-control" value="{{$video->heading ?? "" }}" placeholder="Heading">
                                                @error('heading')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                            @endcan

                                            @can($module_slug.'-widgets-media-custom-media_url')
                                            <div class="col-md-12 mb-3 px-0 link">
                                                <label for="type">Media URL</label>
                                                <input type="text" id="link" name="redirect_url[]" class="form-control" value="{{$video ? ($video->file_url ? :"") :''}}" placeholder="Media URL">
                                                @error('redirect_url')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                            @endcan
                                        </div>
                                    @endforeach

                                @else
                                    <div id="div_1" class="element w-100">
                                        <div class="col-md-12 mb-3 px-0 link">
                                            <div class="row align-items-center px-0">
                                                @can($module_slug.'-widgets-media-custom-language')
                                                <div class="col-md-6 mb-0 link">
                                                    <label for="type">Language</label>
                                                    <select class="form-control type" name="language[]" id="language" required>
                                                        @foreach($media_language as $language)
                                                            <option value="{{ $language->id }}">{{ $language->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('language')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @endcan
                                                @can($module_slug.'-widgets-media-custom-is_default')
                                                <div class="col-md-3 mb-0 link">
                                                    <label for="is_default_1" id="label_is_default_1">Default Language</label>
                                                    <input type="checkbox" name="is_default[0]" id="is_default_1" class="checkboxes" value="1">
                                                    @error('is_default')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @endcan
                                                @can($module_slug.'-widgets-media-custom-add_language')
                                                <div class="col-md-3 mb-0 link">
                                                    <button type="button" class="add">Add Language</button>
                                                </div>
                                                @endcan
                                            </div>
                                        </div>
                                        @can($module_slug.'-widgets-media-custom-heading')
                                        <div class="col-md-12 mb-3 px-0 link">
                                            <label for="heading">Heading</label>
                                            <input type="text" id="link" name="heading[]" class="form-control" value="{{$result->heading ?? "" }}" placeholder="Heading">
                                            @error('heading')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endcan
                                        @can($module_slug.'-widgets-media-custom-media_url')
                                        <div class="col-md-12 mb-3 px-0 link">
                                            <label for="type">Media URL</label>
                                            <input type="text" id="link" name="redirect_url[]" class="form-control" value="{{$result ? ($result->file_url ? :"") :''}}" placeholder="Media URL">
                                            @error('redirect_url')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endcan
                                    </div>
                                @endif

                                @can($module_slug.'-widgets-media-custom-heading')
                                <div class="col-md-12 mb-3 px-0 image" style="display: none;">
                                    <label for="heading">Heading</label>
                                    <input type="text" id="link" name="heading[]" class="form-control" value="{{$result->heading ?? "" }}" placeholder="Heading">
                                    @error('heading')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan

                                @can($module_slug.'-widgets-media-custom-image')
                                <div class="col-md-12 mb-3 px-0 image" style="display: none;">
                                    <label for="image">Image<span class="text-danger" >*</span> (Dimensions : 800 X 800)</label>
                                    <input type="file" data-max-file-size="2M" name="image" id="image"
                                        value="{{ isset($result->source) ? str_contains($result->source, 'http') ? $result->source : env('ASSETS_STORAGE').$result->source : '' }}"
                                        data-default-file="{{ isset($result->source) ? str_contains($result->source, 'http') ? $result->source : env('ASSETS_STORAGE').$result->source : '' }}"
                                        class="dropify">
{{--                                    <input type="hidden" name="source" value="{{ isset($result->source) ? $result->source : '' }}"/>--}}
                                    @error('image')
                                        <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan
                                @can($module_slug.'-widgets-media-custom-status')
                                <div class="col-md-6 px-0 mb-3">
                                    <label for="status" class="d-block">Status</label>
                                    @error('capacity')<div class="validation-error"> {{ $message }}</div> @enderror
                                    <div class="custom__radio mb-3">
                                        <div class="d-flex box p-0 justify-content-between form-check">
                                            <div class="w-100">
                                                <input class="form-check-input" name="status" type="radio"
                                                    @if ($reference)  @if ($reference->status) checked @endif @else checked @endif value="1" id="enable">
                                                <label class="form-check-label" for="enable">
                                                    Enable
                                                </label>
                                            </div>
                                            <div class="w-100">
                                                <input class="form-check-input" type="radio" name="status" value="0"
                                                    @if ($reference)  @if (!$reference->status) checked @endif @endif id="disable">
                                                <label class="form-check-label" for="disable">
                                                    Disable
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endcan
                                @can($module_slug.'-widgets-media-custom-mobile_show')
                                <div class="col-md-6 px-0 mb-3">
                                    <label for="is_mobile_show" class="d-block">Is Mobile Show</label>
                                    @error('is_mobile_show')<div class="validation-error"> {{ $message }}</div> @enderror
                                    <div class="custom__radio mb-3">
                                        <div class="d-flex box p-0 justify-content-between form-check">
                                            <div class="w-100">
                                                <input class="form-check-input" name="is_mobile_show" type="radio"
                                                       @if ($reference)  @if ($reference->is_mobile_show) checked @endif @else checked @endif value="1" id="yes">
                                                <label class="form-check-label" for="yes">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="w-100">
                                                <input class="form-check-input" type="radio" name="is_mobile_show" value="0"
                                                       @if ($reference)  @if (!$reference->is_mobile_show) checked @endif @endif id="no">
                                                <label class="form-check-label" for="no">
                                                    No
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endcan
                                @can($module_slug.'-widgets-media-custom-web_show')
                                <div class="col-md-6 px-0 mb-3">
                                    <label for="is_web_show" class="d-block">Is Web Show</label>
                                    @error('is_web_show')<div class="validation-error"> {{ $message }}</div> @enderror
                                    <div class="custom__radio mb-3">
                                        <div class="d-flex box p-0 justify-content-between form-check">
                                            <div class="w-100">
                                                <input class="form-check-input" name="is_web_show" type="radio"
                                                       @if ($reference)  @if ($reference->is_web_show) checked @endif @else checked @endif value="1" id="web_yes">
                                                <label class="form-check-label" for="web_yes">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="w-100">
                                                <input class="form-check-input" type="radio" name="is_web_show" value="0"
                                                       @if ($reference)  @if (!$reference->is_web_show) checked @endif @endif id="web_no">
                                                <label class="form-check-label" for="web_no">
                                                    No
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endcan
                            </div>
                        </div>
                        <div class="bg-transparent">
                            @can($module_slug.'-widgets-media-custom-save')
                            <button type="submit" class="cst_btn px-5 btn-sm">
                                {{ $result ? 'Update' : 'Save' }}
                            </button>
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

            let ref = $("#type").val();
            console.log(ref);
            if (ref == 'image') {
                $(".image").removeAttr('style');
                $(".link").attr('style', 'display:none');
            } else {
                $(".link").removeAttr('style');
                $(".image").attr('style', 'display:none');
            }
            $("#type").on('change', function() {
                let type = $("#type").val();
                console.log(type);
                if (type == 'image') {
                    $(".image").removeAttr('style');
                    $(".link").attr('style', 'display:none');
                } else {
                    $(".link").removeAttr('style');
                    $(".image").attr('style', 'display:none');
                }
            });

            $(".add").click(function() {
                var total_element = $(".element").length;
                var lastid = $(".element:last").attr("id");
                var split_id = lastid.split("_");
                var nextindex = Number(split_id[1]) + 1;
                var max = 5;
                if (total_element < max) {
                    $(".element:last").after("<div id='div_" + nextindex + "' class='element w-100'></div>");
                    $("#div_" + nextindex).append($("#div_1").html());
                    $("#div_" + nextindex).find('#label_is_default_1').attr('for', 'is_default_'+nextindex);
                    $("#div_" + nextindex).find('#label_is_default_1').attr('id', 'label_is_default_'+nextindex);
                    $("#div_" + nextindex).find('#is_default_1').attr('name', 'is_default['+parseInt(nextindex-1)+']');
                    $("#div_" + nextindex).find('#is_default_1').attr('id', 'is_default_'+nextindex);
                    $("#div_" + nextindex).find('#is_default_1').val('0');
                    $("#div_" + nextindex).find('#is_default_1').prop('checked' , false);
                    $("#div_" + nextindex).find('.add').html('Remove Language');
                    $("#div_" + nextindex).find('.add').attr('id', 'remove_'+nextindex);
                    $("#div_" + nextindex).find('.add').removeClass('add').addClass('remove');
                }
            });

            $('.box_border').on('click', '.remove', function() {
                var id = this.id;
                var split_id = id.split("_");
                var deleteindex = split_id[1];
                $("#div_" + deleteindex).remove();
            });

            $(document).on('click','.checkboxes', function(){
                $('.checkboxes').prop('checked', false);
                $(this).prop('checked', true);
            });
        })
    </script>
@endpush
