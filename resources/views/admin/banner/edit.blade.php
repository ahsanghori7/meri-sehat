@extends('layouts.admin.app')
@section('page_header')
    All {{ Str::plural('User') }}
@endsection
@section('content')
    <div class="container-fluid relative animatedParent animateOnce">
        <div class="tab-content pb-3" id="v-pills-tabContent">
            <!--Today Tab Start-->
            <table class="table table-bordered mt-3">
                <tbody>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Title</th>
                        <th>link</th>
                        <th>Target Self</th>
                        <th>Parent Id</th>
                        <th>Lang</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>
                            <a href="/admin/banner/100/edit" class="btn btn-success">Update</a>
                            <a class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>
                            <a class="btn btn-success">Update</a>
                            <a class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                    <tr>
                        <td>3.</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>
                            <a class="btn btn-success">Update</a>
                            <a class="btn btn-danger">Delete</a>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
    </div>
    {{-- page body end here --}}
    </div>
@endsection
