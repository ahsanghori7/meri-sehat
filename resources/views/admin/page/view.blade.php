@extends('layouts.admin.app')
@section('page_header')
All {{ Str::plural($module_name) }}
@endsection
@section('content')
<div class="container my-3">
    <div class="card p-3">
        <div class="row">
            <div class="col-md-12">
                @can('default-page-add')
                <div class="box-header with-border">
                    <div class="d-flex justify-content-start">
                        <a href="{{ route($folder_name . '-add') }}" class="btn-success btn-sm btn mb-2 cursor-pointer">
                            <i class=" icon-add"></i>
                            Add New
                        </a>
                    </div>
                </div>
                @endcan
                <form method="post" action="{{ route($folder_name . '-view') }} ">
                    @csrf
                    <table id="yajra" class="table table-bordered table-hover data-tables" style="width:100%;">
                        <thead>
                            <tr>
                                <th width=" 10px">#</th>
                                <th class="">Name</th>
                                <th class="">Language</th>
                                <th class="">Image</th>
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

    function search_start() {
        if ($.fn.DataTable.isDataTable("#yajra")) $("#yajra").DataTable().destroy();
        //Start getting filters

        $('#yajra').DataTable({
            "scrollX": true
            , processing: true
            , serverSide: true
            , ajax: {
                url: "{{ route('page-fetch') }}"
                , type: "GET"
                , data: {
                    filter: "{{ app('request')->input('q') }}"
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
                    render: function(data, type, row, index) {
                        return row.language.name;
                    }
                }
                , {
                    render: function(data, type, row, index) {
                        return " <img style='width:100px; height:100px;' src='" + row.image_url + "' >";
                    }
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
                        let e_url = ("{{ route('page-edit', ['id' => '-id-']) }}").replace('-id-'
                            , row.e_id);
                        let l_url = ("{{ route('topics-goto-live', ['id' => '-id-']) }}").replace('-id-'
                            , row.e_id);
                        let mark_draft = ("{{ route('page-mark-draft', ['id' => '-id-']) }}").replace('-id-'
                            , row.e_id);
                        let d_url = ("").replace('-id-', row.e_id);
                        let slugs = row.slug == 'home' ? '' : 'page/' + row.slug;
                        slugs = "{{env('APP_URL')}}" + slugs;
                        let linking_page = ("{{ route('page-linking-page', ['id' => '-id-']) }}").replace('-id-', row.e_id);
                        let pages_url=("{{ route('page-pageArchive', ['id' => '-id-']) }}").replace('-id-'
                            , row.e_id);
                        let btn = ``;
                        // <a class="btn-primary my-2 btn-sm btn cursor-pointer" href="${e_url}"> <i class="icon-edit"></i></a>

                        btn += `<div style="display: flex; gap:10px;">
                                    <div class="dropdown">`;
                        @can('default-page-update')
                            btn += `<button class="btn-primary btn-sm my-2 btn cursor-pointer" title="Edit page layout" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="icon-document-edit2"></i>
                                </button>`;
                        @endcan
                        @can('default-page-draft')
                            btn += ` <a class="btn-info btn-sm my-2 btn cursor-pointer" title="Mark this page as draft" href="${mark_draft}"> <i class="icon-drafts"></i></a>`;
                        @endcan
                        @can('default-page-general-info-goto_live')
                            btn += ` <a class="btn-success btn-sm my-2 btn cursor-pointer" title="Goto live this page" href="${l_url}"> <i class="icon-check"></i></a>`;
                        @endcan
                        @can('default-page-general-info-archive')
                            btn += ` <a class="btn-danger btn-sm my-2 btn cursor-pointer" title="Archive this page" href="${pages_url}"> <i class="icon-trash"></i></a>`;
                        @endcan
                        @can('default-page-update')
                            btn += `<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @foreach($languages as $language)
                        <a class="dropdown-item" href="${linking_page}/{{$language->id}}">{{$language->name}}</a>
                                    @endforeach
                        </div>`;
                        @endcan
                            return `${btn}</div></div>`;
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
