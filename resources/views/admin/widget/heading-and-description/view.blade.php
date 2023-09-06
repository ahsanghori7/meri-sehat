@extends('layouts.widget.app')
@section('page_header')
    All {{ Str::plural($module_name) }}
@endsection
@section('content')
    <div class="container my-3">
        <div class="card p-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="box-header with-border">
                        <div class="d-flex justify-content-start">
                            <a href="{{ route('widget-'.$folder_name.'-add', ['reference_id' => $reference_id]) }}"
                                class="btn-success btn-sm btn mb-2 cursor-pointer">
                                <i class=" icon-add"></i>
                                Add New
                            </a>
                        </div>
                    </div>
                    <form method="post" action="{{ route('widget-'.$folder_name.'-view', ['reference_id' => $reference_id]) }} ">
                        @csrf
                        <table id="" class="table table-bordered table-hover" style="width:100%;">
                            <thead>
                                <tr>
                                    <th width=" 10px">#</th>
                                    <th class="">Heading</th>
                                    <th class="">Description</th>
                                    <th class="">Status</th>
                                    <th width="150px">Action</th>
                                </tr>
                            </thead>
                            <tbody class="">

                                @foreach ($result as $key => $result)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="sequence[]" value="{{ $result->id }}">
                                            {{ $key + 1 }}
                                        </td>
                                        <td> {{ $result->heading }}</td>
                                        <td> {!! $result->description !!}</td>
                                        <td>
                                            @if ($result->status)
                                                <span class="badge p-2 badge-success">Enable</span>
                                            @else
                                                <span class="badge p-2 badge-danger">Disable</span>
                                            @endif
                                        </td>
                                        <td>
                                            @can($module_slug.'-widgets-heading-and-description-update')
                                            <a class="btn-primary btn-sm btn cursor-pointer"
                                                href="{{ route('widget-'.$folder_name . '-edit', ['reference_id' => $reference_id,'id' =>$result->e_id]) }}"> <i
                                                    class="icon-edit"></i> </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- <button type="submit" class="btn btn-sm mb-2 btn-success">
                            Update sequence
                        </button> --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
