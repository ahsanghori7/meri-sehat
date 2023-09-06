@extends('layouts.widget.app')
@section('page_header')
    All {{ Str::plural($module_name) }}
@endsection
@section('content')
    <div class="container-fluid my-3">
        <div class="card p-3">
            <div class="row">
                <div class="col-md-12">

                    <form method="post" action="{{ route('widget-'.$folder_name . '-index',['reference_id'=>$reference_id]) }} ">
                        @csrf
                        <div>
                            @can($module_slug.'-widgets-search-heading')
                            <label for="Heading">Heading</label>
                            <input type="text" class="form-control mb-1" value="{{ $reference_widget ? ($reference_widget->heading ? $reference_widget->heading :'') : ""}}" name="reference_heading" placeholder="Heading">
                            @endcan
                            @can($module_slug.'-widgets-search-redirect_url')
                            <label for="Redirect URL"></label>
                            <input type="text" class="form-control mb-1" value="{{ $reference_widget ? ($reference_widget->redirect_url ? $reference_widget->redirect_url :'') : ""}}" name="reference_redirect_url" placeholder="Redirect URL">
                            @endcan
                            @can($module_slug.'-widgets-search-description')
                            <label for="">Description</label>
                            <textarea name="reference_description" class="form-control">{{ $reference_widget ? ($reference_widget->description ? $reference_widget->description :'') : ""}}</textarea>
                            @endcan
                            @can($module_slug.'-widgets-search-status')
                            <div class="col-md-6 px-0 mb-3 ">
                                <label for="status" class="d-block">Status</label>
                                @error('capacity')<div class="validation-error"> {{ $message }}</div> @enderror
                                <div class="custom__radio mb-3">
                                    <div class="d-flex box p-0 justify-content-between form-check">
                                        <div class="w-100">
                                            <input class="form-check-input" name="status" type="radio"
                                                @if ($reference_widget)  @if ($reference_widget->status) checked @endif @else checked @endif value="1" id="enable">
                                            <label class="form-check-label" for="enable">
                                                Enable
                                            </label>
                                        </div>
                                        <div class="w-100">
                                            <input class="form-check-input" type="radio" name="status" value="0"
                                                @if ($reference_widget)  @if (!$reference_widget->status) checked @endif @endif id="disable">
                                            <label class="form-check-label" for="disable">
                                                Disable
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            @endcan
                            @can($module_slug.'-widgets-search-mobile_show')
                            <div class="col-md-6 px-0 mb-3 ">
                                <label for="status" class="d-block">Is Mobile Show</label>
                                @error('is_mobile_show')<div class="validation-error"> {{ $message }}</div> @enderror
                                <div class="custom__radio mb-3">
                                    <div class="d-flex box p-0 justify-content-between form-check">
                                        <div class="w-100">
                                            <input class="form-check-input" name="is_mobile_show" type="radio"
                                                   @if ($reference_widget)  @if ($reference_widget->is_mobile_show) checked @endif @else checked @endif value="1" id="yes">
                                            <label class="form-check-label" for="yes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="w-100">
                                            <input class="form-check-input" type="radio" name="is_mobile_show" value="0"
                                                   @if ($reference_widget)  @if (!$reference_widget->is_mobile_show) checked @endif @endif id="no">
                                            <label class="form-check-label" for="no">
                                                No
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            @endcan
                            @can($module_slug.'-widgets-search-web_show')
                            <div class="col-md-6 px-0 mb-3 ">
                                <label for="is_web_show" class="d-block">Is Web Show</label>
                                @error('is_web_show')<div class="validation-error"> {{ $message }}</div> @enderror
                                <div class="custom__radio mb-3">
                                    <div class="d-flex box p-0 justify-content-between form-check">
                                        <div class="w-100">
                                            <input class="form-check-input" name="is_web_show" type="radio"
                                                   @if ($reference_widget)  @if ($reference_widget->is_web_show) checked @endif @else checked @endif value="1" id="web_yes">
                                            <label class="form-check-label" for="web_yes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="w-100">
                                            <input class="form-check-input" type="radio" name="is_web_show" value="0"
                                                   @if ($reference_widget)  @if (!$reference_widget->is_web_show) checked @endif @endif id="web_no">
                                            <label class="form-check-label" for="web_no">
                                                No
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            @endcan
                            {{-- <button type="submit" class="btn btn-success">Update</button> --}}
                        </div>

                        @can($module_slug.'-widgets-search-save')
                        <button type="submit" class="btn btn-sm mb-2 btn-success">
                            Update Changes
                        </button>
                        @endcan
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
