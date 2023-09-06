@extends('layouts.register_doctor.app')
@section('form')
    <!-- MultiStep Form -->
    <div class="row">
        <div class="col-md-12">
            <form id="msform" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="text-center">
                    <img src="{{ asset('admin/img/logo.svg') }}" alt="" class="logo">
                </div>
                @csrf
                <!-- progressbar -->
                <ul id="progressbar">
                    <li class="active">About You</li>
                    <li class="active">Qualification & Experience</li>
                    <li class="active">Consultation details</li>
                </ul>
                <!-- fieldsets -->
                <fieldset>
                    <h2 class="fs-title">Consultation details</h2>
                    <h3 class="fs-subtitle">Please give us your Consultation details</h3>

                    <div class="row justify-content-end">
                        <button type="button" class="btn-sm btn-info add_clinic">Add Clinic</button>
                    </div>
                    <br>
                    <div class="clinics_div">
                        @if($result->doctorClinics && count($result->doctorClinics) > 0)
                                @foreach ($result->doctorClinics as $key => $doctor_clinic)
                                <div class="clinic_div">
                                    @if($key != 0)
                                    <div class="row justify-content-end">
                                        <button type="button" class="btn-sm btn-danger delete_clinic"><i
                                                class="fa fa-trash"></i></button>
                                    </div>
                                    @endif
                                    <label>Clinic / Hospital</label>
                                    <select name="clinic[{{$key}}]" class="select_2_sigle_select form-control mb-2 clinic" required>
                                        <option value="">Please select</option>
                                        @foreach ($clinics as $clinic)
                                            <option value="{{ $clinic->id }}" {{ $doctor_clinic->clinic_id == $clinic->id ? ' selected' : '' }}>{{ $clinic->name }}</option>
                                        @endforeach
                                        <option value="other">Other</option>
                                    </select>

                                    <label>Consultation Fee</label>
                                    <input type="number" class="form-control" required name="consultation_fee[]" max="9999" min="1" placeholder="Rs. " value="{{ $doctor_clinic->consultation_fee }}"/>

                                    <label>Consultation Duration in mins</label>
                                    <select class=" form-control w-100 mb-2" name="consultation_duration[]">
                                        <option value="">Please select</option>
                                        @for($i = 5; $i < 65; $i += 5)
                                        <option value="{{ $i }}"
                                            {{ $doctor_clinic->consultation_duration == $i ? 'selected' : '' }}>
                                            {{ $i }}</option>
                                        @endfor
                                    </select>
                                    <div class="row justify-content-end">
                                        <button type="button" class="btn-sm btn-success add_time" data-index="{{$key}}">Add Time</button>
                                    </div>
                                    <div class="Consultation_times_div">

                            @if($doctor_clinic->clinicTimings && count($doctor_clinic->clinicTimings) > 0)
                            @foreach ($doctor_clinic->clinicTimings as $time_key => $clinic_timing)
                                        <div class="Consultation_time_div">

                                    @if($time_key != 0)
                                    <div class="row justify-content-start">
                                        <button type="button" class="btn-sm btn-danger delete_time"><i
                                                class="fa fa-trash"></i></button>
                                    </div>
                                    @endif
                                            <label>Consultation Day</label>
                                            <select name="day[{{$key}}][]" class="multi_select form-control mb-2 days" multiple="multiple" required>
                                                <option value="monday" {{ $clinic_timing->day == "monday"? "selected" :'' }}>Monday</option>
                                                <option value="tuesday" {{ $clinic_timing->day == "tuesday"? "selected" :'' }}>Tuesday</option>
                                                <option value="wednesday" {{ $clinic_timing->day == "wednesday"? "selected" :'' }}>Wednesday</option>
                                                <option value="thursday" {{ $clinic_timing->day == "thursday"? "selected" :'' }}>Thursday</option>
                                                <option value="friday" {{ $clinic_timing->day == "friday"? "selected" :'' }}>Friday</option>
                                                <option value="saturday" {{ $clinic_timing->day == "saturday"? "selected" :'' }}>Saturday</option>
                                                <option value="sunday" {{ $clinic_timing->day == "sunday"? "selected" :'' }}>Sunday</option>
                                            </select>

                                            <div class="row">
                                                <div class="col-6">
                                                    <label>Start Time</label>
                                                    <input type="time" class="form-control start_time " name="start_time[{{$key}}][]" value="{{ $clinic_timing->start_time }}"/>
                                                </div>
                                                <div class="col-6">
                                                    <label>End Time</label>
                                                    <input type="time" class="form-control end_time" name="end_time[{{$key}}][]" value="{{ $clinic_timing->end_time }}"/>
                                                </div>
                                            </div>



                                        </div>
                                        @endforeach
                                        @endif
                                        <hr>
                                    </div>
                                </div>
                                @endforeach
                        @else
                            <div class="clinic_div">
                                <label>Clinic / Hospital</label>
                                <select name="clinic[0]" class="select_2_sigle_select form-control mb-2 clinic" required>
                                    <option value="">Please select</option>
                                    @foreach ($clinics as $clinic)
                                        <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                                    @endforeach
                                    <option value="other">Other</option>
                                </select>

                                <label>Consultation Fee</label>
                                <input type="number" class="form-control" required name="consultation_fee[]" max="9999" min="1" placeholder="Rs. " />

                                <label>Consultation Duration in mins</label>
                                {{-- <input type="number" class="form-control" name="consultation_duration[]" max="999" min="1"/> --}}

                                <select class=" form-control w-100 mb-2" name="consultation_duration[]">
                                    <option value="">Please select</option>
                                    @for($i = 5; $i < 65; $i += 5)
                                    <option>{{ $i }}</option>
                                    @endfor
                                </select>

                                {{-- <div class="row justify-content-start">
                                    <button type="button" class="btn-sm btn-danger delete_time"><i class="fa fa-trash"></i></button>
                                </div> --}}

                                <div class="row justify-content-end">
                                    <button type="button" class="btn-sm btn-success add_time">Add Time</button>
                                </div>
                                <div class="Consultation_times_div">
                                    <div class="Consultation_time_div">
                                        <label>Consultation Day</label>
                                        <select name="day[0][]" class="multi_select form-control mb-2 days" multiple="multiple" required>
                                            <option value="monday">Monday</option>
                                            <option value="tuesday">Tuesday</option>
                                            <option value="wednesday">Wednesday</option>
                                            <option value="thursday">Thursday</option>
                                            <option value="friday">Friday</option>
                                            <option value="saturday">Saturday</option>
                                            <option value="sunday">Sunday</option>
                                        </select>

                                        <div class="row">
                                            <div class="col-6">
                                                <label>Start Time</label>
                                                <input type="time" class="form-control start_time " name="start_time[0][]" />
                                            </div>
                                            <div class="col-6">
                                                <label>End Time</label>
                                                <input type="time" class="form-control end_time" name="end_time[0][]" />
                                            </div>
                                        </div>

                                    </div>

                                    <hr>
                                </div>
                            </div>
                        @endif
                    </div>


                    <div class="d-flex justify-content-between my-5 align-items-center">
                        <div>
                            @php
                            $exploded_url = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
                            $url_id = end($exploded_url);
                            @endphp
                            <a href="{{route('register_doctor_education_experience',['id' => $url_id])}}" class="action-button text-center form-control px-4">Back</a>
                        </div>
                        <div>
                            <a href="{{route('register_doctor_online_consultation',['id' => $url_id])}}" class="action-button text-center form-control px-4">Skip</a>
                        </div>
                        <div>
                            <input type="submit" name="next" class="text-center action-button-green form-control" value="Next" />
                        </div>
                    </div>
                </fieldset>

            </form>
        </div>
    </div>
    <!-- /.MultiStep Form -->
@endsection
@push('scripts')
    <script>
        var clinic_index = @if($result->doctorClinics && count($result->doctorClinics) > 0) {{(count($result->doctorClinics) -1)}} @else 0 @endif;

        $(document).on('change', '.clinic', function() {
            let institute_val = $(this).val();
            if(institute_val == "other"){
                let other_clinic_html = `
                    <input type="text" required class="form-control" name="other_clinic_name[`+clinic_index+`]" placeholder="Clinic Name"/>
                    <input type="text" required class="form-control" name="other_clinic_address[`+clinic_index+`]" placeholder="Clinic Address"/>
                `;
                $(other_clinic_html).insertAfter($(this))
            } else {
                $('[name="other_clinic_name['+clinic_index+']"]').remove();
                $('[name="other_clinic_address['+clinic_index+']"]').remove();
            }
        })
        $(document).on('click', '.add_clinic', function() {
            clinic_index += 1;
            var clinic_div_html = `
            <div class="clinic_div mt-2">
            <div class="row justify-content-end">
                                    <button type="button" class="btn-sm btn-danger delete_clinic"><i
                                            class="fa fa-trash"></i></button>
                                </div>
                            <label>Clinic / Hospital</label>
                            <select name="clinic[`+clinic_index+`]" class="select_2_sigle_select form-control mb-2 clinic" required>
                                <option value="">Please select</option>
                                @foreach ($clinics as $clinic)
                                    <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                                @endforeach
                                <option value="other">Other</option>
                            </select>

                            <label>Consultation Fee</label>
                            <input type="number" class="form-control" required name="consultation_fee[]" max="9999" min="1" placeholder="Rs. " />

                            <label>Consultation Duration in mins</label>
                            <select class=" form-control w-100 mb-2" name="consultation_duration[]">
                                <option value="">Please select</option>
                                @for($i = 5; $i < 65; $i += 5)
                                    <option>{{ $i }}</option>
                                @endfor
                            </select>

                            <div class="row justify-content-end">
                                <button type="button" class="btn-sm btn-success add_time" data-index="`+clinic_index+`">Add Time</button>
                            </div>
                            <div class="Consultation_times_div">
                                <div class="Consultation_time_div">
                                    <label>Consultation Day</label>
                                    <select name="day[`+clinic_index+`][]" class="multi_select form-control mb-2" multiple="multiple" required>
                                        <option value="monday">Monday</option>
                                        <option value="tuesday">Tuesday</option>
                                        <option value="wednesday">Wednesday</option>
                                        <option value="thursday">Thursday</option>
                                        <option value="friday">Friday</option>
                                        <option value="saturday">Saturday</option>
                                        <option value="sunday">Sunday</option>
                                    </select>


                                    <div class="row">
                                            <div class="col-6">
                                    <label>Start Time</label>
                                    <input type="time" class="form-control start_time " name="start_time[`+clinic_index+`][]" required/>
                                </div>

                                <div class="col-6">
                                    <label>End Time</label>
                                    <input type="time" class="form-control end_time" name="end_time[`+clinic_index+`][]" required/>

                                </div>
                                        </div>

                                </div>

                                <hr>
                            </div>
                        </div>
            `;
            $('.clinics_div').append(clinic_div_html)
            $('.select_2_sigle_select').select2();
            $('.multi_select').select2();
        })

        $(document).on('click', '.delete_clinic', function() {
            $(this).parent().parent().remove()
        })


        $(document).on('click', '.add_time', function() {
            let index = clinic_index;
            var time_div_html = `
                                <div class="Consultation_time_div">
            <div class="row justify-content-start">
                                    <button type="button" class="btn-sm btn-danger delete_time my-2"><i
                                            class="fa fa-trash"></i></button>
                                </div>
                                <label>Consultation Day</label>
                                <select name="day[`+index+`][]" class="multi_select form-control mb-2" multiple="multiple" required>
                                    <option value="monday">Monday</option>
                                    <option value="tuesday">Tuesday</option>
                                    <option value="wednesday">Wednesday</option>
                                    <option value="thursday">Thursday</option>
                                    <option value="friday">Friday</option>
                                    <option value="saturday">Saturday</option>
                                    <option value="sunday">Sunday</option>
                                </select>
                                <div class="row">
                                            <div class="col-6">
                                    <label>Start Time</label>
                                    <input type="time" class="form-control start_time " name="start_time[`+index+`][]" required/>
                                </div>

                                <div class="col-6">
                                    <label>End Time</label>
                                    <input type="time" class="form-control end_time" name="end_time[`+index+`][]" required/>

                                </div>
                                        </div>
                            </div>
                            </div>
            `;
            $(this).parent().next().append(time_div_html);
            $('.multi_select').select2();
        })

        $(document).on('click', '.delete_time', function() {
            $(this).parent().parent().remove()
        })


        $(document).on('change', '.start_time', function() {
            let start_time = $(this).val()
            $(this).parent().next().children('.end_time').attr('min',start_time)
        })
        $(document).on('change', '.end_time', function() {
            let end_time = $(this).val()
            $(this).parent().parent().find('.start_time').attr('max',end_time)
        })
    </script>
@endpush
