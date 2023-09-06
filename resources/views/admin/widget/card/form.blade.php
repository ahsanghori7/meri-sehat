@extends('layouts.widget.app')
@section('page_header')
    All {{ Str::plural($module_name) }}
@endsection
@php
    function findSelectedSpeciality($id)
    {
        $cardWidgets = App\Models\WidgetCard::get();
        foreach($cardWidgets as $card)
        {
            if($card->speciality_id == $id)
            {
                return true;
            }
        }
        return false;
    }
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

                                @php
                                    if($reference){
                                        $getReferenceWidget = \App\Models\ReferenceWidget::find($reference->id);
                                        $selectedLanguage = $getReferenceWidget->page ? $getReferenceWidget->page->lang_id : $getReferenceWidget->article->lang_id;
                                    }else {
                                        $selectedLanguage = 1;
                                    }
                                @endphp

                                @canany([$module_slug.'-widgets-card-heading',$module_slug.'-widgets-card-with-slider-heading'])
                                <div class="col-md-12 px-0 mb-3">
                                    <label for="reference_heading">Heading</label>
                                    <input type="text" name="reference_heading" id="reference_heading"
                                           value="{{ $reference ? $reference->heading : old('reference_heading') }}" class="form-control"
                                           @if($selectedLanguage != 1) placeholder="سرخی درج کریں" dir="rtl" @else placeholder="Enter Heading" @endif>
                                    @error('reference_heading')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcanany

                                @canany([$module_slug.'-widgets-card-description',$module_slug.'-widgets-card-with-slider-description'])
                                <div class="col-md-12 px-0 mb-3">
                                    <label for="reference_description">Description</label>
                                    <textarea name="reference_description" id="reference_description" class="form-control summernote"
                                              placeholder="Description">{{ $reference ? $reference->description : old('reference_description') }}</textarea>
                                    @error('reference_description')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcanany

                                @canany([$module_slug.'-widgets-card-redirect_url',$module_slug.'-widgets-card-with-slider-redirect_url'])
                                <div class="col-md-12 px-0 mb-3">
                                    <label for="reference_redirect_url">Redirection URL</label>
                                    <input type="text" name="reference_redirect_url" id="reference_redirect_url"
                                           value="{{ $reference ? $reference->redirect_url : old('reference_redirect_url') }}" class="form-control"
                                           placeholder="Enter Heading">
                                    @error('reference_redirect_url')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcanany

                                @php
                                    $getAds = App\Models\AdsWindow::where('status', true)->get();
                                @endphp

                                @canany([$module_slug.'-widgets-card-ad_window',$module_slug.'-widgets-card-with-slider-ad_window'])
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
                                @endcanany

                                @canany([$module_slug.'-widgets-card-type',$module_slug.'-widgets-card-with-slider-type'])
                                <div class="col-md-12 mb-3 px-0">
                                    <label for="type">Type</label>
                                    <select class="form-control type" name="type" id="type" required>
                                        <option value="">Select Type</option>
                                        @foreach ($card_types as $card_type)
                                            <option value="{{ $card_type['type'] }}"
                                                @if ($result && $result->type == $card_type['type']) selected @endif> {{ $card_type['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcanany
                                @canany([$module_slug.'-widgets-card-article',$module_slug.'-widgets-card-with-slider-article'])
                                <div class="col-md-12 mb-3 px-0 article" style="display: none">
                                    <label for="article">Articles</label>
                                    <select class="select2 select-limit" id="article" name="article[]" multiple="multiple">
                                        @foreach ($articles as $item)
                                            <option {{ in_array($item->id, $selected_parents)? 'selected' : ""}} value="{{$item->id}}" {{(findSelectedSpeciality($item->id) == true) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('article')
                                        <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcanany
                                @canany([$module_slug.'-widgets-card-topic',$module_slug.'-widgets-card-with-slider-topic'])
                                <div class="col-md-12 mb-3 px-0 topic" style="display: none">
                                    <label for="topic">Topics</label>
                                    <select class="select2 select-limit" id="topic" name="topic[]" multiple="multiple">
                                        @foreach ($topics as $item)
                                            <option {{ in_array($item->id, $selected_parents)? 'selected' : ""}} value="{{$item->id}}" {{(findSelectedSpeciality($item->id) == true) ? 'selected':''}}>{{$item->title}}</option>
                                        @endforeach
                                     </select>

                                    @error('topic')
                                        <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcanany
                                @canany([$module_slug.'-widgets-card-doctor',$module_slug.'-widgets-card-with-slider-doctor'])
                                <div class="col-md-12 mb-3 px-0 doctor" style="display: none">
                                    <label for="doctor">Doctors</label>
                                    <select class="select2 select-limit" id="doctor" name="doctor[]" multiple="multiple">
                                        @foreach ($doctors as $item)
                                            <option {{ in_array($item->id, $selected_parents)? 'selected' : ""}} value="{{$item->id}}" {{(findSelectedSpeciality($item->id) == true) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('doctor')
                                        <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcanany
                                @canany([$module_slug.'-widgets-card-wellness_expert',$module_slug.'-widgets-card-with-slider-wellness_expert'])
                                <div class="col-md-12 mb-3 px-0 wellness_experts" style="display: none">
                                    <label for="wellness_experts">Wellness Experts</label>
                                    <select class="select2 select-limit" id="wellness_experts" name="wellness_experts[]" multiple="multiple">
                                        @foreach ($wellness_experts as $item)
                                            <option {{ in_array($item->id, $selected_parents)? 'selected' : ""}} value="{{$item->id}}" {{(findSelectedSpeciality($item->id) == true) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('wellness_experts')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcanany
                                @canany([$module_slug.'-widgets-card-status',$module_slug.'-widgets-card-with-slider-status'])
                                    @if ($include_status_radio)
                                        @include('general_crud.status_radio')
                                    @endif
                                @endcanany
                                @canany([$module_slug.'-widgets-card-mobile_show',$module_slug.'-widgets-card-with-slider-status'])
                                    @if ($include_is_mobile_show_radio)
                                        @include('general_crud.is_mobile_show_radio')
                                    @endif
                                @endcanany
                                @canany([$module_slug.'-widgets-card-web_show',$module_slug.'-widgets-card-with-slider-status'])
                                    @if ($include_is_web_show_radio)
                                        @include('general_crud.is_web_show_radio')
                                    @endif
                                @endcanany
                            </div>
                        </div>
                        <div class="bg-transparent">
                            @canany([$module_slug.'-widgets-card-save',$module_slug.'-widgets-card-with-slider-status'])
                            <button type="submit" class="cst_btn px-5 btn-sm">
                                {{ $result ? 'Update' : 'Save' }}
                            </button>
                            @endcanany
                        </div>
                    </div>
                </div>
            </div>
    </form>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let type = $("#type").val();

            $("#topic").select2({
                maximumSelectionLength: 6
            });

            $("#article").select2({
                maximumSelectionLength: 4
            });

            $("#doctor").select2({
                maximumSelectionLength: 4
            });

            $("#wellness_experts").select2({
                maximumSelectionLength: 20
            });

            if (type == 'article') {
                $(".article").removeAttr('style');
                $(".wellness_experts").attr('style', 'display:none');
                $(".doctor").attr('style', 'display:none');
                $(".topic").attr('style', 'display:none');
            } else if (type == 'topic') {
                $(".topic").removeAttr('style');
                $(".wellness_experts").attr('style', 'display:none');
                $(".doctor").attr('style', 'display:none');
                $(".article").attr('style', 'display:none');
            } else if (type == 'doctor') {
                $(".doctor").removeAttr('style');
                $(".wellness_experts").attr('style', 'display:none');
                $(".topic").attr('style', 'display:none');
                $(".article").attr('style', 'display:none');
            } else if (type == 'wellness_experts') {
                $(".wellness_experts").removeAttr('style');
                $(".doctor").attr('style', 'display:none');
                $(".topic").attr('style', 'display:none');
                $(".article").attr('style', 'display:none');
            } else {
                $(".wellness_experts").attr('style', 'display:none');
                $(".article").attr('style', 'display:none');
                $(".doctor").attr('style', 'display:none');
                $(".topic").attr('style', 'display:none');
            }
            $("#type").on('change', function() {
                let type = $("#type").val();
                // console.log(type, "   aaaaaaaaaaa");
                if (type == 'article') {
                    $("#article").prop('required', true);
                    $(".article").removeAttr('style');
                    $(".wellness_experts").attr('style', 'display:none');
                    $(".doctor").attr('style', 'display:none');
                    $(".topic").attr('style', 'display:none');
                } else if (type == 'topic') {
                    $("#topic").prop('required', true);
                    $(".topic").removeAttr('style');
                    $(".wellness_experts").attr('style', 'display:none');
                    $(".doctor").attr('style', 'display:none');
                    $(".article").attr('style', 'display:none');
                } else if (type == 'doctor') {
                    $("#doctor").prop('required', true);
                    $(".doctor").removeAttr('style');
                    $(".wellness_experts").attr('style', 'display:none');
                    $(".topic").attr('style', 'display:none');
                    $(".article").attr('style', 'display:none');
                } else if (type == 'wellness_experts') {
                    $("#wellness_experts").prop('required', true);
                    $(".wellness_experts").removeAttr('style');
                    $(".doctor").attr('style', 'display:none');
                    $(".topic").attr('style', 'display:none');
                    $(".article").attr('style', 'display:none');
                } else {
                    $(".wellness_experts").attr('style', 'display:none');
                    $(".article").attr('style', 'display:none');
                    $(".doctor").attr('style', 'display:none');
                    $(".topic").attr('style', 'display:none');
                }
            });
        })
    </script>
@endpush
