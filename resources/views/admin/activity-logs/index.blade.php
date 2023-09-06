@extends('layouts.admin.app')
@section('page_header')
All {{ Str::plural($module_name) }}
@endsection
@section('content')
<div class="container my-3">
    <div class="card p-3">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex align-items-center justify-content-between rounded border border-dark p-3 mb-4">
                    <h4 class="mb-0">Activity Logs</h4>
                </div>
                <div class="d-flex align-items-center justify-content-between border-bottom border-dark mb-3">
                    <h5 class="">Filter</h5>
                    <span class="icon icon-filter"></span>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search_name">Name</label>
                        <input type="text" class="form-control" name="search_name" id="search_name" value="" />
                        @error('search_name')
                        <div class="validation-error"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="search_name">Email</label>
                        <input type="email" class="form-control" name="search_email" id="search_email" value="" />
                        @error('search_email')
                        <div class="validation-error"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="search_module">Module</label>
                        <input type="text" class="form-control" name="search_module" id="search_module" value="" />
                        @error('search_module')
                        <div class="validation-error"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="search_event">Event</label>
                        <select class="form-control" id="search_event" name="search_event">
                            <option value=""> Please Select </option>
                            <option value="add"> Add </option>
                            <option value="update"> Update </option>
                            <option value="delete"> Delete </option>
                        </select>
                        @error('search_event')
                        <div class="validation-error"> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
{{--                <form method="post" action="{{ route($folder_name . '--view') }} ">--}}
                    @csrf
                    <table id="yajra" class="table table-bordered table-hover data-tables" style="width:100%;">
                        <thead>
                        <tr>
                            <th width=" 10px">#</th>
                            <th class="">Subject</th>
                            <th class="">Email (ID/Name/Role)</th>
                            <th class="">Module</th>
                            <th class="">Table</th>
                            <th class="">IP Address</th>
                            <th class="">Method</th>
                            <th width="150px">Action</th>
                        </tr>
                        </thead>
                        <tbody class="">

                        </tbody>
                    </table>

                </form>
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
    })
    $('#search_email').change(function() {
        search_start();
    })
    $('#search_module').change(function() {
        search_start();
    })
    $('#search_event').change(function() {
        search_start();
    })

    function search_start() {
        if ($.fn.DataTable.isDataTable("#yajra")) $("#yajra").DataTable().destroy();
        //Start getting filters

        let name = $('#search_name').val();
        let email = $('#search_email').val();
        let module = $('#search_module').val();
        let event = $('#search_event').val();

        $('#yajra').DataTable({
            "scrollX": true
            , searching: false
            , lengthChange: false
            , processing: true
            , serverSide: true
            , ajax: {
                url: "{{ route('activity-logs-fetch') }}"
                , type: "GET"
                , contentType: "application/x-www-form-urlencoded; charset=UTF-8"
                , data: {
                    filter: "{{ app('request')->input('q') }}"
                    , name: name
                    , email: email
                    , module: module
                    , event: event
                }
            }
            , createdRow: function(row, data, dataIndex) {}
            , columns: [
                {
                    data: 'id'
                    , render: function(data, type, row, index) {
                        return index.row + 1;
                    }
                }
                , {
                    data: 'subject'
                }
                , {
                    render: function(data, type, row, index) {
                        return row.user ? row.user.email + "<br>" + row.user.name + " ("+ row.user.id +")<br>"+row.user.role.name : "-";
                    }
                }, {
                    data: 'module'
                }, {
                    data: 'table'
                }, {
                    data: 'ip'
                }
                , {
                    data: 'method'
                    , render: function(data, type, row) {
                        if (row.method == 'get' || row.method == 'GET') {
                            return '<span class="badge p-2 badge-success">GET</span>';
                        } else if (row.method == 'post' || row.method == 'POST') {
                            return '<span class="badge p-2 badge-danger">POST</span>';
                        }
                    }
                }
                , {
                    data: 'id'
                    , render: function(data, type, row) {
                        var render_action_button = '';
                        let e_url = ("{{ route('activity-logs-view', ['id' => '-id-']) }}").replace('-id-'
                            , row.id);
                        render_action_button += `
                                <a class="btn-primary btn-sm btn cursor-pointer" href="${e_url}"> <i class="icon-eye"></i></a>
                            `;
                        return render_action_button;
                    }
                }
                , ]
            , "drawCallback": function(settings) {}
        });
    }


    $('#yajra_filter').children().children().val("{{ app('request')->input('search') }}")
    $('#yajra_filter').children().children().trigger("keyup");

    function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).data('text')).select();
        document.execCommand("copy");
        $temp.remove();
    }
    var $disabledResults = $(".js-example-disabled-results");
    $disabledResults.select2();
</script>
@endpush
