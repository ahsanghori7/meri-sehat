@extends('layouts.admin.app')
@section('page_header')
All {{ Str::plural($module_name) }}
@endsection
@section('content')
<div class="container my-3">
    <div class="card p-3">
        <div class="row">
            @can('scan-management-add')
                <div class="col-md-12">
                     <div class="box-header with-border">
                            <div class="d-flex justify-content-start">
                                <a href="{{ route($folder_name . '-add') }}"
                    class="btn-success btn-sm btn mb-2 cursor-pointer">
                    <i class=" icon-add"></i>
                    Add New
                    </a>
                </div>
            @endcan
            @can('scan-management-filter')
                 <div class="d-flex align-items-center justify-content-between border-bottom border-dark mb-3">
                     <h5 class="">Filter</h5>
                     <span class="icon icon-filter"></span>
                 </div>
                 <div class="row">

                     @can('scan-management-filter-doctor')
                         <div class="col-md-4 mb-3">
                             <label for="search_doctor">Doctor</label>
                             <input type="text" class="form-control searchable_field" name="search_doctor" id="search_doctor" value="" />
                             @error('search_doctor')
                             <div class="validation-error"> {{ $message }}</div>
                             @enderror
                         </div>
                     @endcan

                     @can('scan-management-filter-type')
                         <div class="col-md-4 mb-3">
                             <label for="search_type">Type</label>
                             <select class="form-control searchable_field" id="search_type" name="search_type">
                                 <option value=""> Please Select </option>
                                 <option value="high"> High </option>
                                 <option value="medium"> Medium </option>
                                 <option value="low"> Low </option>
                             </select>
                             @error('search_type')
                             <div class="validation-error"> {{ $message }}</div>
                             @enderror
                         </div>
                     @endcan

                     @can('scan-management-filter-status')
                         <div class="col-md-4 mb-3">
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
        </div>
        <form method="post" action="{{ route($folder_name . '-view') }} ">
            @csrf
            <table id="yajra" class="table table-bordered table-hover data-tables" style="width:100%;">
                <thead>
                    <tr>
                        <th width=" 10px">#</th>
                        <th class="">Name</th>
                        <th class="">Suggested Doctor</th>
                        <th class="">Suggested Article</th>
                        <th class="">Type</th>
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
            , lengthChange: false
            , searching: false
            , ajax: {
                url: "{{ route('health-scan-management-fetch') }}",
                data: function(d) {
                    d.search_doctor = $("#search_doctor").val();
                    d.search_type = $("#search_type").val();
                    d.search_status = $("#search_status").val();
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
                    data: 'doctor.name'
                    , render: function(data, type, row, index) {
                        return row.doctor ? row.doctor.name : "";
                    }
                }
                , {
                    data: 'article.name'
                    , render: function(data, type, row, index) {
                        return row.article ? row.article.name : "";
                    }
                }
                , {
                    'data': 'type'
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
                        @can("scan-management-view")
                        let e_url = ("{{ route('health-scan-management-edit', ['id' => '-id-']) }}").replace('-id-'
                            , row.e_id);
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

    $(document).on('change', '.searchable_field', function(){
        search_start();
    });

    // $(document).ready(function() {
    //     $(".stastus_toggle").click(function() {
    // $(this).toggleClass('bg-green-400')
    // $(this).toggleClass('bg-red-400')
    // if ($(this).attr('checked')) {
    //     alert("checked");
    //     $(this).removeAttr('checked')
    // } else {
    //     alert("NOT checked");
    //     $(this).attr('checked', 'checked');
    // }
    // console.log('test');
    // var id = $(this).data('id')
    // var val = $(this).data('val')
    // Swal.fire({
    //     title: 'Toggle Status?',
    //     icon: 'warning',
    //     showCancelButton: true,
    //     confirmButtonColor: '#3085d6',
    //     cancelButtonColor: '#d33',
    //     confirmButtonText: 'Yes',
    //     cancelButtonText: 'No'
    // }).then((result) => {
    //     if (result.isConfirmed) {
    //         val = !val ? 1 : 0;
    //         toggleStatus(id, val);
    //     } else {

    //         $(this).toggleClass('bg-green-400')
    //         $(this).toggleClass('bg-red-400')
    //     }
    // })

    // })

    // function toggleStatus(id, val) {
    //     console.log(id);
    //     $.ajax({
    //         url: "{{ route($folder_name . '-toggle_status') }}",
    //         type: "post",
    //         data: {
    //             id,
    //             val,
    //             '_token': '{{ csrf_token() }}'
    //         },
    //     });
    // }
    // });

</script>
@endpush
