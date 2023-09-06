@extends('layouts.admin.app')
@section('page_header')
All Disease Draft {{ Str::plural($module_name) }}
@endsection
@section('content')
<div class="container my-3">
    <div class="card p-3">
        <div class="row">
            <div class="col-md-12">
                @can("article-management-add")
                <div class="box-header with-border">
                    <div class="d-flex justify-content-start">
                        <a href="{{ route($folder_name . '-disease-draft-add') }}" class="btn-success btn-sm btn mb-2 cursor-pointer">
                            <i class=" icon-add"></i>
                            Add New
                        </a>
                    </div>
                </div>
                @endcan
                <div class="row">
                    <div class="col-md-4 mb-3">
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
{{--                    <div class="col-md-4 mb-3">--}}
{{--                        <label for="search_status">Status</label>--}}
{{--                        <select class="form-control" id="search_status" name="search_status">--}}
{{--                            <option value=""> Select Status </option>--}}
{{--                            </option>--}}
{{--                            <option value="1"> Published </option>--}}
{{--                            </option>--}}
{{--                            <option value="0"> Un-Published </option>--}}
{{--                            </option>--}}
{{--                        </select>--}}
{{--                        @error('search_status')--}}
{{--                        <div class="validation-error"> {{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
                    <div class="col-md-4 mb-3">
                        <label for="search_category">Category</label>
                        <select class="form-control" id="search_category" name="search_category">
                            <option value=""> Select Category </option>
                            </option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}"> {{ $category->title }} </option>
                            </option>
                            @endforeach
                        </select>
                        @error('search_category')
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
                                <th class="">Language</th>
                                <th class="">Category</th>
                                <th class="">Image</th>
                                <th class="">Status</th>
                                <th class="">Draft</th>
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
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    });

    function search_start() {
        if ($.fn.DataTable.isDataTable("#yajra")) $("#yajra").DataTable().destroy();
        //Start getting filters

        let category = $('#search_category').val();
        let status = $('#search_status').val();
        let locale = $('#search_language').val();

        $('#yajra').DataTable({
            "scrollX": true
            , processing: true
            , serverSide: true
            , ajax: {
                url: "{{ route('article-disease-draft-fetch') }}"
                , type: "GET"
                , contentType: "application/x-www-form-urlencoded; charset=UTF-8"
                , data: {
                    filter: "{{ app('request')->input('q') }}"
                    , category: category
                    , status: status
                    , lang_id: locale
                }
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
                        return row.parent ? row.parent.title : "-";
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
                            return '<span class="badge p-2 badge-success">Published</span>';
                        } else {
                            return '<span class="badge p-2 badge-danger">Un-Published</span>';
                        }
                    }
                }
                , {
                    data: 'draft'
                    , render: function(data, type, row) {
                        if (row.draft == 1) {
                            return '<span class="badge p-2 badge-danger">Draft</span>';
                        } else {
                            return '<span class="badge p-2 badge-success">Live</span>';
                        }
                    }
                }
                , {
                    data: 'id'
                    , render: function(data, type, row) {
                        let e_url = ("{{ route('article-draft-disease-edit', ['id' => '-id-']) }}").replace('-id-', row.e_id);
                        let l_url = ("{{ route('article-goto-live', ['id' => '-id-']) }}").replace('-id-', row.e_id);
                        let archive_url = ("{{ route('article-archiveArticle', ['id' => '-id-']) }}").replace('-id-'
                        , row.e_id);
                        let btn = ``;
                        @can('article-management-update')
                            btn += ` <a class="btn-primary btn-sm my-2 btn cursor-pointer" title="Edit this article" href="${e_url}"> <i class="icon-edit"></i></a>`;
                        @endcan
                        @can('article-management-general-info-goto_live')
                            btn += ` <a class="btn-success btn-sm my-2 btn cursor-pointer" title="Goto Live this article" href="${l_url}" onclick="return confirm('Are you sure?')"><i class="icon-check"></i></a>`;
                        @endcan
                        @can('article-management-archive')
                            btn += `<a class="btn-danger btn-sm my-2 btn cursor-pointer archive-url" title="Archive this article" href="${archive_url}"><i class="icon-trash"></i></a>`;
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

{{-- <a class="btn-warning btn-sm my-2 btn cursor-pointer" title="Copy to Clipboard"
onclick="copyToClipboard(this)" data-text="${slugs}"> <i
    class="icon-clipboard"></i>
</a> --}}
