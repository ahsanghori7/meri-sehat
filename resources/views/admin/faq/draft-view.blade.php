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
                            @can('faq-add')
                            <a href="{{ route($folder_name.'-draft-add') }}"
                                class="btn-success btn-sm btn mb-2 cursor-pointer">
                                <i class=" icon-add"></i>
                                Add New
                            </a>
                            @endcan
                        </div>
                    </div>
                    <form method="post" action="{{ route($folder_name.'-view') }} ">
                        @csrf
                        <table id="" class="table table-bordered table-hover" style="width:100%;">
                            <thead>
                                <tr>
                                    <th width="10%">#</th>
                                    <th width="60%">Question</th>
                                    <th width="10%">Status</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>
                            <tbody class="">

                                @foreach ($result as $key => $result)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="sequence[]" value="{{ $result->id }}">
                                            {{ $key + 1 }}
                                        </td>
                                        <td> {{ $result->question }}</td>
                                        {{-- <td> {{ $result->tranportal_id }}</td>
                                        <td> {{ $result->tranportal_password }}</td>
                                        <td> {{ $result->resource_key }}</td> --}}
                                        <td>
                                            @if ($result->status)
                                                <span class="badge p-2 badge-success">Enable</span>
                                            @else
                                                <span class="badge p-2 badge-danger">Disable</span>
                                            @endif
                                        </td>
                                        <td>
                                            @can('faq-update')
                                                <a class="btn-primary btn-sm btn cursor-pointer" title="Edit this faq"
                                                   href="{{ route($folder_name.'-edit', ['id' => $result->e_id]) }}"> <i
                                                        class="icon-edit"></i> </a>
                                            @endcan
                                            @can('faq-goto_live')
                                                <a class="btn-success btn-sm btn cursor-pointer" title="Goto live this faq"
                                                   href="{{ route($folder_name.'-goto-live', ['id' => $result->e_id]) }}"> <i
                                                        class="icon-check"></i> </a>
                                            @endcan
                                            @can('faq-archive')
                                                <a class="btn-danger btn-sm btn cursor-pointer" title="Archive this faq"
                                                   href="{{ route($folder_name.'-faqArchive', ['id' => $result->e_id]) }}"> <i
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
