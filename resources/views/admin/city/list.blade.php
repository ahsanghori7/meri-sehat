@extends('layouts.admin.app')
@section('page_header')
    All {{ Str::plural('city') }}
@endsection
@section('content')
    <div class="container-fluid relative animatedParent animateOnce">
        <div class="tab-content pb-3" id="v-pills-tabContent">
            <!--Today Tab Start-->
            <form method="POST">
                @csrf
                <div class="row p-t-b-10 ">
                    <div class="col-md-8 mx-auto">
                        <div class="card">
                            <div class="card-body">
                                @if (session('success'))
                                    <div class="toast" data-title="Saved" data-message="{{ session('success') }}"
                                        data-type="success">
                                    </div>
                                @endif
                                @if (session('warning'))
                                    <div class="toast" data-title="Warning" data-message="{{ session('warning') }}"
                                        data-type="warning">
                                    </div>
                                @endif
                                <table id="sites_list_table" class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cities as $city)
                                            <tr>
                                                <td>{{ $city->id }}</td>
                                                <td>{{ $city->name }}</td>
                                                <td>
                                                    @if ($city->status == 1)
                                                        <span class="badge badge-pill badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-pill badge-danger">Disable</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a class="btn btn-outline-success btn-xs"
                                                        href="/admin/city/{{ $city->id }}/edit">Edit</a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!--Yesterday Tab Start-->
        </div>
    </div>
    </div>
    {{-- page body end here --}}
    </div>
@endsection
