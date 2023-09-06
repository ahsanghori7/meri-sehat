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
                        <h4 class="mb-0">Appointments</h4>
                    </div>
                    @can('booking-management-filter')
                    <div class="d-flex align-items-center justify-content-between border-bottom border-dark mb-3">
                        <h5 class="">Filter</h5>
                        <span class="icon icon-filter"></span>
                    </div>
                    <div class="row">
                        @can('booking-management-filter-name')
                        <div class="col-md-3 mb-3">
                            <label for="search_name">Name</label>
                            <input type="text" class="form-control searchable_field" name="search_name" id="search_name" value="" />
                            @error('search_name')
                                <div class="validation-error"> {{ $message }}</div>
                            @enderror
                        </div>
                        @endcan

                        @can('booking-management-filter-date')
                        <div class="col-md-3 mb-3">
                            <label for="search_toDate">Date</label>
                            <input type="date" name="search_toDate" id="search_toDate" class="form-control searchable_field" width="30%">
                            @error('search_toDate')
                                <div class="validation-error"> {{ $message }}</div>
                            @enderror
                        </div>
                        @endcan

                        @can('booking-management-filter-category')
                        <div class="col-md-2 mb-3">
                            <label for="search_category">Category Priority</label>
                            <select class="form-control searchable_field" id="search_category" name="search_category">
                                <option value=""> Select Category </option>
                                <option value="standard"> Standard </option>
                                <option value="priority"> Priority </option>
                            </select>
                            @error('search_category')
                                <div class="validation-error"> {{ $message }}</div>
                            @enderror
                        </div>
                        @endcan

                        @can('booking-management-filter-type')
                        <div class="col-md-2 mb-3">
                            <label for="search_type">Type</label>
                            <select class="form-control searchable_field" id="search_type" name="search_type">
                                <option value=""> Select Type </option>
                                <option value="in-person"> In Person </option>
                                <option value="instant-consultation"> Instant Consultation </option>
                                <option value="schedule"> Schedule </option>
                            </select>
                            @error('search_type')
                                <div class="validation-error"> {{ $message }}</div>
                            @enderror
                        </div>
                        @endcan

                        @can('booking-management-filter-status')
                        <div class="col-md-2 mb-3">
                            <label for="search_progress">Status</label>
                            <select class="form-control searchable_field" id="search_progress" name="search_progress">
                                <option value=""> Select Status </option>
                                <option value="pending"> Pending </option>
                                <option value="processing"> Processing </option>
                                <option value="completed"> Completed </option>
                                <option value="cancelled"> Cancelled </option>
                                <option value="cancelled by user"> Cancelled by User </option>
                                <option value="cancelled by doctor"> Cancelled by Doctor </option>
                            </select>
                            @error('search_progress')
                                <div class="validation-error"> {{ $message }}</div>
                            @enderror
                        </div>
                        @endcan
                    </div>
                    @endcan

                    <div class="tab">
                        <button type="button" class="tablinks" onclick="openTab(event, 'past')">Past</button>
                        <button type="button" class="tablinks" id="defaultOpen" onclick="openTab(event, 'today')">Today</button>
                        <button type="button" class="tablinks" onclick="openTab(event, 'upcoming')">Upcoming</button>
                    </div>

                    <div id="past" class="tabcontent">
                        <h3>Past Appointments</h3>

                        <table class="table table-striped table-bordered table-hover data-table-past" style="width:100%;">
                            <thead>
                            <tr>
                                <th width=" 10px">Date & Time</th>
                                <th class="">Patient Name</th>
                                <th class="">Consultant</th>
{{--                                <th class="">Speciality</th>--}}
                                <th class="">Is Patient Connected</th>
                                <th class="">Appointment Type</th>
                                <th class="">Patient Platform</th>
                                <th class="">Patient Source</th>
                                <th class="">Status</th>
                                <th class="">Action</th>
                            </tr>
                            </thead>
                            <tbody class="">

                            </tbody>
                        </table>
                    </div>

                    <div id="today" class="tabcontent">
                        <h3>Today Appointments</h3>

                        <table class="table table-striped table-bordered table-hover data-table-today" style="width:100%;">
                            <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th class="">Patient Name</th>
                                <th class="">Consultant</th>
{{--                                <th class="">Speciality</th>--}}
                                <th class="">Is Patient Connected</th>
                                <th class="">Appointment Type</th>
                                <th class="">Patient Platform</th>
                                <th class="">Patient Source</th>
                                <th class="">Status</th>
                                <th class="">Action</th>
                            </tr>
                            </thead>
                            <tbody class="">

                            </tbody>
                        </table>
                    </div>

                    <div id="upcoming" class="tabcontent">
                        <h3>Upcoming Appointments</h3>

                        <table class="table table-striped table-bordered table-hover data-table-upcoming" style="width:100%;">
                            <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th class="">Patient Name</th>
                                <th class="">Consultant</th>
{{--                                <th class="">Speciality</th>--}}
                                <th class="">Is Patient Connected</th>
                                <th class="">Appointment Type</th>
                                <th class="">Patient Platforms</th>
                                <th class="">Patient Source</th>
                                <th class="">Status</th>
                                <th class="">Action</th>
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
@endsection

@push('scripts')
    <script>
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

            $('#search_toDate').removeAttr('min');
            $('#search_toDate').removeAttr('max');

            if (tabName == 'past') {
                var d = new Date();
                d.setDate(d.getDate() - 1);
                let mm = ''+(d.getMonth()+1)+'';
                let dd = ''+(d.getDate())+'';
                var past = d.getFullYear() + "-" + mm.padStart(2, '0') + "-" + dd.padStart(2, '0');
                $('#search_toDate').attr('max', past);
            } else if (tabName == 'upcoming') {
                var d = new Date();
                d.setDate(d.getDate() + 1);
                let mm = ''+(d.getMonth()+1)+'';
                let dd = ''+(d.getDate())+'';
                var upcoming = d.getFullYear() + "-" + mm.padStart(2, '0') + "-" + dd.padStart(2, '0');
                $('#search_toDate').attr('min', upcoming);
            } else if (tabName == 'today') {
                var d = new Date();
                let mm = ''+(d.getMonth()+1)+'';
                let dd = ''+(d.getDate())+'';
                var currentDate = d.getFullYear() + "-" + mm.padStart(2, '0') + "-" + dd.padStart(2, '0');
                $('#search_toDate').attr('min', currentDate);
                $('#search_toDate').attr('max', currentDate);
            }

            $('.data-table-'+tabName).DataTable().clear().destroy();

            var table = $('.data-table-'+tabName).DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                lengthChange: false,
                searching: false,
                ajax: {
                    url: "{{ route('appointment-view') }}",
                    data: function(d) {
                        d.tab = tabName;
                        d.search_name = $("#search_name").val();
                        d.search_toDate = $("#search_toDate").val();
                        d.search_category = $("#search_category").val();
                        d.search_type = $("#search_type").val();
                        d.search_progress = $("#search_progress").val();
                    }
                },
                columns: [
                    {data: 'time_slot', name: 'time_slot'},
                    {data: 'user', name: 'user'},
                    {data: 'doctor', name: 'doctor'},
                    // {
                    //     render: function (data, type, row, index) {
                    //         var specialities = '';
                    //         var speciality_json = '';
                    //         if (row.doctor_speciality) {
                    //             var speciality_json = $.parseJSON(row.doctor_speciality)
                    //         }
                    //         if (speciality_json != '') {
                    //             speciality_json.forEach((element) => {
                    //                 specialities += '<div class="speciality_tags">' + element.name + '</div>'
                    //             });
                    //         }
                    //         return specialities;
                    //     }
                    // },
                    // {data: 'doctor_speciality', name: 'doctor_speciality'},
                    {
                        render: function (data, type, row, index) {
                            if (row.is_patient_connected == 1) {
                                return 'Yes';
                            } else {
                                return 'No';
                            }
                        }
                    },
                    // {data: 'is_patient_connected', name: 'is_patient_connected'},
                    {data: 'type', name: 'type'},
                    {data: 'agent_check', name: 'agent_check'},
                    {data: 'device_check', name: 'device_check'},
                    {data: 'progress', name: 'progress'},
                    {data: 'actionby', name: 'actionby'},
                ],
                order: [[0, 'desc']]
            });
        }
        $( document ).ready(function() {
            document.getElementById("defaultOpen").click();

        });
    </script>
    <script>
        $(document).on('change', '.searchable_field', function(){
            document.getElementById("defaultOpen").click();
        });

        $(document).on('click', '#status-change', function(){
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
            })
            var id = $(this).data("id");
            var status = $(this).data("status");
            swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: (status === 1) ? "Are you sure want to change the status to Pending?":"Are you sure want to change the status to Completed?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, do it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
            }).then((result) => {
            if (result.isConfirmed) {
                var id = $(this).data("id");
                $.ajax({
                url: 'status/'+id,
                type: "GET",
                success: function(data){
                    $('.data-table').DataTable().ajax.reload();
                    swalWithBootstrapButtons.fire(
                    'Status Updated!',
                    '',
                    'success'
                    )
                },
                error: function(data){
                    swalWithBootstrapButtons.fire(
                    'Something went wrong',
                    '',
                    'error'
                    )
                }
                });
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelled!',
                    '',
                    'error'
                    )
            }
            })
        })
    </script>
    <script>
    $(document).on('click','#date-filter',function(){
        $('.data-table').DataTable().draw();
      });
    </script>
@endpush
