@extends('layouts.admin.app')
@section('page_header')
    {{Str::singular($module_name)}}
@endsection
@section('content')
    <div class="container my-3">
        <div class="card p-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="box-header with-border">
                        @can('ads-management-add')
                        <div class="d-flex justify-content-start">
                            <a href="{{ route('doctor-earning-add') }}"
                               class="btn-success btn-sm btn mb-2 cursor-pointer">
                                <i class=" icon-add"></i>
                                Add New
                            </a>
                        </div>
                        @endcan
                    </div>
                    @can('ads-management-view')
                    {{-- <form method="post" action="{{ route($folder_name.'-view') }} "> --}}
                        @csrf
                        <table id="" class="table table-bordered table-hover" style="width:100%;">
                            <thead>
                                <tr>
                                    <th width=" 10px">Consult Type </th>
                                    <th class="col">Price Set</th>
                                    <th width="150px">Discounted Price</th>
                                    <th width="150px">MS Platform Fee</th>
                                    <th width="150px">Penalty Charges</th>
                                    <th width="150px">Action</th>
                                </tr>
                            </thead>

                            <tbody class="">
                                    <tr>
                                        @if(!empty($result['instantfee'] ))
                                        <td>{{ $result['instantname'] }}</td>

                                        <td> RS: {{ $result['instantfee'] }}</td>
                                        {{-- <td> {{ $result->tranportal_id }}</td>
                                        <td> {{ $result->tranportal_password }}</td>
                                        <td> {{ $result->resource_key }}</td> --}}
                                        <td>RS: {{$result['instantdisfees']}}</td>
                                        <td>{{$result['instantmscommission']}}%</td>
                                        <td>{{$result['instantpenalty']}}%</td>
                                        <td>
                                            <a href="{{route('doctor-earning-delete',$instantId)}}" class="btn btn-outline-primary btn-sm detail"><i class="icon-trash"></i></a>
                                            <a href="{{route('doctor-earning-edit',$instantId)}}" class="btn btn-outline-primary btn-sm detail"><i class="icon-pencil"></i></a>
                                        </td>
                                        @endif

                                        @can('ads-management-update')
                                        <td>
                                            {{-- <a class="btn-primary btn-sm btn cursor-pointer"
                                                href="{{ route($folder_name.'-edit', ['id' => $result->e_id]) }}"> <i
                                                    class="icon-edit"></i> </a> --}}
                                        </td>
                                        @endcan
                                    </tr>
                                    <tr>
                                        @if(!empty($scheduled['scheduledfee'] ))
                                            <td>{{ $scheduled['scheduledname'] }}</td>

                                            <td> RS: {{ $scheduled['scheduledfee'] }}</td>
                                            {{-- <td> {{ $result->tranportal_id }}</td>
                                            <td> {{ $result->tranportal_password }}</td>
                                            <td> {{ $result->resource_key }}</td> --}}
                                            <td>RS: {{$scheduled['scheduleddisfees']}}</td>
                                            <td>{{$scheduled['scheduledmscommission']}}%</td>
                                            <td>{{$scheduled['scheduledpenalty']}}%</td>
                                            <td>
                                                <a href="{{route('doctor-earning-delete',$scheduledId)}}" class="btn btn-outline-primary btn-sm detail"><i class="icon-trash"></i></a>
                                                <a href="{{route('doctor-earning-edit',$scheduledId)}}" class="btn btn-outline-primary btn-sm detail"><i class="icon-eye"></i></a>
                                            </td>
                                        @endif
                                        @can('ads-management-update')
                                            <td>
                                                {{-- <a class="btn-primary btn-sm btn cursor-pointer"
                                                    href="{{ route($folder_name.'-edit', ['id' => $result->e_id]) }}"> <i
                                                        class="icon-edit"></i> </a> --}}
                                            </td>
                                        @endcan
                                    </tr>
                            </tbody>
                        </table>

                        {{-- <button type="submit" class="btn btn-sm mb-2 btn-success">
                            Update sequence
                        </button> --}}
                    </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
