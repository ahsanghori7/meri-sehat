@extends('layouts.widget.app')
@section('page_header')
    {{ Str::plural($module_name) }}
@endsection
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

                                    @can($module_slug.'-widgets-topic-pills-heading')
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

                                    @can($module_slug.'-widgets-topic-pills-description')
                                    <div class="col-md-12 px-0 mb-3">
                                        <label for="reference_description">Description</label>
                                        <textarea name="reference_description" id="reference_description" class="form-control summernote"
                                                  placeholder="Description">{{ $reference ? $reference->description : old('reference_description') }}</textarea>
                                        @error('reference_description')
                                        <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endcan

                                    @can($module_slug.'-widgets-topic-pills-redirect_url')
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

                                    @can($module_slug.'-widgets-topic-pills-ad_window')
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
                                @can($module_slug.'-widgets-topic-pills-type')
                                <div class="col-md-12 mb-3 px-0">
                                    <label for="type">Type</label>
                                    <select class="form-control type" name="type" id="type" required>
                                        <option value="">Select Type</option>
                                        @foreach ($parents as $parent)
                                            <option value="{{ $parent['type'] }}"
                                                @if (count($result) && $result[0]->type == $parent['type']) selected @endif> {{ $parent['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan
                                @can($module_slug.'-widgets-topic-pills-topic')
                                <div class="col-md-12 mb-3 px-0 topic">
                                    <label for="topics">Topics</label>
                                    <select class="select2" name="topics[]" id="topic" multiple="multiple">
                                        @foreach ($topics as $item)
                                            <option
                                                @if (count($result)) {{ in_array($item->id, $selected_topics) ? 'selected' : '' }} @endif
                                                value="{{ $item->id }}">{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('topics[]')
                                        <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan
                                @can($module_slug.'-widgets-topic-pills-city')
                                <div class="col-md-12 mb-3 px-0 city" style="display: none">
                                    <label for="city">Cities</label>
                                    <select class="select2" name="cities[]" id="city" multiple="multiple">
                                        @foreach ($cities as $item)
                                            <option
                                                @if (count($result)) {{ in_array($item->id, $selected_cities) ? 'selected' : '' }} @endif
                                                value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('cities[]')
                                        <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan
                                @can($module_slug.'-widgets-topic-pills-disease')
                                <div class="col-md-12 mb-3 px-0 disease" style="display: none">
                                    <label for="disease">Disease</label>
                                    <select class="select2" name="disease[]" id="disease" multiple="multiple">
                                        @foreach ($diseases as $item)
                                            <option
                                                @if (count($result)) {{ in_array($item->id, $selected_disease) ? 'selected' : '' }} @endif
                                                value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('diseases[]')
                                        <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan
                                @can($module_slug.'-widgets-topic-pills-status')
                                <div class="col-md-6 px-0 mb-3">
                                    <label for="status" class="d-block">Status</label>
                                    @error('capacity')
                                        <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                    <div class="custom__radio mb-3">
                                        <div class="d-flex box p-0 justify-content-between form-check">
                                            <div class="w-100">
                                                <input class="form-check-input" name="status" type="radio"
                                                    @if ($reference) @if ($reference->status) checked @endif
                                                @else checked @endif value="1" id="enable">
                                                <label class="form-check-label" for="enable">
                                                    Enable
                                                </label>
                                            </div>
                                            <div class="w-100">
                                                <input class="form-check-input" type="radio" name="status" value="0"
                                                    @if ($reference) @if (!$reference->status) checked @endif
                                                    @endif id="disable">
                                                <label class="form-check-label" for="disable">
                                                    Disable
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endcan
                                @can($module_slug.'-widgets-topic-pills-mobile_show')
                                <div class="col-md-6 px-0 mb-3">
                                    <label for="is_mobile_show" class="d-block">Is Mobile Show</label>
                                    @error('is_mobile_show')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                    <div class="custom__radio mb-3">
                                        <div class="d-flex box p-0 justify-content-between form-check">
                                            <div class="w-100">
                                                <input class="form-check-input" name="is_mobile_show" type="radio"
                                                       @if ($reference) @if ($reference->is_mobile_show) checked @endif
                                                       @else checked @endif value="1" id="yes">
                                                <label class="form-check-label" for="yes">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="w-100">
                                                <input class="form-check-input" type="radio" name="is_mobile_show" value="0"
                                                       @if ($reference) @if (!$reference->is_mobile_show) checked @endif
                                                       @endif id="no">
                                                <label class="form-check-label" for="no">
                                                    No
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endcan
                                @can($module_slug.'-widgets-topic-pills-web_show')
                                <div class="col-md-6 px-0 mb-3">
                                    <label for="is_web_show" class="d-block">Is Web Show</label>
                                    @error('is_web_show')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                    <div class="custom__radio mb-3">
                                        <div class="d-flex box p-0 justify-content-between form-check">
                                            <div class="w-100">
                                                <input class="form-check-input" name="is_web_show" type="radio"
                                                       @if ($reference) @if ($reference->is_web_show) checked @endif
                                                       @else checked @endif value="1" id="web_yes">
                                                <label class="form-check-label" for="web_yes">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="w-100">
                                                <input class="form-check-input" type="radio" name="is_web_show" value="0"
                                                       @if ($reference) @if (!$reference->is_web_show) checked @endif
                                                       @endif id="web_no">
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
                    </div>
                </div>
                @can($module_slug.'-widgets-topic-pills-save')
                <div class="form-group d-flex justify-content-start text-left">
                    <button type="submit" class="btn btn-success mt-2"><i class="icon-save"></i>
                        Update
                    </button>
                </div>
                @endcan
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            let type = $("#type").val();
            if (type == 'topic') {
                $(".topic").removeAttr('style');
                $(".city").attr('style', 'display:none');
                $(".disease").attr('style', 'display:none');
            } else if (type == 'city') {
                $(".city").removeAttr('style');
                $(".topic").attr('style', 'display:none');
                $(".disease").attr('style', 'display:none');
            }else if (type == 'disease') {
                $(".disease").removeAttr('style');
                $(".topic").attr('style', 'display:none');
                $(".city").attr('style', 'display:none');
            } else {
                $(".article").attr('style', 'display:none');
                $(".topic").attr('style', 'display:none');
                $(".disease").attr('style', 'display:none');
            }
            $(".type").on('change', function() {
                // let type = $(".type").val();
                let type = $("#type").val();
                if (type == 'topic') {
                    $(".topic").removeAttr('style');
                    $(".city").attr('style', 'display:none');
                    $(".disease").attr('style', 'display:none');
                }else if (type == 'disease') {
                    $(".disease").removeAttr('style');
                    $(".topic").attr('style', 'display:none');
                    $(".city").attr('style', 'display:none');
                } else if (type == 'city') {
                    $(".city").removeAttr('style');
                    $(".topic").attr('style', 'display:none');
                    $(".disease").attr('style', 'display:none');
                } else {
                    $(".city").attr('style', 'display:none');
                    $(".topic").attr('style', 'display:none');
                    $(".disease").attr('style', 'display:none');
                }
            });
        })
    </script>
@endpush
