@extends('layouts.widget.app')
@section('page_header')
    All {{ Str::plural($module_name) }}
@endsection
@section('content')
    <div class="container-fluid my-3">
        <div class="card p-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="box-header with-border">
                        <div class="d-flex justify-content-start">
                            <a href="{{ route('widget-'.$folder_name . '-add',['reference_id'=>$reference_id]) }}"
                                class="btn-success btn-sm btn mb-2 cursor-pointer">
                                <i class=" icon-add"></i>
                                Add New Reference
                            </a>
                        </div>
                    </div>
                    <form method="post" action="{{ route('widget-'.$folder_name . '-index',['reference_id'=>$reference_id]) }} ">
                        @csrf
                        <div>
                            @can($module_slug.'-widgets-vital-health-scan-heading')
                            <label for="Heading">Heading</label>
                            <input type="text" class="form-control mb-1" value="{{ $reference_widget ? ($reference_widget->heading ? $reference_widget->heading :'') : ""}}" name="reference_heading" placeholder="Heading">
                            @endcan
                            @can($module_slug.'-widgets-vital-health-scan-redirect_url')
                            <label for="Redirect URL"></label>
                            <input type="text" class="form-control mb-1" value="{{ $reference_widget ? ($reference_widget->redirect_url ? $reference_widget->redirect_url :'') : ""}}" name="reference_redirect_url" placeholder="Redirect URL">
                            @endcan
                            @can($module_slug.'-widgets-vital-health-scan-reference_description')
                            <label for="">Description</label>
                            <textarea name="reference_description" class="form-control">{{ $reference_widget ? ($reference_widget->description ? $reference_widget->description :'') : ""}}</textarea>
                            @endcan
                            @can($module_slug.'-widgets-vital-health-scan-status')
                            <div class="col-md-6 px-0 mb-3 ">
                                <label for="status" class="d-block">Status</label>
                                @error('status')<div class="validation-error"> {{ $message }}</div> @enderror
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
                            @can($module_slug.'-widgets-vital-health-scan-mobile_show')
                            <div class="col-md-6 px-0 mb-3 ">
                                <label for="status" class="d-block">Is Mobile Show</label>
                                @error('is_mobile_status')<div class="validation-error"> {{ $message }}</div> @enderror
                                <div class="custom__radio mb-3">
                                    <div class="d-flex box p-0 justify-content-between form-check">
                                        <div class="w-100">
                                            <input class="form-check-input" name="is_mobile_status" type="radio"
                                                   @if ($reference_widget)  @if ($reference_widget->is_mobile_status) checked @endif @else checked @endif value="1" id="yes">
                                            <label class="form-check-label" for="yes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="w-100">
                                            <input class="form-check-input" type="radio" name="is_mobile_status" value="0"
                                                   @if ($reference_widget)  @if (!$reference_widget->is_mobile_status) checked @endif @endif id="no">
                                            <label class="form-check-label" for="no">
                                                No
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            @endcan
                            {{-- <button type="submit" class="btn btn-success">Update</button> --}}
                        </div>
                        <table id="" class="table table-bordered table-hover mt-3" style="width:100%;">
                            <thead>
                                <tr>
                                    <th width=" 10px">-</th>
                                    <th width=" 10px">#</th>
                                    <th class="">Image</th>
                                    <th class="">Header</th>
                                    <th class="">Status</th>
                                    <th width="150px">Action</th>
                                </tr>
                            </thead>
                            <tbody class="sortable">

                                @foreach ($result as $key => $result)
                                    <tr>
                                        <td><i class="icon-drag_handle"></i></td>
                                        <td>
                                            <input type="hidden" name="sequence[]" value="{{ $result->id }}">
                                            {{ $key + 1 }}
                                        </td>
                                        <td>
                                            <img src="{{ env('ASSETS_STORAGE').$result->image }}" width="30" height="30" alt="">
                                        </td>
                                        <td>{{$result->header}}</td>
                                        <td>
                                            @if ($result->status)
                                                <span class="badge p-2 badge-success">Enable</span>
                                            @else
                                                <span class="badge p-2 badge-danger">Disable</span>
                                            @endif
                                        </td>
                                        <td>
                                            @can($module_slug.'-widgets-vital-health-scan-update')
                                            <a class="btn-primary btn-sm btn cursor-pointer"
                                                href="{{ route('widget-'.$folder_name . '-edit', ['reference_id'=>$reference_id,'id' => $result->e_id]) }}"> <i
                                                    class="icon-edit"></i> </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @can($module_slug.'-widgets-vital-health-scan-save')
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
