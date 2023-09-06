@extends('layouts.admin.app')
@section('page_header')
Booking Detail
@endsection
@section('content')
<div class="container-fluid card m-2">

    <div class="row my-3">

        <div class="col-md-12">
            <div class="d-flex align-items-center justify-content-between border-bottom border-dark mb-3 pb-2">
                <div class="d-md-inline-flex">
                    <a href="{{ route('appointment-view') }}"><i class="icon-arrow-left fs-20 mr-2"></i></a>
                    <h5 class="">Appointment Details</h5>
                </div>
                <div class="mr-2">
                    @can('booking-management-detail-trigger_call')
                        <span class="mr-5">
                            <a href="javascript:void(0)" class="trigger_call">Trigger Call</a>
                        </span>
                    @endcan
                    @can('booking-management-detail-reschedule_appointment')
                        <button type="button" class="btn btn-sm btn-primary"  data-toggle="modal" data-target="#reschedule_appointment">Reschedule</button>
                    @endcan
                    @can('booking-management-detail-cancel')
                        <button type="button" id="btn_cancelled" class="btn btn-sm btn-danger">Cancel</button>
                    @endcan
                </div>

            </div>
        </div>

        <div class="col-md-12 ml-3">
            <dl class="row">
                <div class="col-md-2 mb-4">
                    <dt>Appointment ID</dt>
                    <dd>{{ $appointment->id ?? '-' }}</dd>
                </div>
                <div class="col-md-2 mb-4">
                    <dt>Date & Time</dt>
                    <dd>
                        {{ Carbon\Carbon::parse($appointment->date ."" .$appointment->time,)->isoFormat('llll') }}
                    </dd>
                </div>
                <div class="col-md-2 mb-4">
                    <dt>Patient name</dt>
                    <dd>{{ $appointment->patientname ?? '-' }}</dd>
                </div>
                <div class="col-md-2 mb-4">
                    <dt>Patient Id</dt>
                    <dd>{{ $appointment->user->id ?? '-' }}</dd>
                </div>
                <div class="col-md-2 mb-4">
                    <dt>Date of Birth</dt>
                    <dd>{{ $appointment->user->birth_date ?? '-' }}</dd>
                </div>
                <div class="col-md-2 mb-4">
                    <dt>Gender</dt>
                    <dd>{{ $appointment->user->gender ?? '-' }}</dd>
                </div>
                <div class="col-md-2 mb-4">
                    <dt>Location</dt>
                    <dd>{{ $appointment->user->city->name ?? '-' }}</dd>
                </div>
                <div class="col-md-2 mb-4">
                    <dt>Contact number</dt>
                    <dd>{{ $appointment->user->phone ?? '-' }}</dd>
                </div>
                <div class="col-md-2 mb-4">
                    <dt>Symptoms</dt>
                    <dd>{{ $appointment->reason ?? '-' }}</dd>
                </div>
                <div class="col-md-2 mb-4">
                    <dt>Blood Group</dt>
                    <dd>{{ $appointment->getPrescription[0]->blood_group ?? '-' }}</dd>
                </div>
                <div class="col-md-2 mb-4">
                    <dt>Weight</dt>
                    <dd>{{ $appointment->user->weight ?? '-' }}</dd>
                </div>
                <div class="col-md-2 mb-4">
                    <dt>Height</dt>
                    <dd>{{ $appointment->user->height ?? '-' }}</dd>
                </div>
                <div class="col-md-2 mb-4">
                    <dt>Appointment category</dt>
                    <dd>{{ $appointment->priority }}</dd>
                </div>
                <div class="col-md-2 mb-4">
                    <dt>Appointment type</dt>
                    <dd>{{ $appointment->type }}</dd>
                </div>
                <div class="col-md-4 mb-4">
                    <dt>Consultant</dt>
                    <dd>{{ $appointment->doctor->name }}</dd>
                </div>
                <div class="col-md-4 mb-4">
                    <dt>Speciality</dt>
                    @php
                    $specialities = '';
                    if (isset($appointment->doctor->doctorSpecialityDetails)) {
                        foreach ($appointment->doctor->doctorSpecialityDetails as $speciality) {
                            $specialities .= '<div>'.json_decode($speciality)->name.'</div>';
                        }
                    }
                    @endphp
                    <dd>{!! $specialities !!}</dd>
                </div>
                <div class="col-md-4 mb-4">
                    <dt>Short Description for Problem</dt>
                    <dd>{{ $appointment->reason ?? '-' }}</dd>
                </div>
                @can('booking-management-detail-view_uploaded_files')
                <div class="col-md-5 mb-4">
                    <dt>Uploaded Files</dt>
                    <table class="table">
                        <tr>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                        <tbody>
    {{--                    @dd($appointment->user->medicalRecords)--}}
                            @php
                                $medical_files = '';
                                if (isset($appointment->user->medicalRecords)) {
                                    foreach ($appointment->user->medicalRecords as $medicalRecord) {
                                        if (isset($medicalRecord->medicalRecordFiles)) {
                                            foreach ($medicalRecord->medicalRecordFiles as $medicalRecordFile) {
                                                    $medical_files .= '<tr><td>'.json_decode($medicalRecordFile)->file.'</td><td>('.$medicalRecordFile->prescriptionElementType->name.')</td><td><a href="'.storage_path('app/public/user'.$appointment->user_id.'/'.json_decode($medicalRecordFile)->file).'" target="_blank">View File</a></td></tr>';
                                            }
                                        }
                                    }
                                }
                            @endphp
                            {!! $medical_files ?? '<tr><td colspan="3">No any files uploaded</td></tr>' !!}
                        </tbody>
                    </table>
                </div>
                @endcan

                @can('booking-management-detail-connected')
                    <div class="col-md-4 row align-items-center mb-4">
                        <div class="col-md-12">
                            <dt>Is Patient Connected</dt>
                            <dd>{{ $appointment->is_patient_connected == 1 ? 'Yes' : 'No' }}</dd>
                        </div>
                    </div>
                @endcan

                @can('booking-management-detail-platform')
                    <div class="col-md-4 row align-items-center mb-4">
                        <div class="col-md-12">
                            <dt>Platform</dt>
                            <dd>{{ $appointment->agent_check }}</dd>
                        </div>
                    </div>

                    <div class="col-md-4 row align-items-center mb-4">
                        <div class="col-md-12">
                            <dt>Source</dt>
                            <dd>{{ $appointment->device_check }}</dd>
                        </div>
                    </div>
                @endcan

                @can('booking-management-detail-status')
                <div class="col-md-4 row align-items-center mb-4">
                    <div class="col-md-3">
                        <dt>Status</dt>
                    </div>
                    <div class="col-md-9">
                        <dd class="mb-0">
                            <select class="form-control" name="progress" id="progress">
                                <option value=""> Select Status </option>
                                <option value="pending" {{ $appointment->progress == 'pending' ? 'selected' : '' }}> Pending </option>
                                <option value="processing" {{ $appointment->progress == 'processing' ? 'selected' : '' }}> Processing </option>
                                <option value="completed" {{ $appointment->progress == 'completed' ? 'selected' : '' }}> Completed </option>
                                <option value="cancelled" {{ $appointment->progress == 'cancelled' ? 'selected' : '' }}> Cancelled </option>
                                <option value="cancelled by user" {{ $appointment->progress == 'cancelled by user' ? 'selected' : '' }}> Cancelled by User </option>
                                <option value="cancelled by doctor" {{ $appointment->progress == 'cancelled by doctor' ? 'selected' : '' }}> Cancelled by Doctor </option>
                            </select>
                        </dd>
                    </div>
                </div>
                @endcan

                @csrf

{{--                <dt class="col-sm-3">Patient name</dt>--}}
{{--                <dd class="col-sm-9">{{ $appointment->reaseon ?? '-' }}</dd>--}}

{{--                <dt class="col-sm-3">Progress</dt>--}}
{{--                <dd class="col-sm-9">{{ Str::headline($appointment->progress) }}</dd>--}}

{{--                <dt class="col-sm-3">Consultation Fee</dt>--}}
{{--                <dd class="col-sm-9">Rs. {{ $appointment->consultation_fee ?? '0' }}</dd>--}}

{{--                <dt class="col-sm-3">Paid</dt>--}}
{{--                <dd class="col-sm-9">--}}
{{--                    @if ($appointment->is_paid)--}}
{{--                    <span class="badge p-2 badge-success">Paid</span>--}}
{{--                    @else--}}
{{--                    <span class="badge p-2 badge-danger">Not Paid</span>--}}
{{--                    @endif--}}
{{--                </dd>--}}
            </dl>
        </div>
    </div>

    <hr>

    <div class="row my-3">

        <div class="col-md-12 ml-3">
            <div class="white mb-3">
                <strong> Prescription Details</strong>
                <hr>
            </div>
        </div>

        <div class="col-md-12 ml-3">
            @if($appointment->getPrescription && $appointment->getPrescription->prescribedMedicine && count($appointment->getPrescription->prescribedMedicine) > 0)
                <table cellpadding="10" cellspacing="10" style="width: 100%; border-collapse: collapse; margin-bottom: 30px">
                    <thead>
                    <tr>
                        <td width="30%" align="left" style="background-color: #D0F4F5; color: #078A8E; border: 0; font-size:10pt; font-family: circularstd_book;">Drug Name</td>
                        <td width="44%" colspan="4" align="center" style="background-color: #D0F4F5; color: #078A8E; border: 0; font-size:10pt; font-family: circularstd_book;">Medicine/Day</td>
                        <td width="12%" align="center" style="background-color: #D0F4F5; color: #078A8E; border: 0; font-size:10pt; font-family: circularstd_book;">Duration</td>
                        <td width="14%" align="center" style="background-color: #D0F4F5; color: #078A8E; border: 0; font-size:10pt; font-family: circularstd_book;">Instructions</td>
                    </tr>
                    <tr>
                        <td style="background-color: #F6F6F6; border-bottom: 0; font-family: circularstd_book;">&nbsp;</td>
                        <td style="background-color: #F6F6F6; border-bottom: 0; font-family: circularstd_book;" align="center">Morning</td>
                        <td style="background-color: #F6F6F6; border-bottom: 0; font-family: circularstd_book;" align="center">Afternoon</td>
                        <td style="background-color: #F6F6F6; border-bottom: 0; font-family: circularstd_book;" align="center">Evening</td>
                        <td style="background-color: #F6F6F6; border-bottom: 0; font-family: circularstd_book;" align="center">Night</td>
                        <td style="background-color: #F6F6F6; border-bottom: 0; font-family: circularstd_book;">&nbsp;</td>
                        <td style="background-color: #F6F6F6; border-bottom: 0; font-family: circularstd_book;">&nbsp;</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($appointment->getPrescription->prescribedMedicine as $key => $medicine)
                        <tr>
                            <td style="font-family: circularstd_light; line-height: 25px; border-bottom: 0; color: #313131;">{{$medicine->prescriptionElement->name}}</td>
                            <td style="font-family: circularstd_light; line-height: 25px; border-bottom: 0; color: #313131;" align="center">{{$medicine->morning ? $medicine->morning.' '.$medicine->unit : '-'}}</td>
                            <td style="font-family: circularstd_light; line-height: 25px; border-bottom: 0; color: #313131;" align="center">{{$medicine->afternoon ? $medicine->afternoon.' '.$medicine->unit : '-'}}</td>
                            <td style="font-family: circularstd_light; line-height: 25px; border-bottom: 0; color: #313131;" align="center">{{$medicine->evening ? $medicine->evening.' '.$medicine->unit : '-'}}</td>
                            <td style="font-family: circularstd_light; line-height: 25px; border-bottom: 0; color: #313131;" align="center">{{$medicine->night ? $medicine->night.' '.$medicine->unit : '-'}}</td>
                            <td style="font-family: circularstd_light; line-height: 25px; border-bottom: 0; color: #313131;" align="center">{{$medicine->number_of_days}}</td>
                            <td style="font-family: circularstd_light; line-height: 25px; border-bottom: 0; color: #313131;" align="center">{{$medicine->is_after_meal ?'After':'Before' }} Meal</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                @if($appointment->getAppointmentPrescription && $appointment->getAppointmentPrescription->count() > 0)
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                    <thead>
                        <tr>
                            <td align="left" style="background-color: #D0F4F5; color: #078A8E; border: 0; font-size:10pt; font-family: circularstd_book;">Prescription</td>
                        </tr>
                    </thead>
                        <tbody style="">
                            @foreach ($appointment->getAppointmentPrescription as $prescription)
                                <tr>
                                    <td style="border: 0;font-family: circularstd_light; font-weight: 300;">
                                        {{ $loop->iteration }}. {{$prescription->prescription}}
                                    </td>
                                </tr>
                        @endforeach
                        </tbody>
                </table>
                @endif
            @endif

            @if($appointment->getPrescription && $appointment->getPrescription->prescribedLab && count($appointment->getPrescription->prescribedLab) > 0)
                <table style="width: 100%; margin-bottom: 30px; border-collapse: collapse">
                    <thead>
                    <tr>
                        <td width="30%" align="left" style="background-color: #D0F4F5; color: #078A8E; border: 0; font-size:10pt; font-family: circularstd_book;">Lab Test</td>
                        <td width="70%" align="center" style="background-color: #D0F4F5; color: #078A8E; border: 0; font-size:10pt; font-family: circularstd_book;"></td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($appointment->getPrescription->prescribedLab as $key => $labtest)
                        <tr>
                            <td style="font-family: circularstd_light; line-height: 25px; color: #313131;">{{$labtest->description}}</td>
                            <td style="font-family: circularstd_light; justify-content: space-between; text-align:left; line-height: 25px; color: #313131;" align="center">{{$labtest->prescriptionElement->name}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif

            @if($appointment->getPrescription && $appointment->getPrescription->cosultation_note)
                <table style="width: 100%; border-collapse: collapse">
                    <thead>
                    <tr>
                        <td align="left" style="background-color: #D0F4F5; color: #078A8E; border: 0; font-size:10pt; font-family: circularstd_book;">Notes</td>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="font-family: circularstd_light; line-height: 25px; color: #313131;">{{$appointment->getPrescription->cosultation_note}}</td>
                        </tr>
                    </tbody>
                </table>
            @endif

            @if (!$appointment->getPrescription)
                <div style="border: 1px solid #C8C8C8; padding: 8px 12px; font-family: circularstd_light; font-size: 12px; margin-bottom: 30px">No prescription found.</div>
            @endif
{{--            <dl class="row">--}}
{{--                @if ($appointment->getPrescription && $appointment->getPrescription->prescribedMedicine)--}}
{{--                    @foreach ($appointment->getPrescription->prescribedMedicine as $prescribed_element)--}}
{{--                        <dt class="col-sm-3">{{ $prescribed_element->prescriptionElement->type->name ?? '-' }}</dt>--}}
{{--                        @if($prescribed_element->prescriptionElement->type->id == 1)--}}
{{--                            <dd class="col-sm-9">--}}
{{--                                {{ $prescribed_element->prescriptionElement->name ? $prescribed_element->prescriptionElement->name . " (".$prescribed_element->dosage." Units) For " . $prescribed_element->number_of_days ." day(s). ". App\Http\Common\Constant::$numberWords[$prescribed_element->per_day] . " a day. " . ($prescribed_element->is_after_meal ? "After Meal" : "Before Meal") . "." : '-' }}</dd>--}}
{{--                        @else--}}
{{--                            <dd class="col-sm-9">{{ $prescribed_element->prescriptionElement->name ?? '-' }}</dd>--}}
{{--                        @endif--}}
{{--                    @endforeach--}}
{{--                @endif--}}
{{--            </dl>--}}
        </div>
    </div>
</div>


<div class="scheduleappointment modal fade" id="reschedule_appointment" tabindex="-1" role="dialog" aria-labelledby="reschedule_appointment" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="row">
                <div class="col-md-7">
                    <div class="leftBox">
                        <h6 dir="auto" class="headingWithSpaceLarge text-uppercase">Reschedule Appointment</h6>
                        <div class="card border-0 mt-5">

                            <div class="docInfoCard row">
                                <div class="col-md-3">
                                    <div class="imgBox">
                                        <img src="{{ $appointment->doctor->image ? env('ASSETS_STORAGE').'user'.$appointment->doctor->id.'/'.$appointment->doctor->image : '' }}" alt="{{ $appointment->doctor->name }}" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="info">
                                        <div class="docName">
                                            <h5>{{ $appointment->doctor->doctorDetail->prefix ? $appointment->doctor->doctorDetail->prefix.' '.$appointment->doctor->name : $appointment->doctor->name }}</h5>
                                        </div>
                                        <div class="speciality">
                                            @php
                                                $specialities = '';
                                                foreach ($appointment->doctor->doctorSpecialityDetails as $speciality) {
                                                    $specialities .= '<span class="speciality_tags">'.$speciality->name.'</span>';
                                                }
                                            @endphp
                                            <h5>{!! $specialities !!}</h5>
                                        </div>
                                        <div class="availabe two">
                                            <h5>{{ ucwords(str_replace('-', ' ', $appointment->type)) }}</h5>
                                        </div>
                                        <div class="simpletag">
                                            <h5>Rs. {{ $appointment->consultation_fee ?? '-' }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-for-appointment mt-2">
                                <form action="" id="reschedule-appointment-form">
                                    <div>
                                        <label>Appointment Type</label>
                                        <input type="text" id="type" name="type" class="form-control" placeholder="Appointment Type" value="{{ $appointment->type }}" readonly />
                                    </div>
                                    <div>
                                        <label>Reason for Consultation</label>
                                        <input type="text" id="reason" name="reason" class="form-control" />
                                    </div>
                                    <div>
                                        <label>Select Clinic</label>
                                        <input type="text" id="clinic_id" name="clinic_id" class="form-control" placeholder="Clinic Name" value="{{ $appointment->doctorClinic ? $appointment->doctorClinic->clinic->name : '-' }}" readonly />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="rightBox">
                        <div class="calenderLabel"><h6 class="headingDescVsmall "> Select available time  </h6></div>
                        <div class="calender">
                            <div class="carousel-wrapper">
                                <a class="jcarousel-prev" href="#"><i class="fa fa-chevron-left"></i></a>
                                <div class="jcarousel">
                                    <ul>
                                        @foreach ($calendar as $calendar_days)
                                            <li class="month-day {{ $calendar_days['date'] == date('d M') ? 'active' : '' }}" data-date="{{ $calendar_days['full_date'] }}">
                                                <div class="week">{{ $calendar_days['day'] }}</div>
                                                <div class="day-month">{{ $calendar_days['date'] }}</div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <a class="jcarousel-next" href="#"><i class="fa fa-chevron-right"></i></a>
                            </div>
                            <div id="calender_content" class="mt-4">

                            </div>
                        </div>
                        <button type="button" class="styled_button simple_btn_small text-uppercase reschedule-submit-btn">Continue Booking</button>
                        <h6 class="last-note "> You can change the date and time or cancel the appointment 12 hours prior to the scheduled time without cancellation charges. </h6>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        });
        const convertTime12to24 = (time12h) => {
            const [time, modifier] = time12h.split(' ');
            let [hours, minutes] = time.split(':');
            hours = pad(hours, 2);
            if (hours === '12') {
                hours = '00';
            }
            if (modifier === 'PM') {
                hours = parseInt(hours, 10) + 12;
            }
            return `${hours}:${minutes}`;
        };

        function pad (str, max) {
            str = str.toString();
            return str.length < max ? pad("0" + str, max) : str;
        }

        $(document).on('click', '.reschedule-submit-btn', function (e) {
            var month_day = '';
            if ($('.month-day.active').data('date')) {
                month_day = $('.month-day.active').data('date');
            }
            var timeslot = '';
            if ($('input[name="timeslot"]:checked').val()) {
                timeslot = $('input[name="timeslot"]:checked').val();
            }
            if (month_day != '' && timeslot != '') {
                $('#reschedule-appointment-form').submit();
            } else {
                swalWithBootstrapButtons.fire(
                    'Please select Date and Time to complete reschedule process',
                    '',
                    'error'
                );
            }
        });

        $('#reschedule-appointment-form').submit(function (e){
            e.preventDefault();
            var reschedule_url = '{{ url("api/appointment").'/'.$appointment->id }}';
            var myform = document.getElementById("reschedule-appointment-form");
            var fd = new FormData(myform);
            fd.append('date', $('.month-day.active').data('date'));
            fd.append('time', $('input[name="timeslot"]:checked').val());

            $.ajaxSetup({
                headers: {
                    'user-id': '{{ $appointment->user->id }}',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            $.ajax({
                url: reschedule_url,
                data: fd,
                cache: false,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (response) {
                    swalWithBootstrapButtons.fire(
                        'Appointment has been rescheduled.',
                        '',
                        'success'
                    );
                },
                error: function(data){
                    swalWithBootstrapButtons.fire(
                        'Something went wrong',
                        '',
                        'error'
                    );
                }
            });
        })

        $(document).on('click', '.trigger_call', function (e) {
            swalWithBootstrapButtons.fire(
                'Trigger call has been initiated.',
                '',
                'success'
            );
        });

        $(document).on('change', '#progress', function (e) {
            var token = $('input[name="_token"]').val();
            var appointment_id = '{{ $appointment->e_id }}';
            let post_status_url = ("{{ route('appointment-progress_change') }}");
            $.ajax({
                url: post_status_url,
                type: "post",
                data: {'id': appointment_id,'_token': token,'progress': $(this).val()},
                success: function (response) {
                    location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        });

        $(document).on('click', '#btn_cancelled', function (e) {
            var token = $('input[name="_token"]').val();
            var appointment_id = '{{ $appointment->e_id }}';
            let post_cancelled_url = ("{{ route('appointment-progress_cancelled') }}");
            $.ajax({
                url: post_cancelled_url,
                type: "post",
                data: {'id': appointment_id,'_token': token},
                success: function (response) {
                    location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });


        });


        var html_prev = '<li class="month-day"><div class="week">sábado</div><div class="day-month">XX set</div></li><li class="month-day"><div class="week">domingo</div><div class="day-month">XX set</div></li><li class="month-day"><div class="week">segunda</div><div class="day-month">XX set</div></li><li class="month-day"><div class="week">terça</div><div class="day-month">XX set</div></li><li class="month-day"><div class="week">quarta</div><div class="day-month">XX set</div></li><li class="month-day"><div class="week">quinta</div><div class="day-month">XX set</div></li><li class="month-day"><div class="week">sexta</div><div class="day-month">XX set</div></li>';
        var html_next = '<li class="month-day"><div class="week">sábado</div><div class="day-month">YY set</div></li><li class="month-day"><div class="week">domingo</div><div class="day-month">YY set</div></li><li class="month-day"><div class="week">segunda</div><div class="day-month">YY set</div></li><li class="month-day"><div class="week">terça</div><div class="day-month">YY set</div></li><li class="month-day"><div class="week">quarta</div><div class="day-month">YY set</div></li><li class="month-day"><div class="week">quinta</div><div class="day-month">YY set</div></li><li class="month-day"><div class="week">sexta</div><div class="day-month">YY set</div></li>';

        $('.jcarousel').on('jcarousel:createend', function() {
                $(this).jcarousel('scroll', $('.jcarousel li:eq('+get_initialSlide($('.jcarousel').find("ul"))+')'), false);
            }).jcarousel();

        $('.jcarousel-prev').on('jcarouselcontrol:active', function() {
            $(this).removeClass('inactive');
        }).on('jcarouselcontrol:inactive', function() {
            $(this).addClass('inactive');
        }).jcarouselControl({
            target: '-=3'
        });


        $('.jcarousel-next').on('jcarouselcontrol:active', function() {
            $(this).removeClass('inactive');
        }).on('jcarouselcontrol:inactive', function() {
            $(this).addClass('inactive');
        }).jcarouselControl({
            target: '+=3'
        });

        function get_initialSlide(el){
            var initialSlide = $( ".month-day" ).index( el.find('.month-day.active') )
            return parseInt(initialSlide);
        }

        $('.add-prev').click(function(){
            $('.jcarousel ul').prepend(html_prev);
            $('.jcarousel').jcarousel('reload');
        });

        $('.add-next').click(function(){
            $('.jcarousel ul').append(html_prev);
            $('.jcarousel').jcarousel('reload');
        });

        $(document).on('click', '.month-day', function() {
            var that = $(this);
            var selected_date = $(this).data('date');
            var user_id = '{{ $appointment->user_id }}';
            var get_appointment_date = '{{ url('/').'/api/doctor-clinic-time/'.$appointment->id.'?date=' }}'+selected_date;
            $.ajax({
                url: get_appointment_date,
                headers: {"user-id": user_id},
                type: "get",
                success: function (response) {
                    $('.month-day').removeClass('active');
                    that.addClass('active');
                    $('#calender_content').empty();
                    var timeslots = '';
                    if (response.data.onlineTimeSlots != '') {
                        timeslots = response.data.onlineTimeSlots;
                    } else if (response.data.physicalTimeSlots != '') {
                        timeslots = response.data.physicalTimeSlots;
                    }
                    if (timeslots) {
                        $.each(timeslots, function( key, value ) {
                            // alert( key + ": " + value );
                            $('#calender_content').append('<div class="checkbox"><input type="checkbox" name="timeslot" id="'+ value.start_time +'" value="'+ convertTime12to24(value.start_time) +'" /><div class="box"><p>'+ value.start_time +'</p></div></div>');
                        });
                    } else {
                        $('#calender_content').html('<p align="center">There is no any timeslot(s)</p>');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        });

        $(document).on('click', 'input[type="checkbox"]', function() {
            $('input[type="checkbox"]').not(this).prop('checked', false);
        });
    })

</script>
@endpush
