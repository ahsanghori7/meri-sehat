@extends('layouts.widget.app')
@section('page_header')
    All {{ Str::plural($module_name) }}
@endsection
@php
    function findSelectedSpeciality($id, $reference_id){
        $specialityWidget = App\Models\WidgetMostSearchSpeciality::where('reference_id', $reference_id)->get();
        foreach($specialityWidget as $spt){
            if($spt->speciality_id == $id){
                return true;
            }
        }
        return false;
    }
@endphp
@section('content')
    <form method="post"  class="needs-validation" id="disable_enter_submit" enctype="multipart/form-data" novalidate>
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

                                    @can($module_slug.'-widgets-most-searched-specialties-heading')
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

                                    @can($module_slug.'-widgets-most-searched-specialties-description')
                                    <div class="col-md-12 px-0 mb-3">
                                        <label for="reference_description">Description</label>
                                        <textarea name="reference_description" id="reference_description" class="form-control summernote"
                                                  placeholder="Description">{{ $reference ? $reference->description : old('reference_description') }}</textarea>
                                        @error('reference_description')
                                        <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endcan

                                    @can($module_slug.'-widgets-most-searched-specialties-redirect_url')
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

                                    @can($module_slug.'-widgets-most-searched-specialties-ad_window')
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
                                @can($module_slug.'-widgets-most-searched-specialties-speciality')
                                <div class="col-md-12 px-0 mb-3">
                                    <label for="name">Speciality</label>
                                    <select class="js-example-basic-multiple" name="speciality_id[]" multiple="multiple">
                                        @foreach($specialities as $key => $speciality)
                                            <option value="{{$speciality->id}}" {{(findSelectedSpeciality($speciality->id, $reference->id) == true) ? 'selected':''}}>{{$speciality->name}}{{$speciality->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('speciality_id')
                                        <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan
                                @can($module_slug.'-widgets-most-searched-specialties-status')
                                    @if ($include_status_radio)
                                        @include('general_crud.status_radio')
                                    @endif
                                @endcan

                                @can($module_slug.'-widgets-most-searched-specialties-mobile_show')
                                    @if ($include_is_mobile_show_radio)
                                        @include('general_crud.is_mobile_show_radio')
                                    @endif
                                @endcan

                                @can($module_slug.'-widgets-most-searched-specialties-web_show')
                                    @if ($include_is_web_show_radio)
                                        @include('general_crud.is_web_show_radio')
                                  @endif
                                @endcan
                            </div>
                        </div>
                        <div class="bg-transparent">
                            @can($module_slug.'-widgets-most-searched-specialties-save')
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
            $('.js-example-basic-multiple').select2();
        });
   </script>
@endpush
