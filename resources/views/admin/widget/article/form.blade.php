@extends('layouts.widget.app')
@section('page_header')
    All {{ Str::plural($module_name) }}
@endsection
@section('content')
    <form method="post" class="needs-validation" id="disable_enter_submit" enctype="multipart/form-data" novalidate>
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

                                @canany([$module_slug.'-widgets-article-heading', $module_slug.'-widgets-article-custom-heading'])
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

                                @canany([$module_slug.'-widgets-article-description',$module_slug.'-widgets-article-custom-description'])
                                <div class="col-md-12 px-0 mb-3">
                                    <label for="reference_description">Description</label>
                                    <textarea name="reference_description" id="reference_description" class="form-control summernote"
                                              placeholder="Description">{{ $reference ? $reference->description : old('reference_description') }}</textarea>
                                    @error('reference_description')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcanany

                                @canany([$module_slug.'-widgets-article-redirect_url',$module_slug.'-widgets-article-custom-redirect_url'])
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

                                @canany([$module_slug.'-widgets-article-ad_window',$module_slug.'-widgets-article-custom-ad_window'])
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

                                @canany([$module_slug.'-widgets-article-type',$module_slug.'-widgets-article-custom-type'])
                                <div class="col-md-12 mb-3 px-0">
                                    <label for="type">Type</label>
                                    <select class="form-control type" name="type" id="type" required>
                                        @foreach ($types as $type)
                                            <option value="{{ $type['value'] }}" data-count="{{ $type['count'] }}"
                                                @if ($result) @if ($result->type == $type['value'])
                                                    selected
                                                    @php $count = $type['count']; @endphp @endif
                                                @endif>
                                                {{ $type['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcanany
                                @canany([$module_slug.'-widgets-article-article',$module_slug.'-widgets-article-custom-article'])
                                <div class="col-md-12 mb-3 px-0 article">
                                    <label for="article">Articles</label>
                                    <select class="select2 search-article" id="article-type" name="article[]" multiple="multiple">
                                    </select>
                                    @error('article')
                                        <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcanany

                                @canany([$module_slug.'-widgets-article-status',$module_slug.'-widgets-article-custom-status'])
                                    <div class="col-md-6 px-0 mb-3">
                                        <label for="status" class="d-block">Status</label>
                                        @error('status')<div class="validation-error"> {{ $message }}</div> @enderror
                                        <div class="custom__radio mb-3">
                                            <div class="d-flex box p-0 justify-content-between form-check">
                                                <div class="w-100">
                                                    <input class="form-check-input" name="status" type="radio"
                                                           {{ (isset($result) && $result->status == 1) ? 'checked' : '' }} value="1" id="enable">
                                                    <label class="form-check-label" for="enable">
                                                        Enable
                                                    </label>
                                                </div>
                                                <div class="w-100">
                                                    <input class="form-check-input" type="radio" name="status" value="0"
                                                           {{ (isset($result) && $result->status == 0) ? 'checked' : '' }} id="disable">
                                                    <label class="form-check-label" for="disable">
                                                        Disable
                                                    </label>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endcanany

                                @canany([$module_slug.'-widgets-article-mobile_show',$module_slug.'-widgets-article-custom-mobile_show'])
                                    <div class="col-md-6 px-0 mb-3">

                                        <label for="status" class="d-block">Is Mobile Show</label>
                                        @error('is_mobile_show')<div class="validation-error"> {{ $message }}</div> @enderror
                                        <div class="custom__radio mb-3">
                                            <div class="d-flex box p-0 justify-content-between form-check">
                                                <div class="w-100">
                                                    <input class="form-check-input" name="is_mobile_show" type="radio"
                                                           {{ (isset($result) && $result->is_mobile_show == 1) ? 'checked' : '' }} value="1" id="yes">
                                                    <label class="form-check-label" for="yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="w-100">
                                                    <input class="form-check-input" type="radio" name="is_mobile_show" value="0"
                                                           {{ (isset($result) && $result->is_mobile_show == 0) ? 'checked' : '' }} id="no">
                                                    <label class="form-check-label" for="no">
                                                        No
                                                    </label>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endcanany

                                @canany([$module_slug.'-widgets-article-web_show',$module_slug.'-widgets-article-custom-web_show'])
                                    <div class="col-md-6 px-0 mb-3">

                                        <label for="is_web_show" class="d-block">Is Web Show</label>
                                        @error('is_web_show')<div class="validation-error"> {{ $message }}</div> @enderror
                                        <div class="custom__radio mb-3">
                                            <div class="d-flex box p-0 justify-content-between form-check">
                                                <div class="w-100">
                                                    <input class="form-check-input" name="is_web_show" type="radio"
                                                           {{ (isset($result) && $result->is_web_show == 1) ? 'checked' : '' }} value="1" id="web_yes">
                                                    <label class="form-check-label" for="web_yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="w-100">
                                                    <input class="form-check-input" type="radio" name="is_web_show" value="0"
                                                           {{ (isset($result) && $result->is_web_show == 0) ? 'checked' : '' }} id="web_no">
                                                    <label class="form-check-label" for="web_no">
                                                        No
                                                    </label>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endcanany
                            </div>
                        </div>
                        <div class="bg-transparent">
                            @canany([$module_slug.'-widgets-article-save',$module_slug.'-widgets-article-custom-save'])
                            <button type="submit" class="cst_btn px-5 btn-sm">
                                {{ $result ? 'Update' : 'Save' }}
                            </button>
                            @endcanany
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <form method="post" action="{{ route('widget-' . $folder_name . '-edit-sequence', ['reference_id' => $reference_id]) }}"
        class="needs-validation" id="disable_enter_submit" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="container-fluid animatedParent animateOnce my-3">
            <div class="animated fadeInUpShort">
                <div class="row">
                    <div class="col-md-12 ">
                        <table id="" class="table table-bordered table-hover mt-3" style="width:100%;">
                            <thead>
                                <tr>
                                    <th width=" 10px">-</th>
                                    <th width=" 10px">#</th>
                                    <th class="">Name</th>
                                    <th class="">Action</th>
                                </tr>
                            </thead>
                            <tbody class="sortable">

                                @foreach ($selected_articles as $key => $widgetArticle)
                                    <tr>
                                        <td><i class="icon-drag_handle"></i></td>
                                        <td>
                                            <input type="hidden" name="sequence[]" value="{{ $widgetArticle->id }}">
                                            {{ $key + 1 }}
                                        </td>
                                        <td> {{ $widgetArticle->article->name }} </td>
                                        <td>
                                            @canany([$module_slug.'-widgets-article-delete',$module_slug.'-widgets-article-custom-delete'])
                                            <a class="btn-outline-danger btn-sm btn cursor-pointer" href="{{route('widget-article-delete-record',['id' => $widgetArticle->id])}}"> <i class="icon-trash"></i></a>
                                            @endcanany
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @canany($module_slug.'-widgets-article-save',$module_slug.'-widgets-article-custom-save')
        <button type="submit" class="btn btn-sm mx-3 mb-2 btn-success">
            Update Changes
        </button>
        @endcanany
    </form>
@endsection

@push('scripts')
    <script>
        $("#article-type").select2({
            placeholder: "Search for an Articles",
            minimumInputLength: 1,
            ajax: {
                url: "{{ route('article-searches') }}",
                type: 'GET',
                data: function (term, page) {
                    return {
                        page_limit: 10,
                        search: term.term,
                        lang_id: '{{$selected_language}}'
                    };
                },
                processResults: function (data) {
                    return {
                        "results": data.data
                    };
                }
            }
        });

        $(document).ready(function() {

            $('#article').keyup(function(){
                console.log("c" + $(this).val());
            });


            let type = $("#type").val();
            if(type == 'featured'){
                $("#article").select2({
                    maximumSelectionLength: 4
                });
            }
            if(type == 'single_column'){
                $("#article").select2({
                    maximumSelectionLength: 3
                });
            }
            if(type == 'double_column'){
                $("#article").select2({
                    maximumSelectionLength: 6
                });
            }
            if(type == 'single_article'){
                $("#article").select2({
                    maximumSelectionLength: 1
                });
            }
            $("#type").on('change', function() {
                let type = $("#type").val();
                if(type == 'featured'){
                    $("#article").select2({
                        maximumSelectionLength: 4
                    });
                }
                if(type == 'single_column'){
                    $("#article").select2({
                        maximumSelectionLength: 3
                    });
                }
                if(type == 'double_column'){
                    $("#article").select2({
                        maximumSelectionLength: 6
                    });
                }
                if(type == 'single_article'){
                    $("#article").select2({
                        maximumSelectionLength: 1
                    });
                }
                $("#article").val(null).trigger("change");
            });
        });
    </script>
@endpush
