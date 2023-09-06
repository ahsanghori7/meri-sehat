@extends('layouts.register_doctor.app')
@php
    $positions = [
        "Professor",
        "Assistant Professor",
        "Associate Professor",
        "Senior Registrar",
        "Consultant",
        "HO",
    ];
@endphp
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
                    <li>Consultation details</li>
                </ul>
                <!-- fieldsets -->
                <fieldset>
                    <h2 class="fs-title">Qualification & EXPERIENCE</h2>
                    <h3 class="fs-subtitle">Tell us about your qualifications</h3>

                    <div class="row justify-content-end">
                        <button type="button" class="btn-sm btn-info add_qualification">Add Qualification</button>
                    </div>
                    <br>
                    <div class="qualifications_div">
                        @if(isset($result->doctorEducation) && count($result->doctorEducation) > 0)
                                @foreach ($result->doctorEducation as $key => $doctor_education)
                                <div class="qualification_div">
                                    @if($key != 0)
                                        <div class="row justify-content-end">
                                            <button type="button" class="btn-sm btn-danger delete_qualification mt-2 " ><i class="fa fa-trash t-white"></i></button>
                                        </div>
                                    @endif
                                    <label>degree</label>
                                    <select name="degree[]" class="select_2_sigle_select form-control mb-2 degree" required>
                                        <option value="">Please select</option>
                                        @foreach ($degrees as $degree)
                                            <option value="{{$degree->id}}" {{ $doctor_education->degree == $degree->name ? ' selected' : '' }}>{{$degree->name." ( ".$degree->full_name." )"}}</option>
                                        @endforeach
                                        <option value="other">Other</option>
                                    </select>

                                    <label>institute or University</label>
                                    <select name="institute[]" class="select_2_sigle_select form-control mb-2 institute" required>
                                        <option value="">Please select</option>
                                        @foreach ($universities as $university)
                                            <option value="{{$university->id}}" {{ $doctor_education->institute == $university->name ? ' selected' : '' }}>{{$university->name}}</option>
                                        @endforeach
                                        <option value="other">Other</option>
                                    </select>

                                    <label>year of completion</label>
                                    <select name="year_of_completion[]" class="form-control mb-2" required>
                                        <option value="">Please select</option>
                                        @for ($i = 0; $i < 50; $i++)
                                            <option  {{ $doctor_education->year_of_completion == date('Y') - $i ? ' selected' : '' }}>{{ date('Y') - $i }}</option>
                                        @endfor
                                    </select>
                                    <hr>
                                </div>
                                @endforeach
                        @else
                        <div class="qualification_div">
                            <label>degree</label>
                            <select name="degree[]" class="select_2_sigle_select form-control mb-2 degree" required>
                                <option value="">Please select</option>
                                @foreach ($degrees as $degree)
                                    <option value="{{$degree->id}}">{{$degree->name." ( ".$degree->full_name." )"}}</option>
                                @endforeach
                                <option value="other">Other</option>
                            </select>

                            <label>institute or University</label>
                            <select name="institute[0]" class="select_2_sigle_select form-control mb-2 institute" required>
                                <option value="">Please select</option>
                                @foreach ($universities as $university)
                                    <option value="{{$university->id}}">{{$university->name}}</option>
                                @endforeach
                                <option value="other">Other</option>
                            </select>

                            <label>year of completion</label>
                            <select name="year_of_completion[]" class="form-control mb-2" required>
                                <option value="">Please select</option>
                                @for ($i = 0; $i < 50; $i++)
                                    <option>{{ date('Y') - $i }}</option>
                                @endfor
                            </select>
                            <hr>
                        </div>
                        @endif
                    </div>

                    <h3 class="fs-subtitle mt-3">Tell us about your professional experience</h3>

                    <div class="row justify-content-end mb-3">
                        <button type="button" class="btn-sm btn-info add_experience">Add Experience</button>
                    </div>
                    <div class="experiences_div">

                        @if(isset($result->doctorExperiences) && count($result->doctorExperiences) > 0)
                                @foreach ($result->doctorExperiences as $key => $doctor_experience)
                                <div class="experience_div">
                                    @if($key != 0)
                                    <div class="row justify-content-end">
                                                    <button type="button" class="btn-sm btn-danger delete_experience mt-2 " ><i class="fa fa-trash t-white"></i></button>
                                                </div>
                                    @endif
                                    <label>Designation</label>
                                    <select name="position[]" class="form-control mb-2" required>
                                        <option value="">Please select</option>
                                        @foreach($positions as $position)
                                            <option {{ $doctor_experience->position == $position ? ' selected' : '' }}>{{$position}}</option>
                                        @endforeach
                                    </select>
                                    <label>institute</label>
                                    <input type="text" required class="form-control" name="experience_institute[]" placeholder="institute" value="{{$doctor_experience->institute}}" />

                                    <div class="row">
                                            <div class="col-6">
                                                <label>Start Year</label>
                                                <select name="start_year[]" class="form-control mb-2" required>
                                                    <option value="">Please select</option>
                                                    @for ($i = 0; $i < 50; $i++)
                                                        <option {{ $doctor_experience->start_year == date('Y') - $i ? ' selected' : '' }}>{{ date('Y') - $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label>End Year</label>
                                                <select name="end_year[]" class="form-control mb-2" >
                                                    <option value="">Please select</option>
                                                    @for ($i = 0; $i < 50; $i++)
                                                        <option {{ $doctor_experience->end_year == date('Y') - $i ? ' selected' : '' }}>{{ date('Y') - $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>

                                    <hr>
                                </div>
                                @endforeach
                        @else
                        <div class="experience_div">
                            <label>Designation</label>
                            <select name="position[]" class="form-control mb-2" required>
                                <option value="">Please select</option>
                                @foreach($positions as $position)
                                    <option>{{$position}}</option>
                                @endforeach
                            </select>
                            <label>institute</label>
                            <input type="text" required class="form-control" name="experience_institute[]" placeholder="institute"/>

                            {{-- <label>Start year</label>
                            <select name="start_year[]" class="form-control mb-2" >
                                @for ($i = 0; $i < 50; $i++)
                                    <option>{{ date('Y') - $i }}</option>
                                @endfor
                            </select>

                            <label>End year</label>
                                <select name="end_year[]" class="form-control mb-2" >
                                    @for ($i = 0; $i < 50; $i++)
                                        <option>{{ date('Y') - $i }}</option>
                                    @endfor
                                </select> --}}



                                <div class="row">
                                    <div class="col-6">
                                        <label>Start Year</label>
                                        <select name="start_year[]" class="form-control mb-2" required>
                                            <option value="">Please select</option>
                                            @for ($i = 0; $i < 50; $i++)
                                                <option>{{ date('Y') - $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label>End Year</label>
                                        <select name="end_year[]" class="form-control mb-2" >
                                            <option value="">Present</option>
                                            @for ($i = 0; $i < 50; $i++)
                                                <option>{{ date('Y') - $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            <hr>
                        </div>
                        @endif
                    </div>


                    <h3 class="fs-subtitle mt-3">Tell us about your professional Memberships</h3>

                    <div class="row justify-content-end mb-3">
                        <button type="button" class="btn-sm btn-info add_collaboration">Add Professional Membership</button>
                    </div>
                    <div class="collaborations_div">

                        @if(isset($result->doctorDetail->collaborations) && json_decode($result->doctorDetail->collaborations) != null)
                        @if(count(array(json_decode($result->doctorDetail->collaborations)) ) > 0)
                        @foreach (json_decode($result->doctorDetail->collaborations) as $key => $collaboration)
                        @if($collaboration != null)
                        <div class="collaboration_div">
                            @if($key != 0)
                            <div class="row justify-content-end">
                                            <button type="button" class="btn-sm btn-danger delete_collaboration mt-2 " ><i class="fa fa-trash t-white"></i></button>
                                        </div>
                            @endif
                            <label>Professional Membership (such as Pakistan Medical Commission, Pakistan Medical Association, etc.)</label>
                            <input type="text" class="form-control" name="collaborations[]" value="{{ $collaboration }}" placeholder="Professional Membership"/>
                            <hr>
                        </div>
                        @endif
                        @endforeach
                        @endif
                        @else
                        <div class="collaboration_div">
                            <label>Professional Membership (such as Pakistan Medical Commission, Pakistan Medical Association, etc.)</label>
                            <input type="text" class="form-control" name="collaborations[]" placeholder="Professional Membership"/>
                            <hr>
                        </div>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between my-5 align-items-center">
                        <div>
                            @php
                            $exploded_url = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
                            $url_id = end($exploded_url);
                            @endphp
                            <a href="{{route('register_doctor_about',['id' => $url_id])}}" class="action-button text-center form-control px-4">Back</a>
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
        var institue_index = 0;
        var degree_index = 0;
        $(document).on('change', '.institute', function() {
            let institute_val = $(this).val();
            var curPos = $(this).parent().index();
            if(institute_val == "other"){
                let other_institute_html = `
                            <input type="text" class="form-control" name="other_institute_name[`+curPos+`]" placeholder="Institute Name"/>
                            <select name="other_institute_sector[`+curPos+`]" class="form-control mb-2" >
                                <option value="government">Government</option>
                                <option value="public">Public</option>
                                <option value="private">Private</option>
                            </select>
                            <input type="text" class="form-control" name="other_institute_city[`+curPos+`]" placeholder="Institute City"/>
                            `;
                $(other_institute_html).insertAfter($(this))
            } else {
                $('[name="other_institute_name['+curPos+']"]').remove();
                $('[name="other_institute_sector['+curPos+']"]').remove();
                $('[name="other_institute_city['+curPos+']"]').remove();
            }
        })

        $(document).on('change', '.degree', function() {
            let degree_val = $(this).val();
            var curPos = $(this).parent().index();
            if(degree_val == "other"){
                let other_degree_html = `
                    <input type="text" required class="form-control" name="other_degree_name[`+curPos+`]" placeholder="Degree Name e.g. B.D Sc"/>
                    <input type="text" class="form-control" name="other_degree_full_name[`+curPos+`]" placeholder="Degree Full Name e.g. Bachelor of Dental Science"/>
                            `;
                $(other_degree_html).insertAfter($(this))
            } else {
                $('[name="other_degree_name['+curPos+']"]').remove();
                $('[name="other_degree_full_name['+curPos+']"]').remove();
            }
        })

        $('.add_qualification').click(function() {
            institue_index += 1;
            degree_index += 1;
            var qualification_div_html = `
            <div class="qualification_div">
                            <div class="row justify-content-end">
                                <button type="button" class="btn-sm btn-danger delete_qualification mt-2 " ><i class="fa fa-trash t-white"></i></button>
                            </div>
                            <label>degree</label>
                            <select name="degree[]" class="select_2_sigle_select form-control mb-2 degree" required>
                                <option value="">Please select</option>
                                @foreach ($degrees as $degree)
                                    <option value="{{$degree->id}}">{{$degree->name." ( ".$degree->full_name." )"}}</option>
                                @endforeach
                                <option value="other">Other</option>
                            </select>
                            <label>institute or University</label>
                            <select name="institute[]" class="select_2_sigle_select form-control mb-2 institute" required>
                                <option value="">Please select</option>
                                @foreach ($universities as $university)
                                    <option value="{{$university->id}}">{{$university->name}}</option>
                                @endforeach
                                <option value="other">Other</option>
                            </select>
                            <label>year of completion</label>
                            <select name="year_of_completion[]" class="form-control mb-2" required>
                                <option value="">Please select</option>
                                @for ($i = 0; $i < 50; $i++)
                                    <option>{{ date('Y') - $i }}</option>
                                @endfor
                            </select>
                            <hr>
                        </div>`;
            $('.qualifications_div').append(qualification_div_html)
            $('.select_2_sigle_select').select2();
        })

        $(document).on('click', '.delete_qualification', function() {
            $(this).parent().parent().remove()
        })


        $('.add_experience').click(function() {
            var experience_div_html = `
            <div class="experience_div">
                <div class="row justify-content-end">
                                <button type="button" class="btn-sm btn-danger delete_experience mt-2 " ><i class="fa fa-trash t-white"></i></button>
                            </div>
                            <label>Designation</label>
                            <select name="position[]" class="form-control mb-2" required>
                                <option value="">Please select</option>
                                @foreach($positions as $position)
                                    <option>{{$position}}</option>
                                @endforeach
                            </select>

                            <label>institute</label>
                            <input type="text" class="form-control" name="experience_institute[]" placeholder="institute" required />

                            <div class="row">
                                    <div class="col-6">
                                        <label>Start Year</label>
                                        <select name="start_year[]" class="form-control mb-2" >
                                            <option value="">Please select</option>
                                            @for ($i = 0; $i < 50; $i++)
                                                <option>{{ date('Y') - $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label>End Year</label>
                                        <select name="end_year[]" class="form-control mb-2">
                                            <option value="">Please select</option>
                                            @for ($i = 0; $i < 50; $i++)
                                                <option>{{ date('Y') - $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            <hr>
                        </div>`;
            $('.experiences_div').append(experience_div_html)
        })

        $(document).on('click', '.delete_experience', function() {
            $(this).parent().parent().remove()
        })


        $('.add_collaboration').click(function() {


            var collaboration_div_html = `
            <div class="collaboration_div">
                <div class="row justify-content-end">
                                <button type="button" class="btn-sm btn-danger delete_collaboration mt-2 " ><i class="fa fa-trash t-white"></i></button>
                            </div>
                            <label>Professional Membership</label>
                            <input type="text" class="form-control" name="collaborations[]" placeholder="Professional Membership" required/>

                            <hr>
                        </div>
            `;
            $('.collaborations_div').append(collaboration_div_html)
        })

        $(document).on('click', '.delete_collaboration', function() {
            $(this).parent().parent().remove()
        })
    </script>
@endpush
