@extends('layouts.admin.app')
@section('page_header')
    All {{Str::plural($module_name)}}
@endsection
@section('content')
    <div class="container my-3">
        <div class="card p-3">
            <div class="row">
                <div class="col-md-12">
                    @can('questionaire-form-update')
                    <div class="box-header with-border">
                        <div class="d-flex justify-content-start">
                            <a href="{{ route($folder_name.'-add') }}"
                                class="btn-success btn-sm btn mb-2 cursor-pointer">
                                <i class=" icon-add"></i>
                                Add New
                            </a>
                        </div>
                    </div>
                    @endcan
                    <form method="post" action="{{ route($folder_name.'-view') }} ">
                        @csrf
                        <table id="" class="table table-bordered table-hover" style="width:100%;">
                            <thead>
                                <tr>
                                    <th width=" 10px">#</th>
                                    <th class="">Speciality Name</th>
                                    <th class="">Form Fields</th>
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
                                        <td> {{ $result->speciality->name ?? 'None' }}</td>
                                        <td> {{ $result->fields->count() . ' Field(s)' }}</td>
                                        <td>
                                            @if ($result->status)
                                                <span class="badge p-2 badge-success">Enable</span>
                                            @else
                                                <span class="badge p-2 badge-danger">Disable</span>
                                            @endif
                                        </td>
                                        <td>
                                            @can('questionaire-form-update')
                                                <a class="btn-primary btn-sm btn cursor-pointer"
                                                    href="{{ route($folder_name.'-edit', ['id' => $result->e_id]) }}"> <i
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
