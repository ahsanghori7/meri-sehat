@extends('layouts.admin.app')
@section('page_header')
    All Patients
@endsection
@section('content')
    <div class="container my-3">
        <div class="card p-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="box-header with-border">
                        {{-- <div class="row pr-4 justify-content-end ">
                            <a href="{{ route('admin_users-add') }}" class="cst_btn-outline btn-sm mb-2">
                                <i class=" icon-add"></i>
                                Add New
                            </a>
                        </div> --}}
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center justify-content-between rounded border border-dark p-3 mb-4">
                                <h4 class="mb-0">Patients</h4>
                            </div>
                            @can('patients-management-filter')
                                <div class="d-flex align-items-center justify-content-between border-bottom border-dark mb-3">
                                    <h5 class="">Filter</h5>
                                    <span class="icon icon-filter"></span>
                                </div>

                                <div class="row">
                                    @can('patients-management-filter-name')
                                        <div class="col-md-2 mb-3">
                                            <label for="search_name">Name</label>
                                            <input type="text" class="form-control" name="search_name" id="search_name" value="" />
                                            @error('search_name')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endcan
                                    @can('patients-management-filter-phone')
                                        <div class="col-md-2 mb-3">
                                            <label for="search_name">Phone Number</label>
                                            <input type="email" class="form-control" name="search_phone" id="search_phone" value="" />
                                            @error('search_phone')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endcan
                                    @can('patients-management-filter-gender')
                                        <div class="col-md-2 mb-3">
                                            <label for="search_gender">Gender</label>
                                            <select class="form-control js-example-disabled-results" id="search_gender" name="search_gender">
                                                <option value=""> Select Gender </option>
                                                <option value="male"> Male </option>
                                                <option value="female"> Female </option>
                                                <option value="others"> Others </option>
                                            </select>
                                            @error('search_gender')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endcan
                                    @can('patients-management-filter-location')
                                        <div class="col-md-2 mb-3">
                                            <label for="search_location">Location</label>
                                            <select class="form-control js-example-disabled-results" id="search_location" name="search_location">
                                                <option value=""> Select Location </option>
                                                @foreach ($locations as $location)
                                                    <option value="{{ $location->id }}">
                                                        {{ $location->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('search_location')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endcan
                                    @can('patients-management-filter-user_type')
                                        <div class="col-md-2 mb-3">
                                            <label for="search_user_type">User Type</label>
                                            <select class="form-control" id="search_user_type" name="search_user_type">
                                                <option value=""> Select User Type </option>
                                                <option value="1"> Subscribed </option>
                                                <option value="0"> Unsubscribed </option>
                                            </select>
                                            @error('search_user_type')
                                            {{--                        <div class="validation-error"> {{ $message }}</div>--}}
                                            @enderror
                                        </div>
                                    @endcan
                                    @can('patients-management-filter-status')
                                        <div class="col-md-2 mb-3">
                                            <label for="search_status">Status</label>
                                            <select class="form-control" id="search_status" name="search_status">
                                                <option value=""> Select Status </option>
                                                <option value="1"> Active </option>
                                                <option value="0"> Inactive </option>
                                            </select>
                                            @error('search_status')
                                            {{--                        <div class="validation-error"> {{ $message }}</div>--}}
                                            @enderror
                                        </div>
                                    @endcan
                                </div>
                            @endcan
                        </div>
                    </div>
                    <table id="yajra" class="table table-bordered table-hover data-tables" style="width:100%;">
                        <thead>
                            <tr>
                                <th width="30%">Name</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Location</th>
                                <th>User Type</th>
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
        $('#search_name').change(function() {
            search_start();
        });
        $('#search_phone').change(function() {
            search_start();
        });
        $('#search_gender').change(function() {
            search_start();
        });
        $('#search_location').change(function() {
            search_start();
        });
        $('#search_user_type').change(function() {
            search_start();
        });
        $('#search_status').change(function() {
            search_start();
        });
        function search_start() {
            if ($.fn.DataTable.isDataTable("#yajra")) $("#yajra").DataTable().destroy();
            //Start getting filters

            let name = $('#search_name').val();
            let phone = $('#search_phone').val();
            let gender = $('#search_gender').val();
            let location = $('#search_location').val();
            let user_type = $('#search_user_type').val();
            let status = $('#search_status').val();

            $('#yajra').DataTable({
                "scrollX": true,
                processing: true,
                serverSide: true,
                searching: false,
                lengthChange: false,
                ajax: {
                    url: "{{ route('patient-fetch') }}",
                    type: "GET",
                    data: {
                        filter: "{{ app('request')->input('q') }}",
                        name,
                        phone,
                        gender,
                        location,
                        user_type,
                        status
                    }
                },
                createdRow: function(row, data, dataIndex) {},
                columns: [{
                        render: function(data, type, row, index) {
                            return " <img style='width:50px; height:40px;' src='" + row.image_url + "' ><span class='pl-3'>"+row.name+"</span>";
                        }
                    },
                    {
                        data: 'phone'
                    },
                    {
                        data: 'gender'
                    },
                    {
                        render: function(data, type, row, index) {
                            return row.city ? row.city.name : "-";
                        }
                    },
                    {
                        data: 'is_subscribed',
                        render: function(data, type, row) {
                            if (row.is_subscribed == 1) {
                                return 'Subscribed';
                            } else {
                                return 'Unsubscribed';
                            }
                        }
                    },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            if (row.status == 1) {
                                return '<span class="badge p-2 badge-success">Active</span>';
                            } else {
                                return '<span class="badge p-2 badge-danger">Inactive</span>';
                            }
                        }
                    },
                    {
                        data: 'id'
                        , render: function(data, type, row) {
                            var render_action_button = '';
                            @can("patients-management-update")
                            let e_url = ("{{ route('patient-edit', ['id' => '-id-']) }}").replace('-id-'
                                , row.e_id);
                            let slugs = 'doctor/' + row.slug;
                            slugs = "{{env('APP_URL')}}" + slugs;
                            render_action_button += `
                                <a class="btn-primary btn-sm btn cursor-pointer" href="${e_url}"> <i class="icon-edit"></i></a>
                            `;
                            @endcan

                            @can("patients-management-delete")
                            let delete_url = ("{{ route('patient-delete', ['id' => '-id-']) }}").replace('-id-'
                                , row.e_id);
                            render_action_button += '<a class="btn-primary btn-danger btn-sm btn cursor-pointer delete" data-id="'+row.e_id+'"> <i class="icon-trash"></i></a>';
                            @endcan
                                return render_action_button;
                        }
                    },
                ],
                "drawCallback": function(settings) { } //end drawcallback
            });
        }
        $('#yajra_filter').children().children().val("{{ app('request')->input('search') }}")
        $('#yajra_filter').children().children().trigger("keyup");

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        });

        $(document).on('click', '.delete', function(e) {
            swalWithBootstrapButtons.fire(
                'Coming Soon',
                '',
                'success'
            );
        });
    </script>
@endpush
