@extends('layouts.admin.app',['title'=>'Roles - '])

@push('css')

@endpush
@section('page_header') Roles @endsection
@section('content')



    <div class="content-wrapper animatedParent animateOnce">
        <div class="container">
            <section class="paper-card">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="_box">
                            <div class="box-header with-border">
                                <div class="row">
                                    @can('rights-management-add')
                                    <div class="col-md-12 my-3 text-right">
                                        <a href="{{ route('role.add') }}" class="cst_btn-outline btn-sm mb-2">
                                            <i class=" icon-add"></i>
                                            Add New
                                        </a>
                                    </div>
                                    @endcan
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th style="width: 10px">ID#</th>
                                            <th>Role</th>
                                            <th>Assigned To</th>
                                            <th style="width: 40px">Action</th>
                                        </tr>
                                        </tr>
                                        @foreach ($roles as $item)
                                            <tr>
                                                <th scope="row">{{ $item->id }}</th>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->users->count() }} user(s)</td>
                                                <td>
                                                    @can('rights-management-update')
                                                        @if ($item->slug != 'superadmin')
                                                            <a href="{{ route('roles.edit', ['role' => $item->id]) }}">
                                                                <button type="button" class="cst_btn-outline btn-sm">
                                                                    <i class="text-bold icon-document-edit"></i></button>
                                                            </a>
                                                        @endif
                                                    @endcan
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
            </section>
        </div>
    </div>


    {{-- <div class="page bg-light height-full">
        <div class="container my-3">




            <div class="row">
                <div class="col-md-12">



                    <div class="card-box">
                        <div class="row d-flex justify-content-between ">
                            <h4 class="m-0 header-title">Roles</h4>
                            <a href="{{ route('role.add') }}" class="btn btn-sm btn-outline-success">
                                Add New
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>


                        <p class="text-muted font-14 mb-3"> </p>

                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>ID#</th>
                                        <th>Title</th>
                                        <th>Assigned to</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($roles as $item)

                                        <tr>
                                            <th scope="row">{{ $item->id }}</th>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->users->count() }} user(s)</td>
                                            <td>
                                                <a href="{{ route('roles.edit', ['role' => $item->id]) }}">
                                                    <button type="button" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i></button>
                                                </a>
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div> --}}


@endsection


@push('scripts')



@endpush
