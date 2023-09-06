@extends('layouts.admin.app')
@section('page_header')
    All Draft {{Str::plural($module_name)}}
@endsection
@section('content')
    <div class="container my-3">
        <div class="card p-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="box-header with-border">
                        <div class="d-flex justify-content-start">
                            <a href="{{ route($folder_name.'-draft-add') }}"
                                class="btn-success btn-sm btn mb-2 cursor-pointer">
                                <i class=" icon-add"></i>
                                Add New
                            </a>
                        </div>
                    </div>
                    <form method="post" action="{{ route($folder_name.'-view') }} ">
                        @csrf
                        <table id="" class="table table-bordered table-hover" style="width:100%;">
                            <thead>
                                <tr>
                                    <th width=" 10px">#</th>
                                    <th class="col">Tag</th>
                                    <th class="col">Restricterd</th>
                                    <th class="col">Status</th>
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
                                        <td> {{ $result->name }}</td>
                                        {{-- <td> {{ $result->tranportal_id }}</td>
                                        <td> {{ $result->tranportal_password }}</td>
                                        <td> {{ $result->resource_key }}</td> --}}
                                        <td>
                                            @if ($result->restricted)
                                                <span class="badge p-2 badge-danger">Restricterd</span>
                                            @else
                                                <span class="badge p-2 badge-success">Not Restricterd</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($result->status)
                                                <span class="badge p-2 badge-success">Enable</span>
                                            @else
                                                <span class="badge p-2 badge-danger">Disable</span>
                                            @endif
                                        </td>
                                        <td>
                                            @can('tags-update')
                                                <a class="btn-primary btn-sm btn cursor-pointer"
                                                   href="{{ route($folder_name.'-edit', ['id' => $result->e_id]) }}"> <i
                                                        class="icon-edit"></i> </a>
                                            @endcan
                                            @can('tags-draft')
                                                <a class="btn-info btn-sm btn cursor-pointer"
                                                   href="{{ route($folder_name.'-mark-draft', ['id' => $result->e_id]) }}"> <i
                                                        class="icon-drafts"></i> </a>
                                            @endcan
                                            @can('tags-goto_live')
                                                <a class="btn-success btn-sm btn cursor-pointer"
                                                   href="{{ route($folder_name.'-goto-live', ['id' => $result->e_id]) }}"> <i
                                                        class="icon-check"></i> </a>
                                            @endcan
                                            @can('tags-archive')
                                                <a class="btn-danger btn-sm btn cursor-pointer"
                                                   href="{{ route($folder_name.'-tagArchive', ['id' => $result->e_id]) }}"> <i
                                                        class="icon-trash"></i> </a>
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
