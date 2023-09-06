@extends('layouts.admin.app')
@section('page_header')
All Draft {{ Str::plural($module_name) }}
@endsection
@section('content')
<div class="container my-3">
    <div class="card p-3">
        <div class="row">
            <div class="col-md-12">
                @can('disease-page-add')
                <div class="box-header with-border">
                    <div class="d-flex justify-content-start">
                        <a href="{{ route($folder_name . '-draft-add') }}" class="btn-success btn-sm btn mb-2 cursor-pointer">
                            <i class=" icon-add"></i>
                            Add New
                        </a>
                    </div>
                </div>
                @endcan
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="search_language">Language</label>
                        <select class="form-control" id="search_language" name="search_language">
                            <option value=""> Select Language </option>
                            @foreach ($languages as $language)
                            <option value="{{ $language->id }}">
                                {{ $language->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('search_language')
                        <div class="validation-error"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="search_status">Status</label>
                        <select class="form-control" id="search_status" name="search_status">
                            <option value=""> Select Status </option>
                            </option>
                            <option value="1"> Enable </option>
                            </option>
                            <option value="0"> Disable </option>
                            </option>
                        </select>
                        @error('search_status')
                        <div class="validation-error"> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <form method="post" action="{{ route($folder_name . '-view') }} ">
                    @csrf
                    <table id="yajra" class="table table-bordered table-hover data-tables" style="width:100%;">
                        <thead>
                            <tr>
                                <th width=" 10px">#</th>
                                <th class="">Name</th>
                                <th class="">Parent</th>
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
                url: "{{ route('disease-draft-fetch') }}"
                , type: "GET"
                // , contentType: "application/x-www-form-urlencoded; charset=UTF-8"
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
                        if (row.translation_of == 0 || row.translation_of == null) {
                            return 'Parent Record';
                        } else {
                            return row.translation_of.name;
                        }
                    }
                , }
                , {
                    render: function(data, type, row, index) {
                        return row.language.name
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
                        let e_url = ("{{ route('disease-edit', ['id' => '-id-']) }}").replace('-id-'
                            , row.e_id);
                        let l_url = ("{{ route('disease-goto-live', ['id' => '-id-']) }}").replace('-id-'
                            , row.e_id);
                        let disease_archive= ("{{ route('disease-diseaseArchive', ['id' => '-id-']) }}").replace('-id-'
                            , row.e_id)
                        let d_url = ("").replace('-id-', row.e_id);
                        let slugs = "{{env('APP_URL')}}" + (row.lang_id != 1 ? row.language.slug + "disease/" + row.slug : "disease/" + row.slug);
                        let linking_page = ("{{ route('disease-linking-page', ['id' => '-id-']) }}").replace('-id-', row.e_id);
                        let btn = ``;

                        @can('disease-page-view')
                            btn += ` <a class="btn-primary btn-sm my-2 btn cursor-pointer" title="Edit this disease" href="${e_url}"> <i class="icon-edit"></i></a>`;
                        @endcan
                        @can('disease-page-page-layout')
                            btn += `<a class="btn-secondary btn-sm my-2 btn cursor-pointer" title="Edit page layout"
                                    href="${linking_page}"> <i
                                        class="icon-document-edit2"></i>
                                </a>`;
                        @endcan
                        @can('disease-page-goto_live')
                            btn += ` <a class="btn-success btn-sm my-2 btn cursor-pointer" title="Goto live this disease" href="${l_url}"> <i class="icon-check"></i></a>`;
                        @endcan
                        @can('disease-page-clipboard')
                            btn += ` <a class="btn-warning btn-sm my-2 btn cursor-pointer" title="Copy to clipboard"
                                    onclick="copyToClipboard(this)" data-text="${slugs}"> <i
                                        class="icon-clipboard"></i>
                                </a>`;
                        @endcan
                        @can('disease-page-archive')
                            btn += ` <a class="btn-danger btn-sm my-2 btn cursor-pointer" title="Archive this disease" href="${disease_archive}"> <i class="icon-trash"></i></a>`;
                        @endcan
                        return `<div style="display: flex; gap:10px;">
                                    ${btn}
                                </div>
                            `;
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
