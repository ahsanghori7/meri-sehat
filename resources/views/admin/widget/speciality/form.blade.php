@extends('layouts.widget.app')
@section('page_header')
 {{ Str::plural($module_name) }}
@endsection
@section('content')
    <div class="container my-3">
        <div class="card p-3">
            <div class="row">
                <div class="col-md-12">

                    <form method="post" action="{{ route('widget-'.$folder_name.'-index', ['reference_id' => $reference_id]) }} ">
                        @csrf
                        @can($module_slug.'-widgets-speciality-heading')
                        <div class="col-md-12 mb-3 px-0">
                            <label for="type">Heading</label>
                            <input type="text" name="reference_heading" class="form-control" value="{{$reference ? ($reference->heading ? :"") :''}}" placeholder="Heading">
                            @error('heading')
                                <div class="validation-error"> {{ $message }}</div>
                            @enderror
                        </div>
                        @endcan
                        @can($module_slug.'-widgets-speciality-description')
                        <div class="col-md-12 mb-3 px-0">
                            <label for="type">Description</label>
                            <textarea name="reference_description" id="" class="form-control">{{$reference ? ($reference->description ? :"") :''}}</textarea>
                            @error('description')
                                <div class="validation-error"> {{ $message }}</div>
                            @enderror
                        </div>
                        @endcan
                        @can($module_slug.'-widgets-speciality-redirect_url')
                        <div class="col-md-12 mb-3 px-0">
                            <label for="type">Redirect URL</label>
                            <input type="text" name="reference_redirect_url" class="form-control" value="{{$reference ? ($reference->redirect_url ? :"") :''}}" placeholder="Redirect URL">
                            @error('redirect_url')
                                <div class="validation-error"> {{ $message }}</div>
                            @enderror
                        </div>
                        @endcan
                        @can($module_slug.'-widgets-speciality-speciality')
                        <div class="col-md-12 mb-3 px-0">
                            <label for="speciality">Speciality</label>
                            <select class="select2" name="spaciality[]" id="speciality" multiple="multiple">
                                @foreach ($speciality as $item)
                                    <option {{ in_array($item->id,$selected_speciality_id)? 'selected' : ""}} value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach

                             </select>

                            @error('speciality')
                                <div class="validation-error"> {{ $message }}</div>
                            @enderror
                        </div>
                        @endcan
                        @can($module_slug.'-widgets-speciality-status')
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
                        @can($module_slug.'-widgets-speciality-mobile_show')
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
                        @can($module_slug.'-widgets-speciality-web_show')
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
                        @can($module_slug.'-widgets-speciality-save')
                        <button class="btn btn-success" type="submit">Submit</button>
                        @endcan
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
