@extends('layouts.admin.app')
@section('page_header')
{{ $page_header }}
@endsection
@section('content')
<div class="container-fluid animatedParent animateOnce my-3">
    <div class="animated fadeInUpShort">
        <form method="post" class="needs-validation" id="disable_enter_submit" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="container-fluid animatedParent animateOnce my-3">
                <div class="animated fadeInUpShort">
                    <div class="row">
                        <div class="col-md-8">

                            <div class="col-md-12 p-0">
                                <div class="box_border">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h5 class="box_heading">General Information</h5>
                                        </div>
                                        <input type="hidden" name="user_id" value="{{ $result ? $result->doctor_id : null }}">
                                        <input type="hidden" name="is_login_credentials_sent" value="{{ $result ? $result->is_login_credentials_sent : null }}">

                                        <div class="col-md-12 mb-3">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" id="name" value="{{ $result ? $result->user->name : old('name') }}" class="form-control" placeholder="Doctor Name" required>
                                            @error('name')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="name">Prefix</label>
                                            <select class="form-control parent_type" name="prefix" id="prefix" required>
                                                <option value="Dr">Dr</option>
                                                <option value="Mr">Prof</option>
                                            </select>
                                            @error('prefix')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label for="type">Experience Year</label>
                                            <input type="number" name="experience_year" id="experience_year" value="{{ $result ? $result->experience_year : old('experience_year') }}" class="form-control" placeholder="Enter Experience Year" required>
                                            @error('experience_year')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label for="category">PMC Number</label>
                                            <input type="text" name="pmc_no" id="pmc_no" value="{{ $result ? $result->pmc_no : old('pmc_no') }}" class="form-control" placeholder="Enter PMC Number" required>
                                            @error('pmc_no')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="parents">Badge</label>
                                            <select class="form-control badge" name="badge" id="badge">
                                                <option value="">No Badge selected</option>
                                                <option {{($result && $result->badge == "silver")? "selected":""}} value="silver">Silver</option>
                                                <option {{($result && $result->badge == "gold")? "selected":""}} value="gold">Gold</option>
                                                <option {{($result && $result->badge == "platinum")? "selected":""}} value="platinum">Platinum</option>
                                            </select>
                                            @error('badge')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>




                                        <div class="col-md-12 mb-3">
                                            <label for="city">City</label>
                                            <select class="form-control badge" name="city" id="city">
                                                @foreach ($cities as $city)
                                                <option {{($result && $result->user->city_id == $city->id)? "selected":""}} value="{{$city->id}}">{{$city->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('badge')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>















                                        <div class="col-md-12 mb-3">
                                            <label for="keywords">About</label>
                                            <textarea name="about" id="about" class="form-control" placeholder="Enter about" required>{{ $result ? $result->about : old('about') }}
                                            </textarea>
                                            @error('about')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label for="descripton">Waiting Time (in minutes)</label>
                                            <input type="number" name="waiting_time" id="waiting_time" value="{{ $result ? $result->waiting_time : old('waiting_time') }}" class="form-control" placeholder="Enter waiting time" required>
                                            @error('waiting_time')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- <div class="col-md-12 mb-3">
                                        <label for="descripton">Consultation Duration</label>
                                        <input type="time" name="consultation_duration" id="consultation_duration" value="{{ $result ? $result->consultation_duration : old('consultation_duration') }}" class="form-control" placeholder="Enter Consultation Duration" required>
                                        @error('consultation_duration')
                                        <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div> --}}

                                    <div class="col-md-12">
                                        <div class="col-md-6 px-0 mb-2">
                                            <label for="is_available" class="d-block">Is Avaliable</label>
                                            @error('is_available')<div class="validation-error"> {{ $message }}</div> @enderror
                                            <div class="custom__radio mb-3">
                                                <div class="d-flex box p-0 justify-content-between form-check">
                                                    <div class="w-100">
                                                        <input class="form-check-input" name="is_available" type="radio" @if ($result) @if ($result->is_available == 1) checked @endif @else checked @endif value="1" id="avaliable_enable">
                                                        <label class="form-check-label" for="avaliable_enable">
                                                            Yes
                                                        </label>
                                                    </div>
                                                    <div class="w-100">
                                                        <input class="form-check-input" type="radio" name="is_available" value="0" @if ($result) @if ($result->is_available == 0) checked @endif @endif id="avaliable_disable">
                                                        <label class="form-check-label" for="avaliable_disable">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="col-md-6 px-0 mb-2">
                                            <label for="is_physical_consultancy" class="d-block">Is Physical Consultancy</label>
                                            @error('is_physical_consultancy')<div class="validation-error"> {{ $message }}</div> @enderror
                                            <div class="custom__radio mb-3">
                                                <div class="d-flex box p-0 justify-content-between form-check">
                                                    <div class="w-100">
                                                        <input class="form-check-input" name="is_physical_consultancy" type="radio" @if ($result) @if ($result->is_physical_consultancy == 1) checked @endif @else checked @endif value="1" id="phy_enable">
                                                        <label class="form-check-label" for="phy_enable">
                                                            Yes
                                                        </label>
                                                    </div>
                                                    <div class="w-100">
                                                        <input class="form-check-input" type="radio" name="is_physical_consultancy" value="0" @if ($result) @if ($result->is_physical_consultancy == 0) checked @endif @endif id="phy_disable">
                                                        <label class="form-check-label" for="phy_disable">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="col-md-6 px-0 mb-2">
                                            <label for="is_video_consultancy" class="d-block">Is Video Consultancy</label>
                                            @error('is_video_consultancy')<div class="validation-error"> {{ $message }}</div> @enderror
                                            <div class="custom__radio mb-3">
                                                <div class="d-flex box p-0 justify-content-between form-check">
                                                    <div class="w-100">
                                                        <input class="form-check-input" name="is_video_consultancy" type="radio" @if ($result) @if ($result->is_video_consultancy == 1) checked @endif @else checked @endif value="1" id="vid_enable">
                                                        <label class="form-check-label" for="vid_enable">
                                                            Yes
                                                        </label>
                                                    </div>
                                                    <div class="w-100">
                                                        <input class="form-check-input" type="radio" name="is_video_consultancy" value="0" @if ($result) @if ($result->is_video_consultancy == 0) checked @endif @endif id="vid_disable">
                                                        <label class="form-check-label" for="vid_disable">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="col-md-6 px-0 mb-2">
                                            <label for="is_blocked" class="d-block">Is Blocked</label>
                                            @error('is_blocked')<div class="validation-error"> {{ $message }}</div> @enderror
                                            <div class="custom__radio mb-3">
                                                <div class="d-flex box p-0 justify-content-between form-check">
                                                    <div class="w-100">
                                                        <input class="form-check-input" name="is_blocked" type="radio" @if ($result) @if ($result->user->is_blocked == 1) checked @endif @else checked @endif value="1" id="is_blocked_enable">
                                                        <label class="form-check-label" for="is_blocked_enable">
                                                            Yes
                                                        </label>
                                                    </div>
                                                    <div class="w-100">
                                                        <input class="form-check-input" type="radio" name="is_blocked" value="0" @if ($result) @if ($result->user->is_blocked == 0) checked @endif @endif id="is_blocked_disable">
                                                        <label class="form-check-label" for="is_blocked_disable">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="col-md-6 px-0 mb-2">
                                            <label for="is_instant_consultation" class="d-block">Is Instant Consultation</label>
                                            @error('is_instant_consultation')<div class="validation-error"> {{ $message }}</div> @enderror
                                            <div class="custom__radio mb-3">
                                                <div class="d-flex box p-0 justify-content-between form-check">
                                                    <div class="w-100">
                                                        <input class="form-check-input" name="is_instant_consultation" type="radio" @if ($result) @if ($result->is_instant_consultation == 1) checked @endif @else checked @endif value="1" id="is_instant_consultation_enable">
                                                        <label class="form-check-label" for="is_instant_consultation_enable">
                                                            Yes
                                                        </label>
                                                    </div>
                                                    <div class="w-100">
                                                        <input class="form-check-input" type="radio" name="is_instant_consultation" value="0" @if ($result) @if ($result->is_instant_consultation == 0) checked @endif @endif id="is_instant_consultation_disable">
                                                        <label class="form-check-label" for="is_instant_consultation_disable">
                                                            No
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-2 px-4">
                                        <label for="image">Image<span class="text-danger">*</span></label>
                                        <input type="file" data-max-file-size="2M" name="image" id="image" value="{{ $result ? env('ASSETS_STORAGE').$result->user->image : null }}" data-default-file="{{ $result ? env('ASSETS_STORAGE'). $result->user->image : '' }}" class="dropify">
                                        @error('image')
                                        <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>

                                    @if(auth()->user()->role_id == (new App\Http\Common\Constant)->SUPER_ADMIN_ROLE_ID)
                                    <div class="col-md-12">
                                        <div class="col-md-6 px-0 mb-2">
                                            <label for="status" class="d-block">Status</label>
                                            @error('status')<div class="validation-error"> {{ $message }}</div> @enderror
                                            <div class="custom__radio mb-3">
                                                <div class="d-flex box p-0 justify-content-between form-check">
                                                    <div class="w-100">
                                                        <input class="form-check-input" name="status" type="radio" @if ($result) @if ($result->user->status == 1) checked @endif @else checked @endif value="1" id="enable">
                                                        <label class="form-check-label" for="enable">
                                                            Active
                                                        </label>
                                                    </div>
                                                    <div class="w-100">
                                                        <input class="form-check-input" type="radio" name="status" value="0" @if ($result) @if ($result->user->status == 0) checked @endif @endif id="disable">
                                                        <label class="form-check-label" for="disable">
                                                            Inactive
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                            </div>

                        </div>




                    </div>
                    <div class="col-md-4 p-0 m-0">

                        {{-- SERVICES --}}
                        <div class="col-md-12 p-0">
                            <div class="box_border">
                                <div class="row">
                                    <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="box_heading mb-0">Services</h5>
                                        <button type="button" class="btn-success btn-sm btn mb-0 cursor-pointer edit_sevices">
                                            <i class="icon-pencil"></i>
                                            Edit
                                        </button>
                                    </div>

                                    <div class="col-md-12">
                                        @if($result->user->doctorServices && count($result->user->doctorServices) > 0)
                                        @foreach ($result->user->doctorServices as $service)
                                        <li>{{$service->service->name}}</li>
                                        @endforeach
                                        @else
                                        <li>No services</li>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Specialities --}}
                        <div class="col-md-12 p-0">
                            <div class="box_border">
                                <div class="row">

                                    <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="box_heading mb-0">Specialities</h5>
                                        <button type="button" class="btn-success btn-sm btn mb-0 cursor-pointer edit_specialities">
                                            <i class="icon-pencil"></i>
                                            Edit
                                        </button>
                                    </div>

                                    <div class="col-md-12">
                                        @if($result->user->doctorSpecialities && count($result->user->doctorSpecialities) > 0)
                                        @foreach ($result->user->doctorSpecialities as $speciality)
                                        <li>{{$speciality->speciality->name}}</li>
                                        @endforeach
                                        @else
                                        <li>No Specialities</li>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Experiences --}}
                        <div class="col-md-12 p-0">
                            <div class="box_border">
                                <div class="row">
                                    <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="box_heading mb-0">Experiences</h5>
                                        <button type="button" class="btn-success btn-sm btn mb-0 cursor-pointer edit_experiences">
                                            <i class="icon-pencil"></i>
                                            Edit
                                        </button>
                                    </div>
                                    <div class="col-md-12">
                                        @if($result->user->doctorExperiences && count($result->user->doctorExperiences) > 0)
                                        @foreach ($result->user->doctorExperiences as $experience)
                                        <li>{{$experience->position ." at ". $experience->institute ." from ". $experience->start_year ." till ". ($experience->end_year ?? "current")}}</li>
                                        @endforeach
                                        @else
                                        <li>No Experience</li>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Educations --}}
                        <div class="col-md-12 p-0">
                            <div class="box_border">
                                <div class="row">
                                    <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="box_heading mb-0">Educations</h5>
                                        <button type="button" class="btn-success btn-sm btn mb-0 cursor-pointer edit_educations">
                                            <i class="icon-pencil"></i>
                                            Edit
                                        </button>
                                    </div>

                                    <div class="col-md-12">
                                        @if($result->user->doctorEducation && count($result->user->doctorEducation) > 0)
                                        @foreach ($result->user->doctorEducation as $education)
                                        <li>{{$education->degree ." from ". $education->institute . ($education->is_completed ?  " in ".$education->year_of_completion : " (currently enrolled)")}}</li>
                                        @endforeach
                                        @else
                                        <li>No Education</li>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Reviews --}}
                        <div class="col-md-12 p-0">
                            <div class="box_border">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="box_heading">Ratings </h5>
                                    </div>

                                    <div class="col-md-12">
                                        @php
                                        $reviews = App\Models\Review::getReviews($result->id)
                                        @endphp
                                        @if($reviews && count($reviews) > 0)
                                        @foreach ($reviews as $key => $review)
                                        @if($key != 'reviews')
                                        <li>{{\Str::headline($key)}} : {{$review}}</li>
                                        @endif
                                        @endforeach
                                        @if($reviews['reviews'] && count($reviews['reviews']) > 0)
                                        <br>
                                        <h5>Reviews</h5>
                                        @foreach ($reviews['reviews'] as $review_text_key => $review_text)
                                        <li>{{$review_text->description}}</li>
                                        @endforeach
                                        @endif
                                        @else
                                        <li>No Reviews</li>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>


                <div class="bg-transparent">
                    <button type="submit" class="cst_btn px-5 btn-sm">
                        {{-- <i class="icon-save"></i> --}}
                        {{ $result ? 'Update' : 'Save' }}
                    </button>
                </div>
        </form>
    </div>

    <div class="modal fade" id="edit_doctor_details_modal" tabindex="-1" role="dialog" aria-labelledby="edit_doctor_details_modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
</div>


@endsection
@push('scripts')
<script>
    var doctor_id = "{{$result->doctor_id}}"
    $('.edit_sevices').click(function() {
        let url = ("{{ route('doctor-update-services', ['doctor_id' => '-id-']) }}").replace('-id-', doctor_id);
        open_iframe(url);
    })

    $('.edit_specialities').click(function() {
        let url = ("{{ route('doctor-update-specialities', ['doctor_id' => '-id-']) }}").replace('-id-', doctor_id);
        open_iframe(url);
    })

    $('.edit_experiences').click(function() {
        let url = ("{{ route('doctor-update-experiences', ['doctor_id' => '-id-']) }}").replace('-id-', doctor_id);
        open_iframe(url);
    })

    $('.edit_educations').click(function() {
        let url = ("{{ route('doctor-update-educations', ['doctor_id' => '-id-']) }}").replace('-id-', doctor_id);
        open_iframe(url);
    })



    function open_iframe(url) {
        var height = screen.height * 0.8;
        var html = '<iframe id="frame" height=' + height + ' src="' + url + '"> </iframe>'
        $('#edit_doctor_details_modal').modal('show');
        $('#edit_doctor_details_modal').children().children().html(html);
    }

</script>
{{-- $('.role').change(function() {
            var role_id = $(this).val();
            if (role_id == "{{ App\User::PICKUP_DISPATCHER_ROLE }}") {
$('.city').removeClass('d-none')
$('.city_dropdown').attr('required', 'required');
} else if (role_id == "{{ App\User::DELIVERY_TRANSPORTER_ROLE }}") {
$('.region').removeClass('d-none')
$('.region_dropdown').attr('required', 'required');
} else if (role_id == "{{ App\User::OUTLET_MANAGER_ROLE }}") {
$('.outlet').removeClass('d-none')
$('.outlet_dropdown').attr('required', 'required');
} else {
$('.city_dropdown').removeAttr('required');
$('.city').addClass('d-none')
$('.region_dropdown').removeAttr('required');
$('.region').addClass('d-none')
$('.outlet_dropdown').removeAttr('required');
$('.outlet').addClass('d-none')
}
}) --}}
@endpush
