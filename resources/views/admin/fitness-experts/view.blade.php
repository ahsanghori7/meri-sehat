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
                    <h4 class="mb-0">Wellness Experts</h4>
                    @can("fitness-management-add")
                    <div class="box-header with-border">
                        <div class="d-flex justify-content-start">
                            <a href="{{ route('fitness-experts-add') }}" class="btn-success btn-sm btn mb-0 cursor-pointer">
                                <i class=" icon-add"></i>
                                Add New
                            </a>
                        </div>
                    </div>
                    @endcan
                </div>
                @can("fitness-management-filter")
                <div class="d-flex align-items-center justify-content-between border-bottom border-dark mb-3">
                    <h5 class="">Filter</h5>
                    <span class="icon icon-filter"></span>
                </div>
                <div class="row">
                    @can("fitness-management-name")
                    <div class="col-md-2 mb-3">
                        <label for="search_name">Name</label>
                        <input type="text" class="form-control" name="search_name" id="search_name" value="" />
                        @error('search_name')
                        <div class="validation-error"> {{ $message }}</div>
                        @enderror
                    </div>
                    @endcan
                    @can("fitness-management-email")
                    <div class="col-md-2 mb-3">
                        <label for="search_name">Email</label>
                        <input type="email" class="form-control" name="search_email" id="search_email" value="" />
                        @error('search_email')
                        <div class="validation-error"> {{ $message }}</div>
                        @enderror
                    </div>
                    @endcan
                    @can('fitness-management-specialty')
                    <div class="col-md-3 mb-3">
                        <label for="search_speciality">Speciality</label>
                        <select class="form-control js-example-disabled-results" id="search_speciality" name="search_speciality">
                            <option value=""> Select Speciality </option>
                            @foreach ($specialities as $speciality)
                                <option value="{{ $speciality->id }}">
                                    {{ $speciality->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('search_speciality')
                        <div class="validation-error"> {{ $message }}</div>
                        @enderror
                    </div>
                    @endcan
                    @can('fitness-management-location')
                    <div class="col-md-3 mb-3">
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
                    @can('fitness-management-status')
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
{{--                <form method="post" action="{{ route($folder_name . '--view') }} ">--}}
                    @csrf
                    @can("fitness-management-view")
                    <table id="yajra" class="table table-bordered table-hover data-tables" style="width:100%;">
                        <thead>
                        <tr>
                            <th width=" 10px">#</th>
                            <th class="">Name</th>
                            <th class="">Email</th>
                            <th class="">Speciality</th>
                            <th class="">Location</th>
                            <th class="">Gender</th>
                            <th class="">Status</th>
                            <th width="150px">Action</th>
                        </tr>
                        </thead>
                        <tbody class="">

                        </tbody>
                    </table>
                    @endcan

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
    $('#search_speciality').change(function() {
        search_start();
    })
    $('#search_location').change(function() {
        search_start();
    })
    $('#search_status').change(function() {
        search_start();
    })

    function search_start() {
        if ($.fn.DataTable.isDataTable("#yajra")) $("#yajra").DataTable().destroy();
        //Start getting filters

        let name = $('#search_name').val();
        let email = $('#search_email').val();
        let speciality = $('#search_speciality').val();
        let location = $('#search_location').val();
        let status = $('#search_status').val();

        $('#yajra').DataTable({
            "scrollX": true
            , searching: false
            , lengthChange: false
            , processing: true
            , serverSide: true
            , ajax: {
                url: "{{ route('fitness-experts-fetch') }}"
                , type: "GET"
                , contentType: "application/x-www-form-urlencoded; charset=UTF-8"
                , data: {
                    filter: "{{ app('request')->input('q') }}"
                    , name: name
                    , email: email
                    , speciality: speciality
                    , status: status
                    , location: location
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
                    data: 'name'
                }
                , {
                    data: 'email'
                }
                , {
                    render: function(data, type, row, index) {
                        var specialities = '';
                        row.fitness_speciality_details.forEach((element) => {
                            specialities += '<div class="speciality_tags">'+element.name+'</div>'
                        });
                        return specialities;
                    }
                }
                , {
                    render: function(data, type, row, index) {
                        return row.city ? row.city.name : "-";
                    }
                }
                , {
                    data: 'gender'
                }
                , {
                    data: 'status'
                    , render: function(data, type, row) {
                        if (row.status == 1) {
                            return '<span class="badge p-2 badge-success">Active</span>';
                        } else {
                            return '<span class="badge p-2 badge-danger">Inactive</span>';
                        }
                    }
                }
                , {
                    data: 'id'
                    , render: function(data, type, row) {
                        var render_action_button = '';
                        @can("fitness-management-update")
                        let e_url = ("{{ route('fitness-experts-edit', ['id' => '-id-']) }}").replace('-id-'
                            , row.e_id);
                        let slugs = 'fitness-experts/' + row.slug;
                        slugs = "{{env('APP_URL')}}" + slugs;
                        render_action_button += `
                                <a class="btn-primary btn-sm btn cursor-pointer" href="${e_url}"> <i class="icon-edit"></i></a>
                            `;
                        @endcan

                        @can("fitness-management-delete")
                        let delete_url = ("{{ route('fitness-experts-edit', ['id' => '-id-']) }}").replace('-id-'
                            , row.e_id);
                        render_action_button += '<a class="btn-primary btn-danger btn-sm btn cursor-pointer delete" data-id="'+row.e_id+'"> <i class="icon-trash"></i></a>';
                        @endcan
                        return render_action_button;
                    }
                }
                , ]
            , "drawCallback": function(settings) {}
        });
    }


    $('#yajra_filter').children().children().val("{{ app('request')->input('search') }}")
    $('#yajra_filter').children().children().trigger("keyup");

    $(document).on('click', '.delete', function(e) {
        var token = $('input[name="_token"]').val();
        var fitness_id = $(this).data('id');
        let post_url = ("{{ route('fitness-experts-delete') }}");
        $.ajax({
            url: post_url,
            type: "post",
            data: {'id': fitness_id,'_token': token},
            success: function (response) {
                alert('Coming Soon!');
                // You will get response from your PHP page (what you echo or print)
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    });

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
