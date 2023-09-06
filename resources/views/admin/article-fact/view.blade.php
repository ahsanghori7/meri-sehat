@extends('layouts.admin.app')
@section('page_header')
    All {{Str::plural($module_name)}}
@endsection
@php
    $colors = [
        '#FF0000' => 'Red',
        '#72d54a' => 'Green',
        '#28bcc1' => 'Sky Blue',
        '#f5d730' => 'Yellow',
        '#ef6286' => 'Pink',
        '#bef5f1' => 'Light Blue',
        '#e9eaef' => 'Bright Gray',
        '#E9F5F1' => 'Clear Day',
        '#F7EEE9' => 'Hint Of Red',
        '#E9EAEF' => 'Solitude',
    ];
@endphp
@section('content')
    <div class="container my-3">
        <div class="card p-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="box-header with-border">
                        <div class="d-flex justify-content-start">
                            @can('article-fact-add')
                            <a href="{{ route($folder_name.'-add') }}"
                                class="btn-success btn-sm btn mb-2 cursor-pointer">
                                <i class=" icon-add"></i>
                                Add New
                            </a>
                            @endcan
                        </div>
                    </div>
                    <form method="post">
                        @csrf
                        <table id="" class="table table-bordered table-hover" style="width:100%;">
                            <thead>
                                <tr>
                                    <th width=" 10px">#</th>
                                    <th class="">Name</th>
                                    <th class="">Color</th>
                                    <th class="">Status</th>
                                    <th width="150px">Action</th>
                                </tr>
                            </thead>
                            <tbody class="">

                                @foreach ($result as $key => $result)
                                    <tr>
                                        <td>
                                            {{ $key + 1 }}
                                        </td>
                                        <td> {{ $result->name }}</td>
                                        <td> {{ $colors[$result->color] }}</td>
                                        <td>
                                            @if ($result->status)
                                                <span class="badge p-2 badge-success">Enable</span>
                                            @else
                                                <span class="badge p-2 badge-danger">Disable</span>
                                            @endif
                                        </td>
                                        <td>
                                            @can('article-fact-update')
                                            <a class="btn-primary btn-sm btn cursor-pointer"
                                                href="{{ route($folder_name.'-edit', ['id' => $result->e_id]) }}"> <i
                                                    class="icon-edit"></i> </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
