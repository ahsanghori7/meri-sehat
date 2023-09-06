@extends('layouts.admin.app')
@section('page_header')All Admins @endsection
@section('content')

    <div class="container my-3">
        <div class="card p-3">
            <div class="row">
                <div class="col-md-12">
                    @can('admin-roles-add')
                    <div class="box-header with-border">
                        <div class="row pr-4 justify-content-end ">
                            <a href="{{ route('admin_users-add') }}" class="cst_btn-outline btn-sm mb-2">
                                <i class=" icon-add"></i>
                                Add New
                            </a>
                        </div>
                    </div>
                    @endcan
                    @can('admin-roles-filter')
                        <div class="d-flex align-items-center justify-content-between border-bottom border-dark mb-3">
                            <h5 class="">Filter</h5>
                            <span class="icon icon-filter"></span>
                        </div>
                        <div class="row">

                            @can('admin-roles-filter-name')
                                <div class="col-md-3 mb-3">
                                    <label for="search_name">Name</label>
                                    <input type="text" class="form-control searchable_field" name="search_name" id="search_name" value="" />
                                    @error('search_name')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                            @endcan

                            @can('admin-roles-filter-phone')
                                <div class="col-md-2 mb-3">
                                    <label for="search_phone">Phone</label>
                                    <input type="text" class="form-control searchable_field" name="search_phone" id="search_phone" value="" />
                                    @error('search_phone')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                            @endcan

                            @can('admin-roles-filter-email')
                                <div class="col-md-3 mb-3">
                                    <label for="search_email">Email</label>
                                    <input type="text" class="form-control searchable_field" name="search_email" id="search_email" value="" />
                                    @error('search_email')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                            @endcan

                            @can('admin-roles-filter-role')
                                <div class="col-md-2 mb-3">
                                    <label for="search_role">Roles</label>
                                    <select class="form-control searchable_field" id="search_role" name="search_role">
                                        <option value=""> Please Select </option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}"> {{ $role->name }} </option>
                                        @endforeach
                                    </select>
                                    @error('search_role')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                            @endcan

                            @can('admin-roles-filter-status')
                                <div class="col-md-2 mb-3">
                                    <label for="search_status">Status</label>
                                    <select class="form-control searchable_field" id="search_status" name="search_status">
                                        <option value=""> Please Select </option>
                                        <option value="1"> Enable </option>
                                        <option value="0"> Disable </option>
                                    </select>
                                    @error('search_status')
                                    <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                            @endcan

                        </div>
                    @endcan
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

        function search_start() {
            if ($.fn.DataTable.isDataTable("#yajra")) $("#yajra").DataTable().destroy();
            //Start getting filters

            let verified = $('#search_verified').val();
            let status = $('#search_status').val();

            $('#yajra').DataTable({
                "scrollX": true
                , processing: true
                , serverSide: true
                , lengthChange: false
                , searching: false
                , ajax: {
                    url: "{{ route('admin_users-fetch') }}",
                    data: function(d) {
                        d.search_name = $("#search_name").val();
                        d.search_phone = $("#search_phone").val();
                        d.search_email = $("#search_email").val();
                        d.search_role = $("#search_role").val();
                        d.search_status = $("#search_status").val();
                    }
                },
                createdRow: function(row, data, dataIndex) {
                    // $(row).attr('class', 'data-row click_detail');
                    // $(row).attr('data-id', data.id);
                    // console.log(row,data);
                },
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
                            let e_url = ("{{ route('admin_users-edit', ['id' => '-id-']) }}").replace('-id-',row.e_id);
                            let d_url = ("").replace('-id-', row.e_id);
                            return `<a class="cst_btn-outline btn-sm cursor-pointer" href="${e_url}"> <i class="icon-edit"></i> </a>`;
                        }
                    },

                ],
                "drawCallback": function(settings) {
                    //   $('#search-result-div').show();
                    //   $('#search-overlay').hide();
                    //   initShowDetailsMethod();
                } //end drawcallback
            });
        }


        $('#yajra_filter').children().children().val("{{ app('request')->input('search') }}")
        $('#yajra_filter').children().children().trigger("keyup");

        $(document).on('change', '.searchable_field', function(){
            search_start();
        });

    </script>
@endpush
