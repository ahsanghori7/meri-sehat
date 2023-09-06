@extends('layouts.admin.app')
@section('page_header')
All {{ Str::plural($module_name) }}
@endsection
@section('content')
<div class="container my-3">
    <div class="">
        <div class="row">

            <div class="card p-3 col-md-12 mb-4">
                @can('reporting-sehat-scan-report-filter')
                <div class="box-header with-border">
                    <h5 class="ml-3">Filter <span class="icon icon-filter"></span></h5>
                    <form>
                        <div class="d-flex justify-content-start">
                            @can('reporting-sehat-scan-report-filter-start_date')
                            <div class="col-md-4 mb-3">
                                <label for="start_date">Start Date</label>
                                <input type="date" id="start_date" name="start_date" class="form-control">
                                {{-- @error('start_date') --}}
                                <div class="text-danger start_date_error" style="display: none">Start Date Cannot be greater than End Date</div>
                                {{-- @enderror --}}
                            </div>
                            @endcan
                            @can('reporting-sehat-scan-report-filter-end_date')
                            <div class="col-md-4 mb-3">
                                <label for="end_date">End Date</label>
                                <input type="date" id="end_date" name="end_date" class="form-control">
                            </div>
                            @endcan
                            <div class="col-md-4 mb-3">
                                <label for="search_language">Actions:</label>
                                <br>
                                @can('reporting-sehat-scan-report-filter-start_date')
                                {{-- <input type="submit" name="action" class="btn-success btn-sm btn mb-2 cursor-pointer" value="fetch" > --}}
                                <button type="button" id="fetch"  class="btn-success btn-sm btn mb-2 cursor-pointer">
                                    <i class="icon-search4"></i>
                                    Fetch Report
                                </button>
                                @endcan
                                @can('reporting-sehat-scan-report-download_report')
                                {{-- <button type="button" id="download" name="action" class="btn-info btn-sm btn mb-2 cursor-pointer">
                                    <i class="icon-get_app"></i>
                                    Download Report
                                </button> --}}
                                @endcan
                                @can('reporting-sehat-scan-report-filter-start_date')
                                <a href="{{route('report-sehat_scan_report')}}" class="btn-warning btn-sm btn mb-2 cursor-pointer">
                                    &times;
                                    Clear
                                </a>
                                @endcan
                                @can('reporting-sehat-scan-report-download_report')
                                {{-- <a href="#" class="btn-info btn-sm btn mb-2 cursor-pointer">
                                    <i class="icon-get_app"></i>
                                    Download Report
                                </a> --}}
                                @endcan
                            </div>
                        </div>
                    </form>
                </div>
                @endcan
            </div>
            <div class="card p-3 col-md-12">
                {{-- <div class="box-header with-border">
                    <div class="d-flex justify-content-start">
                        <a href="{{ route($folder_name . '-add') }}" class="btn-success btn-sm btn mb-2 cursor-pointer">
                <i class=" icon-add"></i>
                Add New
                </a>
            </div>
        </div> --}}
        <form method="post">
            @csrf
            {{-- <strong class="text-right d-block">Total Records : {{$result->count()}}</strong> --}}
            <br>
            <table id="yajra" class="table table-bordered table-hover data-tables" style="width:100%;">
                <thead>
                    <tr>
                        <th width=" 10px">#</th>
                        <th class="">User</th>
                        <th class="">IP-Address</th>
                        <th class="">Blood Pressure</th>
                        <th class="">Stress Level</th>
                        <th class="">Respiratory Rate</th>
                        <th class="">SPO2</th>
                        <th class="">Heart Rate</th>
                        <th class="">SDNN</th>
                        <th class="">BMI</th>
                    </tr>
                </thead>
                <tbody class="">
                    {{-- @foreach ($result as $key => $result)
                    <tr>
                        <td widtd=" 10px">{{$key+1}}</td>
                        <td class="">{{$result->user->name ??  "UnNamed"}}</td>
                        <td class="">{{$result->blood_pressure ?? "-"}}</td>
                        <td class="">{{$result->stress_level ?? "-"}}</td>
                        <td class="">{{$result->respiratory_rate ?? "-"}}</td>
                        <td class="">{{$result->spo2 ?? "-"}}</td>
                        <td class="">{{$result->heart_rate ?? "-"}}</td>
                        <td class="">{{$result->sdnn ?? "-"}}</td>
                        <td class="">{{$result->bmi ?? "-"}}</td>
                    </tr>
                    @endforeach --}}
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

    $('#fetch').click(function() {
        search_start('fetch');
    })
    $('#download').click(function() {
        search_start('download');
    })
    function search_start(action = 'fetch') {
        if ($.fn.DataTable.isDataTable("#yajra")) $("#yajra").DataTable().destroy();
        //Start getting filters

        $('#yajra').DataTable({
            "scrollX": true
            , processing: true
            , serverSide: true
            , ajax: {
                url: "{{ route('report-sehat_scan_report-fetch') }}"
                , type: "GET"
                , data: {
                    action,
                    filter: "{{ app('request')->input('q') }}"
                , }
            }
            , createdRow: function(row, data, dataIndex) {}
            , columns: [{render: function(data, type, row, index) {
                        return index.row + 1;
                    }
                }
                , {
                    render: function(data, type, row, index) {
                        if(row.user != null){
                            return row.user.name ?? 'Unnamed User'
                        }else{
                            return "Guest"
                        }
                    }
                }
                , {
                    data: 'ip_address'
                }
                , {
                    data: 'blood_pressure'
                }
                , {
                    data: 'blood_pressure'
                }
                , {
                    data: 'blood_pressure'
                }
                , {
                    data: 'blood_pressure'
                }
                , {
                    data: 'blood_pressure'
                }
                , {
                    data: 'blood_pressure'
                }
                , {
                    data: 'blood_pressure'
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


    $(document).ready(function() {

$('#start_date').change(function() {
    validateDate();
})
$('#end_date').change(function() {
    validateDate();
})
window.validateDate = function() {
    var start_date = new Date($('#start_date').val());
    var end_date = new Date($('#end_date').val());

    if (start_date > end_date) {
        $('.start_date_error').show()
    } else {
        $('.start_date_error').hide()
    }
}

})

</script>
@endpush
