@extends('layouts.admin.app')
@section('page_header')
Edit {{ Str::singular($module_name) }}
@endsection
@section('content')
<div class="container my-3">
        <div class="card p-3">
            <div class="row border-bottom">
                <div class="col-md-8 text-left">
                    {{ (isset($doctor->doctorDetail) ) ? $doctor->doctorDetail->prefix.' '.$doctor->name : $doctor->name }}
                    @if($completion_progress < 100)
                        <span class="badge p-2 badge-danger text-white ml-3">Incomplete <i class="icon icon-check"></i></span>
                    @elseif ($verification_progress < 100)
                        <span class="badge p-2 text-white ml-3" style="background-color:#EB8E39;">Pending Verification <i class="icon icon-check"></i></span>
                    @endif
                </div>
                @if($completion_progress < 100)
                    <div class="col-md-4 text-right">
                        <div class="progress" style="height:20px;">
                            <div class="progress-bar progress-bar-success p-2" role="progressbar"
                            aria-valuenow="{{ number_format($completion_progress) }}" aria-valuemin="0" aria-valuemax="100" style="width:{{ number_format($completion_progress) }}%;">
                            </div>
                        </div>
                        Profile Completion: {{ number_format($completion_progress) }}%
                    </div>
                @elseif ($verification_progress < 100)
                    <div class="col-md-4 text-right">
                        <div class="progress" style="height:20px;">
                            <div class="progress-bar progress-bar-success p-2" role="progressbar"
                            aria-valuenow="{{ number_format($verification_progress) }}" aria-valuemin="0" aria-valuemax="100" style="width:{{ number_format($verification_progress) }}%;">
                            </div>
                        </div>
                        Profile Verification: {{ number_format($verification_progress) }}%
                    </div>
                @endif
                <div class="tab bg-white border-0">
                    <button type="button" class="tablinks text-uppercase doctorTabBtns" id="about_tab" onclick="openTab(event, 'about')">
                        About
                    </button>
                    <button type="button" class="tablinks text-uppercase doctorTabBtns" id="qualification_tab" onclick="openTab(event, 'qualification')">
                        Qualifications
                    </button>
                    <button type="button" class="tablinks text-uppercase doctorTabBtns" id="consultation_tab" onclick="openTab(event, 'consultation')">
                        Consultation Details
                    </button>
                </div>
                <form method="POST" action="{{ route('doctor-onboard-update', $doctor->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div id="about" class="tabcontent border-0">
                        <h4 class="mt-4">Basic Information</h4>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="doctor_id">Doctor ID</label>
                                <input type="text" class="form-control" id="doctor_id" name="doctor_id" value="{{ isset($doctor->id) ? $doctor->id : '-' }}" readonly aria-describedby="emailHelp">
                            </div>
                            <div class="col-md-4">
                                <label for="name">Full Name</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <select class="form-control parent_type rounded" name="prefix" id="prefix" required>
                                            <option value="Dr" {{ isset($doctor->doctorDetail->prefix) && $doctor->doctorDetail->prefix == 'Dr' ? 'selected' : '' }}>Dr</option>
                                            <option value="Mr" {{ isset($doctor->doctorDetail->prefix) && $doctor->doctorDetail->prefix == 'Mr' ? 'selected' : '' }}>Prof</option>
                                        </select>
                                    </div>
                                    <input type="text" name="name" id="name" value="{{ isset($doctor->name) ? $doctor->name : '-' }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="phone">Mobile Number</label>
                                <input type="text" class="form-control" name="phone" id="phone" aria-describedby="emailHelp" value="{{ isset($doctor->phone) ? $doctor->phone : '-' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="assistant_phone">Assistant Mobile Number</label>
                                <input type="text" class="form-control" id="assistant_phone" name="assistant_phone" value="{{ isset($doctor->doctorDetail->assistant_phone) ? $doctor->doctorDetail->assistant_phone : '' }}" placeholder="Assistant Mobile Number" aria-describedby="emailHelp">
                            </div>
                            <div class="col-md-4">
                                <label for="email">Email Address*</label>
                                <input type="text" name="email" id="email" value="{{ isset($doctor->email) ? $doctor->email : '-' }}" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label for="birth_date">Birth Date</label>
                                <input type="date" name="birth_date" class="form-control" id="birth_date" aria-describedby="emailHelp" value="{{ isset($doctor->birth_date) ? $doctor->birth_date : '-' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="year_of_experience">Total Experience in years*</label>
                                <input type="text" name="total_experience" class="form-control" id="year_of_experience" name="total_experience" value="{{ isset($doctor->doctorDetail->experience_year) ? $doctor->doctorDetail->experience_year : '-' }}" aria-describedby="emailHelp">
                            </div>
                            <div class="col-md-4">
                                <label for="gender">Gender*</label>
                                <select class="form-control parent_type rounded" name="gender" id="gender" required>
                                    <option value="male" {{ isset($doctor->gender) && $doctor->gender == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ isset($doctor->gender) && $doctor->gender == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="city_id">City*</label>
                                <select class="form-control parent_type rounded" name="city_id" id="city_id" required>
                                    @foreach ($locations as $location)
                                        <option value="{{$location->id}}" {{ isset($doctor->city_id) && $doctor->city_id == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="image">Profile Image*</label>
                                @if($doctor->image)
                                    <a href="https://ms-images.s3.ap-southeast-1.amazonaws.com/{{$doctor->image}}" target="_blank">View Profile</a>
                                @endif
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="image" class="custom-file-input" id="image">
                                        <label class="custom-file-label" for="image">Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <button class="btn doctorFileBtn" type="button">Upload</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="doctor_type">Doctor Type*</label>
                                <select class="form-control parent_type rounded" name="is_staff" id="is_staff" required>
                                    <option value="1" {{ isset($doctor->doctorDetail->is_staff) && $doctor->doctorDetail->is_staff == '1' ? 'selected' : '' }}>Staff</option>
                                    <option value="0" {{ isset($doctor->doctorDetail->is_staff) && $doctor->doctorDetail->is_staff == '0' ? 'selected' : '' }}>External</option>
                                </select>
                            </div>
                        </div>
                        <h4 class="mt-4">Practice Details</h4>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="about">Introduction about myself</label>
                                <textarea style="resize: none" class="form-control" name="about" id="about" rows="5" placeholder="This will be drafted for you from the information you have provided, however, please do let us know a little bit more about yourself.">{{isset($doctor->doctorDetail->about) ? $doctor->doctorDetail->about : '-'}}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="pmc_no">PMDC Number* (Optional for certain specialities)</label>
                                <input type="text" name="pmc_no" class="form-control" value="{{ isset($doctor->doctorDetail->pmc_no) ? $doctor->doctorDetail->pmc_no : '-' }}">
                            </div>
                        </div>
                        <div id="specialityDiv">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="speciality">Select your speciality*</label>
                                    <select name="speciality[]" id="speciality" class="form-control">
                                        @foreach ($specialities as $speciality)
                                            <option value="{{ $speciality->id }}" {{(isset($doctor->doctorSpecialities) && in_array($speciality->id, $doctor->doctorSpecialities->pluck('speciality_id')->toArray())) ? 'selected' : ''}}>{{ $speciality->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="condition">Select condition you treat <i class="fa fa-info-circle"></i></label>
                                    <select name="condition[]" id="condition" class="form-control" multiple="multiple">
                                        @foreach ($conditions as $disease)
                                            <option value="{{ $disease->id }}" {{(isset($doctor->doctorConditions) && in_array($disease->id, $doctor->doctorConditions->pluck('disease_id')->toArray())) ? 'selected' : ''}}>{{ $disease->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="service">Select your service*</label>
                                    <select name="service[]" id="service" class="form-control">
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}" {{(isset($doctor->doctorServices) && in_array($service->id, $doctor->doctorServices->pluck('service_id')->toArray())) ? 'selected' : ''}}>{{ $service->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-primary float-right mt-4" id="add_more_speciality">Add Another Speciality</button>
                                </div>
                            </div>
                        </div>
                        <h4 class="mt-4">Bank Details</h4>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="account_name">Account title</label>
                                <input type="account_name" name="account_name" class="form-control" value="{{ isset($doctor->doctorBankDetails->account_name) ? $doctor->doctorBankDetails->account_name : '-' }}">
                            </div>
                            <div class="col-md-6">
                                <label for="iban">Account number/IBAN*</label>
                                <input type="text" name="iban" class="form-control" value="{{ isset($doctor->doctorBankDetails->account_number) ? $doctor->doctorBankDetails->account_number : '-' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="bank_name">Bank name*</label>
                                <input type="text" name="bank_name" class="form-control" value="{{ isset($doctor->doctorBankDetails->bank_name) ? $doctor->doctorBankDetails->bank_name : '-' }}">
                            </div>
                            <div class="col-md-6">
                                <label for="cnic">CNIC*</label>
                                <input type="text" name="cnic" class="form-control" value="{{ isset($doctor->doctorDetail->cnic) ? $doctor->doctorDetail->cnic : '-' }}">
                            </div>
                        </div>
                    </div>
                    
                    <div id="qualification" class="tabcontent border-0">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="mt-4">Qualifications</h4>
                                <button type="button" class="btn btn-primary float-right" id="add_more_qualification">Add More</button>
                            </div>
                        </div>
                        
                        <div id="qualificationDiv">
                            
                            @if ($doctor->doctorEducation->isEmpty())
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="degree">Degree*</label>
                                        <select name="degree[]" id="degree" class="form-control">
                                            @foreach ($degrees as $degree)
                                                <option value="{{ $degree->id }}" {{(in_array($degree->id, $doctor->doctorEducation->pluck('degree')->toArray())) ? 'selected' : ''}}>{{ $degree->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="institute_degree">Institute or University*</label>
                                        <select name="institute_degree[]" id="institute_degree" class="form-control">
                                            @foreach ($universities as $university)
                                                <option value="{{ $university->id }}" {{(in_array($university->id, $doctor->doctorEducation->pluck('institute')->toArray())) ? 'selected' : ''}}>{{ $university->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="year_of_completion">Year Of Completion*</label>
                                        <input type="text" name="year_of_completion[]" id="year_of_completion" class="form-control" value="">
                                    </div>
                                </div>
                            @else
                                @foreach ($doctor->doctorEducation as $education)
                                    
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="degree">Degree*</label>
                                            <select name="degree[]" id="degree" class="form-control">
                                                @foreach ($degrees as $degree)
                                                    <option value="{{ $degree->id }}" {{ $education->degree == $degree->name ? 'selected' : '' }}>{{ $degree->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="institute_degree">Institute or University*</label>
                                            <select name="institute_degree[]" id="institute_degree" class="form-control">
                                                @foreach ($universities as $university)
                                                    <option value="{{ $university->id }}" {{ $education->institute == $university->name ? 'selected' : '' }}>{{ $university->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="year_of_completion">Year Of Completion*</label>
                                            <input type="text" name="year_of_completion[]" id="year_of_completion" class="form-control" value="{{ $education->year_of_completion }}">
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary float-right" id="add_more_experience">Add More</button>
                            </div>
                        </div>
                        <div id="experienceDiv">
                            @if ($doctor->doctorExperiences->isEmpty())
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="designation">Designation*</label>
                                        <select name="designation[]" id="designation" class="form-control">
                                            @foreach ($positions as $position)
                                                <option value="{{ $position }}" {{(isset($doctor->DoctorExperience) && in_array($position, $doctor->DoctorExperience->pluck('position')->toArray())) ? 'selected' : ''}}>{{ $position }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="institute_experience">Institute*</label>
                                        <select name="institute_experience[]" id="institute_experience" class="form-control">
                                            @foreach ($universities as $university)
                                                <option value="{{ $university->id }}" {{(isset($doctor->DoctorExperience) && in_array($university->name, $doctor->DoctorExperience->pluck('institute')->toArray())) ? 'selected' : ''}}>{{ $university->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="start_year">Start Year*</label>
                                        <input type="text" name="start_year[]" id="start_year" class="form-control" value="">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-6">
                                                <label for="end_year">End Year*</label>
                                            </div>
                                            <div class="col-6">
                                                <!-- <label for="currently_working">Currently working here &nbsp;&nbsp;</label><input type="checkbox" name="end_year" id="end_year"> -->
                                            </div>
                                        </div>
                                        <input type="text" name="end_year[]" id="end_year" class="form-control" value="">
                                    </div>
                                </div>
                            @else
                                @foreach ($doctor->doctorExperiences as $experience)
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="designation">Designation*</label>
                                            <select name="designation[]" id="designation" class="form-control">
                                                @foreach ($positions as $position)
                                                    <option value="{{ $position }}" {{ $experience->position == $position ? 'selected' : '' }}>{{ $position }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="institute_experience">Institute*</label>
                                            <select name="institute_experience" id="institute_experience" class="form-control">
                                                @foreach ($universities as $university)
                                                    <option value="{{ $university->id }}" {{ $experience->institute == $university->id ? 'selected' : '' }}>{{ $university->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="start_year">Start Year*</label>
                                            <input type="text" name="start_year[]" id="start_year" class="form-control" value="{{ $experience->start_year }}">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-6">
                                                    <label for="end_year">End Year*</label>
                                                </div>
                                                <div class="col-6">
                                                    <!-- <label for="currently_working">Currently working here &nbsp;&nbsp;</label><input type="checkbox" name="end_year" id="end_year" value="0" {{ ($experience->is_completed == 1) ? 'checked' : ''}}> -->
                                                </div>
                                            </div>
                                            <input type="text" name="end_year[]" id="end_year" class="form-control" value="{{ $experience->end_year }}">
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div id="consultation" class="tabcontent">
                        <div class="row video_consult_append">
                            <div class="col-md-12">
                                    <label for="video_consultation" class="d-flex align-items-center">
                                        <input type="checkbox" name="video_consultation" id="video_consultation">
                                        <h5>Video Consultations</h5>
                                    </label>
                                <p>Consult patients online through video calls</p>
                            </div>
                        </div>
                        <div class="row clinic_consult_append">
                            <div class="col-md-12">
                                <label for="clinic_consultation" class="d-flex align-items-center">
                                <input type="checkbox" name="clinic_consultation" id="clinic_consultation">
                                <h5>Clinic Visits</h5>
                            </label>
                                <p>Consult patients in-person at your clinic</p>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary float-right px-4">Save</button>
                </form>
            </div>
        </div>
</div>
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .custom-file-input:lang(en)~.custom-file-label::after{
            font-family: 'Font Awesome 5 Free';
            content: '\f019' !important;
            font-weight: 900;
        }
        .tablinks.doctorTabBtns{
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            background-color: #F7F7F7;
            padding: 15px;
            width: 250px;
        }
        .tablinks.doctorTabBtns.active{
            background-color: #1FA7A8;
            color: white;
            border-bottom: none;
        }
        .doctorFileBtn{
            background: #19B3B5;
            color: #fff;
        }
    </style>
@endpush
@push('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#condition").each(function(index, item){
                var multipleCancelButton = new Choices(item, {
                    removeItemButton: true,
                    maxItemCount:5,
                    searchResultLimit:5,
                    renderChoiceLimit:5
                });
            });
            openTab(event, 'about')
        })
        function openTab(evt, cityName) {
            {{-- var route_slug = 'view-'+cityName.toLowerCase()+'/{{$result->doctor_id}}'; --}}
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            $('#'+cityName+'_tab').addClass('active');
        }
        $(document).ready(function(){
            $("#video_consultation").click(function(){
            if($('#video_consultation').is(":checked")){
            var video_consultation = `<div class="box_border">
                        <div class="row items_div">
                            <div class="col-md-6 mb-3">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                    <label>Select Duration (in minutes)</label>
                                </div>
                                <input type="number" class="form-control" name="consultation_duration_video" id="consultation_duration_video" value="" />
                            </div>
                            @can('doctor-management-shift-consultation-fees')
                            <div class="col-md-6 mb-3">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                    <label>Consultation Fees</label>
                                </div>
                                <input type="text" class="form-control" name="consultation_fee_video" id="consultation_fee_video" value="" />
                            </div>
                            @endcan
                            @can('doctor-management-shift-select-days')
                            <div class="col-md-12 mb-3">
                                <label>Consultation Days*</label>
                            </div>
                            <div class="col-md-12 mb-3">
                                @foreach ($days as $key => $day)
                                    <div class="form-check form-check-inline p-2" style="border: 1px solid #19B3B5;">
                                        <input class="form-check-input" type="checkbox" name="days_video[]" id="days_{{$key}}" value="{{$day}}">
                                        <label class="form-check-label" for="days_{{$key}}">{{ucfirst($day)}}</label>
                                    </div>
                                @endforeach
                            </div>
                            @endcan
                            @can('doctor-management-shift-time-in')
                            <div class="col-md-6 mb-3">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                    <label>Start Time*</label>
                                </div>
                                <input type="text" class="form-control timepicker" name="start_time_video" id="start_time" />
                            </div>
                            @endcan
                            @can('doctor-management-shift-time-out')
                            <div class="col-md-6 mb-3">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                    <label>End Time*</label>
                                </div>
                                <input type="text" class="form-control timepicker" name="end_time_video" id="end_time" />
                            </div>
                            @endcan
                        </div>
                    </div>`;
            $(".video_consult_append").append(video_consultation);
            }
            else{
                $(".video_consult_append").find(".box_border").remove();
            }
        });

        $("#clinic_consultation").click(function(){
            if($('#clinic_consultation').is(":checked")){
            var clinic_consultation = `<div class="box_border">
                        <div class="row items_div">
                            <div class="col-md-12 mb-3">
                                <select name="clinic[]" class="form-control">
                                @foreach ($clinics as $clinic)
                                    <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                    <label>Select Duration (in minutes)</label>
                                </div>
                                <input type="number" class="form-control" name="consultation_duration" id="consultation_duration" value="" />
                            </div>
                            @can('doctor-management-shift-consultation-fees')
                            <div class="col-md-6 mb-3">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                    <label>Consultation Fees</label>
                                </div>
                                <input type="text" class="form-control" name="consultation_fee" id="consultation_fee" value="" />
                            </div>
                            @endcan
                            @can('doctor-management-shift-select-days')
                            <div class="col-md-12 mb-3">
                                <label>Consultation Days*</label>
                            </div>
                            <div class="col-md-12 mb-3">
                                @foreach ($days as $key => $day)
                                    <div class="form-check form-check-inline p-2" style="border: 1px solid #19B3B5;">
                                        <input class="form-check-input" type="checkbox" name="days[]" id="days_{{$key}}" value="{{$day}}">
                                        <label class="form-check-label" for="days_{{$key}}">{{ucfirst($day)}}</label>
                                    </div>
                                @endforeach
                            </div>
                            @endcan
                            @can('doctor-management-shift-time-in')
                            <div class="col-md-6 mb-3">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                    <label>Start Time*</label>
                                </div>
                                <input type="text" class="form-control timepicker" name="start_time" id="start_time" />
                            </div>
                            @endcan
                            @can('doctor-management-shift-time-out')
                            <div class="col-md-6 mb-3">
                                <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                    <label>End Time*</label>
                                </div>
                                <input type="text" class="form-control timepicker" name="end_time" id="end_time" />
                            </div>
                            @endcan
                        </div>
                    </div>`;
            $(".clinic_consult_append").append(clinic_consultation);
            }
            else{
                $(".clinic_consult_append").find(".box_border").remove();
            }
        });
    });
    $("#add_more_speciality").click(function(){
            var speciality = `<div class="form-group row">
                        <div class="col-md-6">
                            <label for="speciality">Select your speciality*</label>
                            <select name="speciality[]" id="speciality" class="form-control">
                                @foreach ($specialities as $speciality)
                                    <option value="{{ $speciality->id }}">{{ $speciality->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="condition">Select condition you treat <i class="fa fa-info-circle"></i></label>
                            <select name="condition[]" id="condition" class="form-control" multiple="multiple">
                                @foreach ($conditions as $disease)
                                    <option value="{{ $disease->id }}">{{ $disease->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="service">Select your service*</label>
                            <select name="service[]" id="service" class="form-control">
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary float-right mt-4" id="add_more_speciality">Add Another Speciality</button>
                        </div>
                    </div>`;
            $("#specialityDiv").append(speciality);
            $(this).remove();
        });
        $("#add_more_qualification").click(function(){
            var qualification = `<div class="form-group row">
                    <div class="col-md-6">
                        <label for="degree">Degree*</label>
                        <select name="degree[]" id="degree" class="form-control">
                            @foreach ($degrees as $degree)
                                <option value="{{ $degree->id }}">{{ $degree->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="institute_degree">Institute or University*</label>
                        <select name="institute_degree[]" id="institute_degree" class="form-control">
                            @foreach ($universities as $university)
                                <option value="{{ $university->id }}">{{ $university->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                    <div class="form-group row">
                    <div class="col-md-6">
                        <label for="year_of_completion">Year Of Completion*</label>
                        <input type="text" name="year_of_completion[]" id="year_of_completion" class="form-control" value="">
                    </div>
                </div>`;
            $("#qualificationDiv").append(qualification);
        });
        $("#add_more_experience").click(function(){
            var experience = `<div class="form-group row">
                    <div class="col-md-6">
                        <label for="designation">Designation*</label>
                        <select name="designation[]" id="designation" class="form-control">
                            @foreach ($positions as $position)
                                <option value="{{ $position }}">{{ $position }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="institute_experience">Institute*</label>
                        <select name="institute_experience[]" id="institute_experience" class="form-control">
                            @foreach ($universities as $university)
                                <option value="{{ $university->id }}">{{ $university->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                    <div class="form-group row">
                    <div class="col-md-6">
                        <label for="start_year">Start Year*</label>
                        <input type="text" name="start_year[]" id="start_year" class="form-control" value="">
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-6">
                                <label for="end_year">End Year*</label>
                            </div>
                            <div class="col-6">
                            </div>
                        </div>
                        <input type="text" name="end_year[]" id="end_year" class="form-control" value="">
                    </div>
                </div>`;
                $('#experienceDiv').append(experience);
        });
    </script>
@endpush
