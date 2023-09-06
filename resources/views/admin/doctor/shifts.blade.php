{{--<div id="Shifts" class="tabcontent">--}}
{{--    <div class="d-flex align-items-center justify-content-between mb-3">--}}
{{--        <h3 class="mb-0">Shifts</h3>--}}

{{--        <div class="box-header with-border">--}}
{{--            <div class="d-flex justify-content-start">--}}
{{--                <a href="javascript:void(0);" class="btn-success btn-sm btn mb-0 cursor-pointer" data-toggle="modal" data-target="#edit_doctor_details_modal">--}}
{{--                    <i class=" icon-add"></i>--}}
{{--                    Add Shift--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--    </div>--}}
{{--    <table class="table table-striped table-bordered-none">--}}
{{--        <thead>--}}
{{--        <tr>--}}
{{--            <th scope="col">Days</th>--}}
{{--            <th scope="col">Consult time</th>--}}
{{--            <th scope="col">Clinic Hours</th>--}}
{{--            <th scope="col">Action</th>--}}
{{--        </tr>--}}
{{--        </thead>--}}
{{--        <tbody>--}}
{{--        <tr>--}}
{{--            <td>Monday</td>--}}
{{--            <td>15 Mins</td>--}}
{{--            <td>9am to 12pm (Standard)</td>--}}
{{--            <td>--}}
{{--                <a href="javascript:void(0)" class="mr-2 text-primary">Edit</a>--}}
{{--                <a href="javascript:void(0)" class="text-danger">Delete</a>--}}
{{--            </td>--}}
{{--        </tr>--}}
{{--        <tr>--}}
{{--            <td>Tuesday</td>--}}
{{--            <td>15 Mins</td>--}}
{{--            <td>3am to 6pm (Priority)</td>--}}
{{--            <td>--}}
{{--                <a href="javascript:void(0)" class="mr-2 text-primary">Edit</a>--}}
{{--                <a href="javascript:void(0)" class="text-danger">Delete</a>--}}
{{--            </td>--}}
{{--        </tr>--}}
{{--        <tr>--}}
{{--            <td>Wednesday</td>--}}
{{--            <td>15 Mins</td>--}}
{{--            <td>9am to 12pm - 3pm to 6pm</td>--}}
{{--            <td>--}}
{{--                <a href="javascript:void(0)" class="mr-2 text-primary">Edit</a>--}}
{{--                <a href="javascript:void(0)" class="text-danger">Delete</a>--}}
{{--            </td>--}}
{{--        </tr>--}}
{{--        <tr>--}}
{{--            <td>Thursday</td>--}}
{{--            <td>15 Mins</td>--}}
{{--            <td>9am to 12pm</td>--}}
{{--            <td>--}}
{{--                <a href="javascript:void(0)" class="mr-2 text-primary">Edit</a>--}}
{{--                <a href="javascript:void(0)" class="text-danger">Delete</a>--}}
{{--            </td>--}}
{{--        </tr>--}}
{{--        <tr>--}}
{{--            <td>Friday</td>--}}
{{--            <td>15 Mins</td>--}}
{{--            <td>9am to 12pm</td>--}}
{{--            <td>--}}
{{--                <a href="javascript:void(0)" class="mr-2 text-primary">Edit</a>--}}
{{--                <a href="javascript:void(0)" class="text-danger">Delete</a>--}}
{{--            </td>--}}
{{--        </tr>--}}
{{--        <tr>--}}
{{--            <td>Saturday</td>--}}
{{--            <td>-</td>--}}
{{--            <td>-</td>--}}
{{--            <td>-</td>--}}
{{--        </tr>--}}
{{--        <tr>--}}
{{--            <td>Sunday</td>--}}
{{--            <td>-</td>--}}
{{--            <td>-</td>--}}
{{--            <td>-</td>--}}
{{--        </tr>--}}
{{--        </tbody>--}}
{{--    </table>--}}
{{--</div>--}}

{{--<div id="shifts" class="tabcontent">--}}
<div class="modal fade" id="edit_shifts_details_modal" tabindex="-1" role="dialog"
     aria-labelledby="edit_shifts_details_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit shifts</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_shifts_details_modal" tabindex="-1" role="dialog"
     aria-labelledby="add_shifts_details_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" class="needs-validation" id="shifts_add_submit" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add shifts</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="box_border">
                        <div class="row items_div">
                            @can('doctor-management-shift-select-hospital/clinic')
                            <div class="col-md-12 mb-3">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                    <label>Select Clinic/Hospital</label>
                                </div>
                                <select class="form-control" name="clinic" id="clinic">
                                    @foreach ($clinics as $clinic)
                                        <option value="{{$clinic->id}}" >{{$clinic->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endcan
                            @can('doctor-management-shift-select-days')
                            <div class="col-md-12 mb-3">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                    <label>Select day</label>
                                </div>
                                <select class="form-control" name="day" id="day">
                                    @foreach ($days as $day)
                                        <option value="{{$day}}" >{{ ucfirst($day) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endcan
                            @can('doctor-management-shift-consultation-fees')
                            <div class="col-md-12 mb-3">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                    <label>Consultation Fees</label>
                                </div>
                                <input type="text" class="form-control" name="consultation_fee" id="consultation_fee" value="" />
                            </div>
                            @endcan
                            @can('doctor-management-shift-is-physical')
                            <div class="col-md-12 mb-3">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                    <label>Is Physical</label>
                                </div>
                                <input type="checkbox"  name="is_physical" id="is_physical" value="1" />
                            </div>
                            @endcan
                            
                            <div class="col-md-12 mb-3">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                    <label>Select Duration (in minutes)</label>
                                </div>
                                <input type="number" class="form-control" name="consultation_duration" id="consultation_duration" value="" />
                            </div>
                            @can('doctor-management-shift-time-in')
                            <div class="col-md-6 mb-3">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                    <label>Time In</label>
                                </div>
                                <input type="text" class="form-control timepicker" name="start_time" id="start_time" />
                            </div>
                            @endcan
                            @can('doctor-management-shift-time-out')
                            <div class="col-md-6 mb-3">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                    <label>Time Out</label>
                                </div>
                                <input type="text" class="form-control timepicker" name="end_time" id="end_time" />
                            </div>
                            @endcan
                            @can('doctor-management-shift-generate-slots')
                            <div class="col-md-12 mb-3">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                    <label>Select time slot
                                    <button type="button" id="generate_slot" name="generate_slot" onclick="chk_field()">Generate Slots</button>
                                    </label>
                                </div>
                                <div id="time_slots"></div>
                            </div>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="mb-0">Shifts</h3>
    @can('doctor-management-shift-add-shift')
    <div class="box-header with-border">
        <div class="d-flex justify-content-start">
            <a href="javascript:void(0);" class="btn-success btn-sm btn mb-0 cursor-pointer" data-toggle="modal" data-target="#add_shifts_details_modal">
                <i class=" icon-add"></i>
                Add Shift
            </a>
        </div>
    </div>
    @endcan

</div>
@can('doctor-management-shift-list-view')
<table class="table table-striped table-bordered-none" id="shifts_listing">
    <thead>
    <tr>
        <th scope="col">Days</th>
        <th scope="col">Consult Duration</th>
        <th scope="col">Clinic Hours</th>
        <th scope="col">Clinic Name</th>
        <th scope="col">Action</th>
    </tr>
    <tr>
        <th scope="col">Days</th>
        <th scope="col">Consult Duration</th>
        <th scope="col">Clinic Hours</th>
        <th scope="col">Clinic Name</th>
        <th scope="col">Action</th>
    </tr>
    </thead>
    <tbody>
    @if($result->user->doctorClinics && count($result->user->doctorClinics) > 0)
        @foreach ($result->user->doctorClinics as $clinic)
{{--            @dd($clinic);--}}
{{--            @dd($clinic->getClinicWithTimeSlots($result->user->id));--}}
            @foreach($clinic->clinicTimings as $record)
{{--                @foreach($record['time_slots'] as $time_slot)--}}
{{--                    @dd($record);--}}
                    <tr>
                        <td>{{ucfirst($record['day'])}}</td>
                        <td>{{$clinic->consultation_duration}} mins</td>
                        <td>{{date('h:ia', strtotime($record['start_time'])).' ~ '.date('h:ia', strtotime($record['end_time']))}}</td>
                        <td>{{$clinic->clinic->name}}</td>
                        <td>
                            {{--                <a href="javascript:void(0)" class="mr-2 text-primary">Edit</a>--}}
                            <a href="javascript:void(0)" data-id="{{$record['id']}}" class="text-danger shifts_delete">Delete</a>
                        </td>
                    </tr>
{{--                @endforeach--}}
            @endforeach
        @endforeach
    @else
        <tr>
            <td colspan="5">No shifts</td>
        </tr>
    @endif
    </tbody>
</table>
@endcan
<script>
    var doctor_link = '{{$doctor_link}}';
    if (doctor_link != '') {
        $(document).find('.warning').addClass('d-none');
        $(document).find('.warning').removeClass('d-flex');
        $(document).find('.warning').removeClass('justify-content-center');
        $('.doctor_anchor').removeClass('d-none').attr('href', doctor_link);
    } else {
        $(document).find('.warning').removeClass('d-none');
        $(document).find('.warning').addClass('d-flex');
        $(document).find('.warning').addClass('justify-content-center');
        $('.doctor_anchor').addClass('d-none').attr('href', 'javascript:void(0)');
    }
    var shifts_template =
        `<div class="col-md-12 mb-3 add">
                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                    <label>Select shift</label>
                    <button type="button" class="btn-outline-danger btn-sm btn mb-0 cursor-pointer py-0 px-1 shifts_delete_this">
                        &times;
                    </button>
                </div>
                <select class="form-control badge" name="shift[]" required>
                    <option value="">Select shift</option>
{{--                    @foreach ($shifts as $key => $shift)--}}
{{--        <option value="{{$shift->id}}">{{$shift->name}}</option>--}}
{{--                    @endforeach--}}
        </select>
</div>`;

    function pad (str, max) {
        str = str.toString();
        return str.length < max ? pad("0" + str, max) : str;
    }

    function convertTime24to12 (time) {
        time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];
        if (time.length > 1) {
            time = time.slice (1);
            time[5] = +time[0] < 12 ? 'AM' : 'PM';
            time[0] = +time[0] % 12 || 12;
        }
        return time.join ('');
    }

    function addMinutes(time, minutes) {
        var date = new Date(new Date('01/01/2015 ' + time).getTime() + minutes * 60000);
        var tempTime = ((date.getHours().toString().length == 1) ? '0' + date.getHours() : date.getHours()) + ':' +
            ((date.getMinutes().toString().length == 1) ? '0' + date.getMinutes() : date.getMinutes()) + ':' +
            ((date.getSeconds().toString().length == 1) ? '0' + date.getSeconds() : date.getSeconds());
        return tempTime;
    }

    function chk_field() {
        var start_time = convertTime12to24($('#start_time').val())+':00';
        var end_time = convertTime12to24($('#end_time').val())+':00';
        var interval = $('#consultation_duration').val();
        var timeslots = [start_time];

        if (start_time != '' && end_time != '' && interval != '') {
            while (start_time != end_time) {

                start_time = addMinutes(start_time, interval);
                timeslots.push(start_time);

            }

            $('#time_slots').empty();

            $.each( timeslots, function( key, value ) {
                // alert( key + ": " + value );
                $('#time_slots').append('<label for="'+ value +'"><input type="checkbox" name="timeslot[]" id="'+ value +'" value="'+ value +'" />'+ convertTime24to12(value) +'</label>');
            });
            // alert(timeslots)
        }
    }

    $(document).on('click', '.shift_add_more', function() {
        $('#add_shifts_details_modal').find('.items_div').append(shifts_template);
    })

    $(document).on('click', '.shifts_delete_this', function() {
        $(this).parent().parent().remove()
    })

    // $('#add_shifts_details_modal').on('shown.bs.modal', function (e) {
    // do something...
    // })

    $('.timepicker').wickedpicker({
        timeSeparator: ':'
    });



    $('#add_shifts_details_modal').on('hidden.bs.modal', function (e) {
        $('#add_shifts_details_modal').find('.add').not(':first').remove();
        $('#shifts_add_submit').trigger("reset");
    });

    $('#shifts_add_submit').submit(function (event) {
        event.preventDefault();

        var shifts_add_url = '{{ route("doctor-add-shifts", ":id") }}';
        shifts_add_url = shifts_add_url.replace(':id', doctor_id);
        var myform = document.getElementById("shifts_add_submit");
        var fd = new FormData(myform );
        $.ajax({
            url: shifts_add_url,
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (response) {
                swalWithBootstrapButtons.fire(
                    'shift has been added.',
                    '',
                    'success'
                );
                $('#Shifts_tab').click();
            },
            error: function(data){
                swalWithBootstrapButtons.fire(
                    'Something went wrong',
                    '',
                    'error'
                );
                $('#Shifts_tab').click();
            }
        });
        $('#add_shifts_details_modal').find('.add').not(':first').remove();
        $('#shifts_add_submit').trigger("reset");
    });

    $(document).on('click', '.shifts_delete', function(e) {
        var data = new FormData();
        var shiftId = $(this).data('id');
        var shifts_delete_url = '{{ route("doctor-delete-shifts", ":id") }}';
        data.append( 'recordId',  shiftId);
        shifts_delete_url = shifts_delete_url.replace(':id', doctor_id);
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                $.ajax({
                    url: shifts_delete_url,
                    data: data,
                    cache: false,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function (response) {
                        swalWithBootstrapButtons.fire(
                            'Shift has been deleted.',
                            '',
                            'success'
                        );
                        $('#Shifts_tab').click();
                    },
                    error: function(data){
                        swalWithBootstrapButtons.fire(
                            'Something went wrong',
                            '',
                            'error'
                        );
                        $('#Shifts_tab').click();
                    }
                });
            }
        })
    });

    $('#shifts_listing thead tr:eq(1) th').each( function () {
        var title = $(this).text();
        if (title != 'Action') {
            $(this).html('<input type="text" placeholder="Search ' + title + '" class="column_search" />');
        }
        if (title == 'Action') {
            $(this).html('');
        }
    });

    var table = $('#shifts_listing').DataTable({
        orderCellsTop: true,
        // searching: false,
        bLengthChange : false,
        autoWidth: false,
        retrieve: true,
    });

    $(".dataTables_filter").hide();


    $('#shifts_listing thead').on( 'keyup', ".column_search",function () {
        table
            .column( $(this).parent().index() )
            .search( this.value )
            .draw();
    });sss

</script>

