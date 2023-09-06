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

                                    @can($module_slug.'-widgets-call-by-reference-card-heading')
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

                                    @can($module_slug.'-widgets-call-by-reference-card-description')
                                    <div class="col-md-12 px-0 mb-3">
                                        <label for="reference_description">Description</label>
                                        <textarea name="reference_description" id="reference_description" class="form-control summernote"
                                                  placeholder="Description">{{ $reference ? $reference->description : old('reference_description') }}</textarea>
                                        @error('reference_description')
                                        <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endcan

                                    @can($module_slug.'-widgets-call-by-reference-card-redirect_url')
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

                                    @can($module_slug.'-widgets-call-by-reference-card-ad_window')
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
                                @can($module_slug.'-widgets-call-by-reference-card-type')
                                <div class="col-md-12 mb-3 px-0">
                                    <label for="type">Type</label>
                                    <select class="form-control type" name="type" id="type" onchange="showDiv(this)" required @if (count($result))
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
                                <div class="col-md-12 mb-3 px-0" id="parent-div">
                                    @can($module_slug.'-widgets-call-by-reference-card-heading')
                                    <div class="col-md-12 mb-3 px-0">
                                        <label for="heading">Card Heading</label>
                                        <input type="text" id="heading" name="single_column_heading[]" class="form-control" value="{{count($result) ? ($result[0]->heading ? :"") :''}}" placeholder="Heading">
                                        @error('heading')
                                            <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endcan
                                    @can($module_slug.'-widgets-call-by-reference-card-description')
                                    <div class="col-md-12 mb-3 px-0">
                                        <label for="description">Card Description</label>
                                        <input type="text" id="description" name="single_column_description[]" class="form-control" value="{{count($result) ? ($result[0]->description ? :"") :''}}" placeholder="Description">
                                        @error('description')
                                            <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endcan
                                    @can($module_slug.'-widgets-call-by-reference-card-image_redirect_url')
                                    <div class="col-md-12 mb-3 px-0">
                                        <label for="redirect_url">Image Redirect URL</label>
                                        <input type="text" id="redirect_url" name="single_column_redirect_url[]" class="form-control" value="{{count($result) ? ($result[0]->redirect_url ? :"") :''}}" placeholder="Heading">
                                        @error('redirect_url')
                                            <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endcan
                                    @can($module_slug.'-widgets-call-by-reference-card-redirect_button_text')
                                    <div class="col-md-12 mb-3 px-0">
                                        <label for="button_text">Redirect Button Text</label>
                                        <input type="text" id="button_text" name="single_column_button_text[]" class="form-control" value="{{count($result) ? ($result[0]->button_text ? :"") :''}}" placeholder="Heading">
                                        @error('button_text')
                                            <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endcan
                                    @can($module_slug.'-widgets-call-by-reference-card-card_color')
                                    <div class="col-md-12 mb-3 px-0">
                                        <label for="card_color">Card Color</label>
                                        <input type="color" id="card_color" name="single_column_card_color[]" class="form-control" value="{{count($result) ? ($result[0]->card_color ? :"") :''}}" placeholder="Heading">
                                        @error('card_color')
                                            <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endcan
                                    @can($module_slug.'-widgets-call-by-reference-card-image_position')
                                    <div class="col-md-12 mb-3 px-0">
                                        <label for="image_position">Image Position</label>
                                        <select class="form-control type" name="single_column_image_position[]" id="image_position">
                                            @foreach ($image_positions as $image_position)
                                                <option value="{{ $image_position['type'] }}"
                                                    @if (count($result) && $result[0]->image_position == $image_position['type']) selected @endif> {{ $image_position['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('image_position')
                                            <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endcan
                                    @can($module_slug.'-widgets-call-by-reference-card-image')
                                    <div class="col-md-12 mb-2 px-4">
                                        <label for="image">Image</label>
                                        <input type="file" data-max-file-size="2M" name="single_column_image[]" id="image"
                                            value="{{ count($result) ? env('ASSETS_STORAGE').$result[0]->image : null }}"
                                            data-default-file="{{ count($result) ? env('ASSETS_STORAGE').$result[0]->image : '' }}"
                                            class="dropify">
                                        @error('image')
                                            <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endcan
                                </div>

                                <div class="col-md-12 mb-3 px-0" id="second-div" style="display: none">
                                    @if (count($result) && $result[0]->type == 'double-column' || !count($result) || !$result)
                                        @for($i = 1; $i <= 2; $i++)
                                            @can($module_slug.'-widgets-call-by-reference-card-card_head')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="type"> Heading {{ $i }}</label>
                                                <input type="text" id="heading1" name="heading[]" class="form-control" value="{{count($result) ? ($result[$i-1]->heading ? :"") :''}}" placeholder="Heading">
                                                @error('heading')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                            @endcan
                                            @can($module_slug.'-widgets-call-by-reference-card-card_desc')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="description"> Description {{ $i }}</label>
                                                <input type="text" id="description1" name="description[]" class="form-control" value="{{count($result) ? ($result[$i-1]->description ? :"") :''}}" placeholder="Description">
                                                @error('description')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                            @endcan
                                            @can($module_slug.'-widgets-call-by-reference-card-card_redirect_url')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="redirect_url"> Redirect URL {{ $i }}</label>
                                                <input type="text" id="redirect_url1" name="redirect_url[]" class="form-control" value="{{count($result) ? ($result[$i-1]->redirect_url ? :"") :''}}" placeholder="Card Color">
                                                @error('redirect_url')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                            @endcan
                                            @can($module_slug.'-widgets-call-by-reference-card-redirect_button_text')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="button_text"> Redirect Button Text {{ $i }}</label>
                                                <input type="text" id="button_text1" name="button_text[]" class="form-control" value="{{count($result) ? ($result[$i-1]->button_text ? :"") :''}}" placeholder="Card Color">
                                                @error('button_text')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                            @endcan
                                            @can($module_slug.'-widgets-call-by-reference-card-card_color')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="card_color">Card Color {{ $i }}</label>
                                                <input type="color" id="card_color1" name="card_color[]" class="form-control" value="{{count($result) ? ($result[$i-1]->card_color ? :"") :''}}" placeholder="Card Color">
                                                @error('card_color')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                            @endcan
                                            @can($module_slug.'-widgets-call-by-reference-card-card_position')
                                            <div class="col-md-12 mb-3 px-0">
                                                <label for="image_position"> Image Positiosn {{ $i }}</label>
                                                <select class="form-control type" name="image_position[]" id="image_position1">
                                                    @foreach ($image_positions as $image_position)
                                                        <option value="{{ $image_position['type'] }}"
                                                            @if (count($result) && $result[$i-1]->image_position == $image_position['type']) selected @endif> {{ $image_position['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('image_position')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                            @endcan
                                            @can($module_slug.'-widgets-call-by-reference-card-card_image')
                                            <div class="col-md-12 mb-2 px-4">
                                                <label for="image"> Image {{ $i }}</label>
                                                <input type="file" data-max-file-size="2M" name="image[]" id="image1"
                                                    value="{{ count($result) ? $result[$i-1]->image : null }}"
                                                    data-default-file="{{ count($result) ? env('ASSETS_STORAGE'). $result[$i-1]->image : '' }}"
                                                    class="dropify">
                                                @error('image')
                                                    <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                            @endcan
                                        @endfor
                                        @endif
                                    </div>

                                @can($module_slug.'-widgets-call-by-reference-card-status')
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
                                @can($module_slug.'-widgets-call-by-reference-card-mobile_show')
                                    @if ($include_is_mobile_show_radio)
                                        <div class="col-md-6 px-0 mb-3">
                                            <label for="status" class="d-block">Is Mobile Show</label>
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
                                @can($module_slug.'-widgets-call-by-reference-card-web_show')
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
                        <div class="bg-transparent">
                            @can($module_slug.'-widgets-call-by-reference-card-save')
                            <button type="submit" class="cst_btn px-5 btn-sm">
                                {{ count($result) ? 'Update' : 'Save' }}
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


        $(document).ready(function(){
            let value = document.getElementById("type");
           showDiv(value);
        });

        function showDiv(value)
        {

            console.log(value.value);

            if(value.value == 'double-column') {
                $('#parent-div').attr('style', 'display:none');
                $('#second-div').removeAttr('style', 'display:none');
                $('#heading').removeAttr('name');
                $('#description').removeAttr('name');
                $('#redirect_url').removeAttr('name');
                $('#button_text').removeAttr('name');
                $('#card_color').removeAttr('name');
                $('#image_position').removeAttr('name');
                $('#image').removeAttr('name');
            }else if(value.value == 'single-column'){
                $('#second-div').attr('style', 'display:none');
                $('#parent-div').removeAttr('style', 'display:none');
                $('#heading1').removeAttr('name');
                $('#description1').removeAttr('name');
                $('#redirect_url1').removeAttr('name');
                $('#button_text1').removeAttr('name');
                $('#card_color1').removeAttr('name');
                $('#image_position1').removeAttr('name');
                $('#image1').removeAttr('name');
            }
        }
    </script>
@endpush
