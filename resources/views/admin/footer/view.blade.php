@extends('layouts.admin.app')
@php
$lang_id = request()->route()->parameters['lang_id'];
($language = App\Models\Language::find(decrypt($lang_id)));
@endphp
@section('page_header')
All {{ Str::plural($module_name) . " (".$language->name.")"}}
@endsection
@section('content')
<div class="container my-3">
    <div class="card p-3">
        <div class="row">
            <div class="col-md-12">
                <div class="box-header with-border">
                    <div class="d-flex justify-content-start">
                        <a href="{{ route($folder_name . '-add',['lang_id' => $lang_id]) }}" class="btn-success btn-sm btn mb-2 cursor-pointer">
                            <i class=" icon-add"></i>
                            Add New
                        </a>
                    </div>
                </div>
                <form method="post" action="{{ route($folder_name.'-update_sequence') }} ">
                    @csrf
                    <table id="" class="table table-bordered table-hover" style="width:100%;">
                        <thead>
                            <tr>
                                <th width=" 10px">#</th>
                                <th class="">Name</th>
                                <th class="">Status</th>
                                <th width="150px">Action</th>
                            </tr>
                        </thead>
                        <tbody class="sortable">

                            @foreach ($result as $key => $result)
                            <tr>
                                <td>
                                    <input type="hidden" name="sequence[]" value="{{ $result->id }}">
                                    {{ $key + 1 }}
                                </td>
                                <td> {{ $result->name }}</td>
                                <td>
                                    @if ($result->status)
                                    <span class="badge p-2 badge-success">Enable</span>
                                    @else
                                    <span class="badge p-2 badge-danger">Disable</span>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn-primary btn-sm btn cursor-pointer" href="{{ route($folder_name.'-edit', ['id' => $result->e_id, 'lang_id' => $lang_id]) }}"> <i class="icon-edit"></i> </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-sm mb-2 btn-outline-success">
                        <i class="icon-refresh"></i>
                        Update sequence
                    </button>
                    {{-- <button type="submit" class="btn btn-sm mb-2 btn-success">
                            Update sequence
                        </button> --}}
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
