@extends('layouts.admin.app')
@php
$lang_id = request()->route()->parameters['lang_id'];
($language = App\Models\Language::find(decrypt($lang_id)));
@endphp
@section('page_header')
All {{ Str::plural($module_name) . " (".$language->name.")"}}
@endsection

@section('content')
<div class="container my-3">
    <div class="card p-3">
        <div class="row">
            <div class="col-md-12">

                @can("menu-management-add")
                <div class="box-header with-border">
                    <div class="d-flex justify-content-start">
                        <a href="{{ route($folder_name . '-add',['lang_id' => $lang_id]) }}" class="btn-success btn-sm btn mb-2 cursor-pointer">
                            <i class=" icon-add"></i>
                            Add New
                        </a>
                    </div>
                </div>
                @endcan

                <form method="post">
                    @csrf
                    <table id="yajra" class="table table-bordered table-hover data-tables" style="width:100%;">
                        <thead>
                            <tr>
                                <th width=" 10px">#</th>
                                <th class="">Name</th>
                                <th class="">Type</th>
                                <th class="">Language</th>
                                <th class="">Status</th>
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
    $('#search_category').change(function() {
        search_start();
    })
    $('#search_status').change(function() {
        search_start();
    })
    $('#search_language').change(function() {
        search_start();
    })

    function search_start() {
        if ($.fn.DataTable.isDataTable("#yajra")) $("#yajra").DataTable().destroy();
        //Start getting filters

        let category = $('#search_category').val();
        let status = $('#search_status').val();
        let language = $('#search_language').val();

        $('#yajra').DataTable({
            "scrollX": true
            , processing: true
            , serverSide: true
            , ajax: {
                url: "{{ route('menu-fetch',['lang_id' => $lang_id]) }}"
                , type: "GET"
                , contentType: "application/x-www-form-urlencoded; charset=UTF-8"
                , data: {
                    filter: "{{ app('request')->input('q') }}"
                    , category: category
                    , status: status
                    , language: language
                , }
            }
            , createdRow: function(row, data, dataIndex) {}
            , columns: [{
                    data: 'id'
                    , render: function(data, type, row, index) {
                        return index.row + 1;
                    }
                }
                , {
                    data: 'name'
                }
                , {
                    render: function(data, type, row) {
                        return ucwords(row.type.replace(/-/gi, " "));
                    }
                }
                , {
                    data: 'language.name'
                }
                , {
                    data: 'status'
                    , render: function(data, type, row) {
                        if (row.status == 1) {
                            return '<span class="badge p-2 badge-success">Enable</span>';
                        } else {
                            return '<span class="badge p-2 badge-danger">Disabled</span>';
                        }
                    }
                }
                , {
                    data: 'id'
                    , render: function(data, type, row) {
                        @can("menu-management-view")
                        let e_url = ("{{ route('menu-edit', ['id' => '-id-','lang_id' => $lang_id]) }}").replace('-id-'
                            , row.e_id);
                        let d_url = ("").replace('-id-', row.e_id);
                        return `<a class="btn-primary btn-sm btn cursor-pointer" href="${e_url}"> <i class="icon-edit"></i></a>`;
                        @else
                        return '-';
                        @endcan
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

</script>
@endpush
