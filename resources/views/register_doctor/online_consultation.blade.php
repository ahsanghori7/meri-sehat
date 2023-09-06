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
                    <h2 class="fs-title">online Consultation details</h2>
                    <h3 class="fs-subtitle">Please give us your online Consultation details</h3>

                    <div class="clinics_div">
                        <div class="clinic_div">

                            <label>Consultation Fee</label>
                            <input type="number" class="form-control" required name="consultation_fee" max="9999" min="1"
                                placeholder="Rs. " value="{{ $online_clinic ? $online_clinic->consultation_fee : '' }}" />

                            <label>Consultation Duration in mins</label>
                            <select class=" form-control w-100 mb-2" name="consultation_duration">
                                <option value="">Please select</option>
                                @for ($i = 5; $i < 150; $i += 5)
                                    <option value="{{ $i }}"
                                        {{ $online_clinic ? ($online_clinic->consultation_duration == $i ? 'selected' : '') : '' }}>
                                        {{ $i }}</option>
                                @endfor
                            </select>

                            <div class="row justify-content-end">
                                <button type="button" class="btn-sm btn-success add_time">Add Time</button>
                            </div>
                            <div class="Consultation_times_div">
                                @if ($online_clinic && count($online_clinic->clinicTimings) > 0)
                                    @foreach ($online_clinic->clinicTimings as $key => $clinic_timing)
                                        <div class="Consultation_time_div">
                                            @if ($key != 0)
                                                <div class="row justify-content-start">
                                                    <button type="button" class="btn-sm btn-danger delete_time"><i
                                                            class="fa fa-trash"></i></button>
                                                </div>
                                            @endif
                                            <label>Consultation Day</label>
                                            <select name="day[]" class="multi_select form-control mb-2" multiple="multiple"  required>
                                                <option value="monday"
                                                    {{ $clinic_timing->day == 'monday' ? 'selected' : '' }}>Monday
                                                </option>
                                                <option value="tuesday"
                                                    {{ $clinic_timing->day == 'tuesday' ? 'selected' : '' }}>Tuesday
                                                </option>
                                                <option value="wednesday"
                                                    {{ $clinic_timing->day == 'wednesday' ? 'selected' : '' }}>Wednesday
                                                </option>
                                                <option value="thursday"
                                                    {{ $clinic_timing->day == 'thursday' ? 'selected' : '' }}>Thursday
                                                </option>
                                                <option value="friday"
                                                    {{ $clinic_timing->day == 'friday' ? 'selected' : '' }}>Friday
                                                </option>
                                                <option value="saturday"
                                                    {{ $clinic_timing->day == 'saturday' ? 'selected' : '' }}>Saturday
                                                </option>
                                                <option value="sunday"
                                                    {{ $clinic_timing->day == 'sunday' ? 'selected' : '' }}>Sunday
                                                </option>
                                            </select>

                                            <div class="row">
                                                <div class="col-6">
                                                    <label>Start Time</label>
                                                    <input type="time" class="form-control start_time" name="start_time[]"
                                                        value="{{ $clinic_timing->start_time }}" />
                                                </div>

                                                <div class="col-6">
                                                    <label>End Time</label>
                                                    <input type="time" class="form-control end_time" name="end_time[]"
                                                        value="{{ $clinic_timing->end_time }}" />

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="Consultation_time_div">
                                        <label>Consultation Day</label>
                                        <select name="day[]" class="multi_select form-control mb-2" multiple="multiple" required>
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
                                                <input type="time" class="form-control start_time" name="start_time[]" />
                                            </div>

                                            <div class="col-6">
                                                <label>End Time</label>
                                                <input type="time" class="form-control end_time" name="end_time[]" />

                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                @endif
                            </div>
                        </div>
                    </div>



                    <div class="d-flex justify-content-between my-5 align-items-center">
                        <div>
                            @php
                                $exploded_url = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
                                $url_id = end($exploded_url);
                            @endphp
                            <a href="{{ route('register_doctor_availability', ['id' => $url_id]) }}"
                                class="action-button text-center form-control px-4">Back</a>
                        </div>
                        <div>
                            <a href="{{route('register_doctor_thankyou')}}" class="action-button text-center form-control px-4">Skip</a>
                        </div>
                        <div>
                            <input type="submit" name="next" class="text-center action-button-green form-control"
                                value="Next" />
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
        $(document).on('click', '.add_time', function() {
            var time_div_html = `
                                <div class="Consultation_time_div">
            <div class="row justify-content-start my-2">
                                    <button type="button" class="btn-sm btn-danger delete_time"><i
                                            class="fa fa-trash"></i></button>
                                </div>
                                <label>Consultation Day</label>
                                <select name="day[]" class="multi_select form-control mb-2" multiple="multiple" required>
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
                                <input type="time" class="form-control start_time" name="start_time[]" />
                                </div>

                                <div class="col-6">
                                    <label>End Time</label>
                                <input type="time" class="form-control end_time" name="end_time[]" />

                                </div>
                                        </div>
                            </div>
                            </div>
            `;
            $(this).parent().next().append(time_div_html)
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
