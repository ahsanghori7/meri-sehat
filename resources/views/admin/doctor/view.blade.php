@extends('layouts.admin.app')
@section('page_header')
All {{ Str::plural($module_name) }}
@endsection
@section('content')
<div class="container my-3">
    <div class="row">
        <div class="col-md-12">
            <div class="tab bg-white">
                <button type="button" class="tablinks doctorTabBtn" id="defaultOpen" onclick="openTab(event, 'live')">Live Doctors</button>
                <button type="button" class="tablinks doctorTabBtn" onclick="openTab(event, 'onboarding')">Onboarding</button>
                <button type="button" class="tablinks doctorTabBtn" onclick="openTab(event, 'deactivated')">Deactivated</button>
            </div>
        </div>
    </div>
    <div class="card p-3">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="">Filter</h5>
                    @can("doctor-management-add")
                    <a href="{{ route('doctor-add') }}" data-toggle="modal" data-target="#addDoctorLead" class="btn-success btn-sm btn mb-0 cursor-pointer ml-auto">
                        <i class=" icon-add"></i>
                        Add Doctor
                    </a>
                    <a href="{{ route('doctor-add') }}" class="btn-success btn-sm btn mb-0 cursor-pointer ml-2">
                        <i class=" icon-add"></i>
                        Import Leads
                    </a>
                    @endcan
                </div>
                <div class="row">
                    @can('doctor-management-filter-name')
                    <div class="col-md-2 mb-3">
                        <label for="search_name">Name</label>
                        <input type="text" class="form-control searchable_field" name="search_name" id="search_name" value="" />
                        @error('search_name')
                        <div class="validation-error"> {{ $message }}</div>
                        @enderror
                    </div>
                    @endcan

                    @can('doctor-management-filter-email')
                    <div class="col-md-2 mb-3">
                        <label for="search_name">Email</label>
                        <input type="email" class="form-control searchable_field" name="search_email" id="search_email" value="" />
                        @error('search_email')
                        <div class="validation-error"> {{ $message }}</div>
                        @enderror
                    </div>
                    @endcan

                    @can('doctor-management-filter-speciality')
                    <div class="col-md-3 mb-3">
                        <label for="search_speciality">Speciality</label>
                        <select class="form-control js-example-disabled-results searchable_field" id="search_speciality" name="search_speciality">
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

                    @can('doctor-management-filter-location')
                    <div class="col-md-3 mb-3">
                        <label for="search_location">Location</label>
                        <select class="form-control js-example-disabled-results searchable_field" id="search_location" name="search_location">
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

                    @can('doctor-management-filter-status')
                    <div class="col-md-2 mb-3">
                        <label for="search_status">Status</label>
                        <select class="form-control searchable_field" id="search_status" name="search_status">
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
{{--                <form method="post" action="{{ route($folder_name . '--view') }} ">--}}
                    <div class="tabcontent" id="live">
                        <table class="table table-bordered table-hover data-table-live" style="width:100%;">
                            <thead>
                            <tr>
                                <th width=" 10px">Doctor Id</th>
                                <th class="">Name</th>
                                <th class="">Email</th>
                                <th class="">Speciality</th>
                                <th class="">Featured</th>
                                <th class="">Gender</th>
                                <th class="">Location</th>
                                <th class="">Verification</th>
                                <th class="">Type</th>
                                <th width="150px">Action</th>
                            </tr>
                            </thead>
                            <tbody class="">
    
                            </tbody>
                        </table>
                    </div>
                    <div class="tabcontent" id="onboarding">
                        <table class="table table-bordered table-hover data-table-onboarding" style="width:100%;">
                            <thead>
                            <tr>
                                <th width=" 10px">Doctor ID</th>
                                <th class="">Name</th>
                                <th class="">Phone Number</th>
                                <th class="">Last Updated</th>
                                <th class="">About</th>
                                <th class="">Qualification</th>
                                <th class="">Consultation</th>
                                <th class="">Location</th>
                                <th class="">Status</th>
                                <th width="150px">Action</th>
                            </tr>
                            </thead>
                            <tbody class="">
    
                            </tbody>
                        </table>
                    </div>
                    <div class="tabcontent" id="deactivated">
                        <table class="table table-bordered table-hover data-table-deactivated" style="width:100%;">
                            <thead>
                            <tr>
                                <th width=" 10px">Doctor Id</th>
                                <th class="">Name</th>
                                <th class="">Email</th>
                                <th class="">Speciality</th>
                                <th class="">Featured</th>
                                <th class="">Gender</th>
                                <th class="">Location</th>
                                <th class="">Verification</th>
                                <th class="">Type</th>
                                <th width="150px">Action</th>
                            </tr>
                            </thead>
                            <tbody class="">
    
                            </tbody>
                        </table>
                    </div>

            </div>
        </div>
    </div>
</div>
@include('admin.doctor.add_modal')
@endsection

@push('scripts')
<script>
    $(document).on('change', '.searchable_field', function(){
        document.getElementById("defaultOpen").click();
    });
    function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";

            $('.data-table-'+tabName).DataTable().clear().destroy();

            let name = $('#search_name').val();
            let email = $('#search_email').val();
            let speciality = $('#search_speciality').val();
            let location = $('#search_location').val();
            let status = $('#search_status').val();

            if(tabName == 'onboarding'){
                var table = $('.data-table-onboarding').DataTable({
                "scrollX": true,
                searching: false,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('doctor-fetch') }}",
                    type: "GET"
                , contentType: "application/x-www-form-urlencoded; charset=UTF-8"
                , data: {
                    filter: "{{ app('request')->input('q') }}"
                    , name: name
                    , email: email
                    , speciality: speciality
                    , status: status
                    , location: location
                    , tab: tabName
                }
                },
                createdRow: function(row, data, dataIndex) {},
                columns: [
                    {data: 'doctorId', name: 'doctorId'},
                    {data: 'name', name: 'name'},
                    {data: 'phone', name: 'phone'},
                    {data: 'last_updated', name: 'last_updated'},
                    {data: 'about', name: 'about'},
                    {data: 'qualification', name: 'qualification'},
                    {data: 'consultation', name: 'consultation'},
                    {data: 'location', name: 'location'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},
                ],
            "drawCallback": function(settings) {},
                order: [[0, 'desc']]
            });
            table.columns.adjust().draw();
            }
            else{

            var table = $('.data-table-'+tabName).DataTable({
                "scrollX": true,
                searching: false,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('doctor-fetch') }}",
                    type: "GET"
                , contentType: "application/x-www-form-urlencoded; charset=UTF-8"
                , data: {
                    filter: "{{ app('request')->input('q') }}"
                    , name: name
                    , email: email
                    , speciality: speciality
                    , status: status
                    , location: location
                    , tab: tabName
                }
                },
                createdRow: function(row, data, dataIndex) {},
                columns: [
                {
                    data: 'id'
                }
                , {
                    data: 'name'
                }
                , {
                    render: function(data, type, row, index){
                        return row.email ?? '-';
                    }
                }
                , {
                    render: function(data, type, row, index) {
                        var specialities = '';
                        row.doctor_speciality_details.forEach((element) => {
                            specialities += '<div class="speciality_tags">'+element.name+'</div>'
                        });
                        return specialities;
                    }
                }
                , {
                    render: function(data, type, row, index) {
                        return row.doctor_detail && row.doctor_detail.is_featured == 1 ? 'Yes' : 'No';
                    }
                }
                , {
                    render: function(data, type, row, index) {
                        return row.gender != null ? row.gender : '-';
                    }
                }
                , {
                     render: function(data, type, row) {
                        return row.city ? row.city.name : '-';
                    }
                }
                ,
                {
                     render: function(data, type, row) {
                        if (row.doctor_detail && row.doctor_detail.is_verified == 1) {
                            return '<span class="badge p-2 text-white" style="background-color:#19B3B5;">Verified <i class="icon icon-check"></i></span>';
                        } else {
                            return '<span class="badge p-2 badge-danger text-white">Unverified</span>';
                        }
                    }
                }
                ,
                {
                    render: function(data, type, row) {
                        if (row.doctor_detail && row.doctor_detail.is_staff == 1) {
                            return 'Staff Doctor';
                        } else {
                            return 'External Doctor';
                        }
                    }
                }
                ,
                {
                    data: 'id'
                    , render: function(data, type, row) {
                        var render_action_button = '';
                        @can("doctor-management-update")
                        let e_url = ("{{ route('doctor-edit', ['id' => '-id-']) }}").replace('-id-'
                            , row.e_id);
                        let slugs = 'doctor/' + row.slug;
                        slugs = "{{env('APP_URL')}}" + slugs;
                        render_action_button += `
                                <a class="btn-primary btn-sm btn cursor-pointer" href="${e_url}"> <i class="icon-edit"></i></a>
                            `;
                        @endcan

                        @can("doctor-management-delete")
                        let delete_url = ("{{ route('doctor-edit', ['id' => '-id-']) }}").replace('-id-'
                            , row.e_id);
                        render_action_button += '<a class="btn-primary btn-danger btn-sm btn cursor-pointer delete" data-id="'+row.e_id+'"> <i class="icon-trash"></i></a>';
                        @endcan
                        return render_action_button;
                    }
                }
                , ],
            "drawCallback": function(settings) {},
                order: [[0, 'desc']]
            });
            table.columns.adjust().draw();
        }
        }
        $( document ).ready(function() {
            document.getElementById("defaultOpen").click();
        });
</script>
<script>
    $('#yajra_filter').children().children().val("{{ app('request')->input('search') }}")
    $('#yajra_filter').children().children().trigger("keyup");

    $(document).on('click', '.delete', function(e) {
        var token = $('input[name="_token"]').val();
        var doctor_id = $(this).data('id');
        let post_url = ("{{ route('doctor-delete') }}");
        $.ajax({
            url: post_url,
            type: "post",
            data: {'id': doctor_id,'_token': token},
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