@extends('layouts.admin.app')
@section('page_header')
    All Users
@endsection
@section('content')
    <div class="container my-3">
        <div class="card p-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="box-header with-border">
                        @can('user-management-add')
                        {{-- <div class="row pr-4 justify-content-end ">
                            <a href="{{ route('admin_users-add') }}" class="cst_btn-outline btn-sm mb-2">
                                <i class=" icon-add"></i>
                                Add New
                            </a>
                        </div> --}}
                        @endcan
                    </div>
                    <div class="row">
                        @can('user-management-filter')
                            @can('user-management-filter-bought')
                            <div class="col-md-4 mb-3">
                                <label for="search_subscribed">Bought Subscription</label>
                                <select class="form-control" id="search_subscribed" name="search_subscribed">
                                    <option value=""> Select Status </option>
                                    </option>
                                    <option value="1"> Paid </option>
                                    </option>
                                    <option value="0"> Un-Paid </option>
                                    </option>
                                </select>
                                @error('search_subscribed')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            @endcan

                            @can('user-management-filter-newsletter_subscription')
                            <div class="col-md-4 mb-3">
                                <label for="search_newsletter_subscribed">Newsletter Subscribed</label>
                                <select class="form-control" id="search_newsletter_subscribed" name="search_newsletter_subscribed">
                                    <option value=""> Select Status </option>
                                    </option>
                                    <option value="1"> Subscribed </option>
                                    </option>
                                    <option value="0"> Not-Subscribed </option>
                                    </option>
                                </select>
                                @error('search_newsletter_subscribed')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            @endcan
                        @endcan
                    </div>
                    <table id="yajra" class="table table-bordered table-hover data-tables" style="width:100%;">
                        <thead>
                            <tr>
                                <th width=" 10px">#</th>
                                <th width="200px">Image</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th width="150px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        search_start();
        $('#search_subscribed').change(function() {
            search_start();
        })
        $('#search_newsletter_subscribed').change(function() {
            search_start();
        })
        function search_start() {
            if ($.fn.DataTable.isDataTable("#yajra")) $("#yajra").DataTable().destroy();
            //Start getting filters

            let subscribed = $('#search_subscribed').val();
            let newsletter_subscribed = $('#search_newsletter_subscribed').val();

            $('#yajra').DataTable({
                "scrollX": true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('user_management-fetch') }}",
                    type: "GET",
                    data: {
                        filter: "{{ app('request')->input('q') }}",
                        subscribed,
                        newsletter_subscribed,
                    }
                },
                createdRow: function(row, data, dataIndex) {},
                columns: [{
                        data: 'id',
                        render: function(data, type, row, index) {
                            return index.row + 1;
                        }
                    },
                    {
                        render: function(data, type, row, index) {
                            return " <img style='width:50px; height:40px;' src='" + row.image_url + "' >";
                        }
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'role.name'
                    },
                    {
                        data: 'phone'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            if (row.status == 1) {
                                return '<span class="badge p-2 badge-success">Enable</span>';
                            } else {
                                return '<span class="badge p-2 badge-danger">Disabled</span>';
                            }
                        }
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            // let e_url = ("{{ route('admin_users-edit', ['id' => '-id-']) }}").replace('-id-',
                            //     row.e_id);
                            // let d_url = ("").replace('-id-', row.e_id);
                            return `<a class="cst_btn-outline btn-sm cursor-pointer" href="#"> <i class="icon-eye"></i> </a>`;
                        }
                    },
                ],
                "drawCallback": function(settings) { } //end drawcallback
            });
        }
        $('#yajra_filter').children().children().val("{{ app('request')->input('search') }}")
        $('#yajra_filter').children().children().trigger("keyup");
    </script>
@endpush
