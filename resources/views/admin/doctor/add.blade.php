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
                        <div class="col-md-12">

                            <div class="col-md-12 p-0">
                                <div class="box_border">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="box_heading">Add Doctor</h5>
                                        </div>
                                        <input type="hidden" name="user_id" value="{{ $result ? $result->doctor_id : null }}">
                                        <input type="hidden" name="is_login_credentials_sent" value="{{ $result ? $result->is_login_credentials_sent : null }}">
                                        @can('doctor-management-add-name')
                                        <div class="col-md-6 d-md-flex align-items-center mb-3">
                                            <div class="col-md-4">
                                                <label for="name">Name</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" name="name" id="name" value="{{ $result ? $result->user->name : old('name') }}" class="form-control" placeholder="Doctor Name" required>
                                                @error('name')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @endcan
                                        @can('doctor-management-add-phone-number')
                                        <div class="col-md-6 d-md-flex align-items-center mb-3">
                                            <div class="col-md-4">
                                                <label for="name">Phone Number</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" name="phone" id="phone" value="{{ $result ? $result->user->phone : old('phone') }}" class="form-control" placeholder="Doctor Phone Number" required>
                                                @error('phone')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @endcan
                                        @can('doctor-management-add-email')
                                        <div class="col-md-6 d-md-flex align-items-center mb-3">
                                            <div class="col-md-4">
                                                <label for="email">Email</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="email" name="email" id="email" value="{{ $result ? $result->user->email : old('email') }}" class="form-control" placeholder="Doctor Email" required>
                                                @error('email')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @endcan
                                        @can('doctor-management-add-pmc')
                                        <div class="col-md-6 d-md-flex align-items-center mb-3">
                                            <div class="col-md-4">
                                                <label for="pmc_no">PMC Number</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" name="pmc_no" id="pmc_no" value="{{ $result ? $result->pmc_no : old('pmc_no') }}" class="form-control" placeholder="Enter PMC Number" required>
                                                @error('pmc_no')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @endcan
                                        @can('doctor-management-add-experience-year')
                                        <div class="col-md-6 d-md-flex align-items-center mb-3">
                                            <div class="col-md-4">
                                                <label for="experience_year">Experience Year</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="number" name="experience_year" id="experience_year" value="{{ $result ? $result->experience_year : old('experience_year') }}" class="form-control" placeholder="Enter Experience Year" required>
                                                @error('experience_year')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @endcan
                                        @can('doctor-management-add-prefix')
                                        <div class="col-md-6 d-md-flex align-items-center mb-3">
                                            <div class="col-md-4">
                                                <label for="name">Prefix</label>
                                            </div>
                                            <div class="col-md-8">
                                                <select class="form-control parent_type" name="prefix" id="prefix" required>
                                                    <option value="Dr">Dr</option>
                                                    <option value="Mr">Prof</option>
                                                </select>
                                                @error('prefix')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endcan    
                                        </div>









{{--                                        <div class="col-md-12 mb-3">--}}
{{--                                            <label for="parents">Speciality</label>--}}
{{--                                            <select class="form-control badge" name="speciality" id="speciality">--}}
{{--                                                @foreach ($specialities as $speciality)--}}
{{--                                                    <option {{($result && $result->speciality->speciality_id == $speciality->id)? "selected":""}} value="{{$speciality->id}}">{{$speciality->name}}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                            @error('speciality')--}}
{{--                                            <div class="validation-error"> {{ $message }}</div>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}

                                        @can('doctor-management-add-location')                   
                                        <div class="col-md-6 d-md-flex align-items-center mb-3">
                                            <div class="col-md-4">
                                                <label for="city">Location</label>
                                            </div>
                                            <div class="col-md-8">
                                                <select class="form-control badge" name="city" id="city">
                                                    @foreach ($cities as $city)
                                                    <option {{($result && $result->user->city_id == $city->id)? "selected":""}} value="{{$city->id}}">{{$city->name}}</option>
                                                    @endforeach
                                                </select>
                                                @error('city')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @endcan
                                        @can('doctor-management-add-gender')
                                        <div class="col-md-6 d-md-flex align-items-center mb-3">
                                            <div class="col-md-4">
                                                <label for="gender">Gender</label>
                                            </div>
                                            <div class="col-md-8">
                                                <select class="form-control badge" name="gender" id="gender">
                                                    <option value="" selected>Please select gender</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                    <option value="others">Others</option>
                                                </select>
                                                @error('gender')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @endcan
                                        @can('doctor-management-add-cnic')
                                        <div class="col-md-6 d-md-flex align-items-center mb-3">
                                            <div class="col-md-4">
                                                <label for="cnic">CNIC</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" name="cnic" id="cnic" value="{{ $result ? $result->cnic : old('cnic') }}" class="form-control" placeholder="Enter CNIC Number" required>
                                                @error('cnic')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @endcan
                                        @can('doctor-management-add-about')
                                        <div class="col-md-6 d-md-flex align-items-center mb-3">
                                            <div class="col-md-4">
                                                <label for="keywords">About</label>
                                            </div>
                                            <div class="col-md-8">
                                                <textarea name="about" id="about" class="form-control" placeholder="Enter about" required>{{ $result ? $result->about : old('about') }}
                                                </textarea>
                                                @error('about')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @endcan
                                        @can('doctor-management-add-waiting-time')   
                                        <div class="col-md-6 d-md-flex align-items-center mb-3">
                                            <div class="col-md-4">
                                                <label for="descripton">Waiting Time (in minutes)</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="number" name="waiting_time" id="waiting_time" value="{{ $result ? $result->waiting_time : old('waiting_time') }}" class="form-control" placeholder="Enter waiting time" required>
                                                @error('waiting_time')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @endcan
                                        {{-- <div class="col-md-12 mb-3">
                                        <label for="descripton">Consultation Duration</label>
                                        <input type="time" name="consultation_duration" id="consultation_duration" value="{{ $result ? $result->consultation_duration : old('consultation_duration') }}" class="form-control" placeholder="Enter Consultation Duration" required>
                                        @error('consultation_duration')
                                        <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div> --}}
                                     @can('doctor-management-add-is-available-approved')       
                                    <div class="col-md-6 d-md-flex align-items-center">
                                        <div class="col-md-4 mb-2">
                                            <label for="is_available" class="d-block">Is Avaliable</label>
                                        </div>
                                        <div class="col-md-8">
                                            @error('is_available')<div class="validation-error"> {{ $message }}</div> @enderror
                                            <div class="custom__radio mb-3">
                                                <div class="d-flex box p-0 justify-content-between form-check w-100">
                                                    <div class="w-100">
                                                        <input class="form-check-input" name="is_available" type="radio" @if ($result) @if ($result->is_available == 1) checked @endif @else checked @endif value="1" id="avaliable_enable">
                                                        <label class="form-check-label" for="avaliable_enable">
                                                            Approved
                                                        </label>
                                                    </div>
                                                    <div class="w-100">
                                                        <input class="form-check-input" type="radio" name="is_available" value="0" @if ($result) @if ($result->is_available == 0) checked @endif @endif id="avaliable_disable">
                                                        <label class="form-check-label" for="avaliable_disable">
                                                            Not Approved
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endcan

{{--                                    <div class="col-md-12">--}}
{{--                                        <div class="col-md-6 px-0 mb-2">--}}
{{--                                            <label for="is_physical_consultancy" class="d-block">Is Physical Consultancy</label>--}}
{{--                                            @error('is_physical_consultancy')<div class="validation-error"> {{ $message }}</div> @enderror--}}
{{--                                            <div class="custom__radio mb-3">--}}
{{--                                                <div class="d-flex box p-0 justify-content-between form-check">--}}
{{--                                                    <div class="w-100">--}}
{{--                                                        <input class="form-check-input" name="is_physical_consultancy" type="radio" @if ($result) @if ($result->is_physical_consultancy == 1) checked @endif @else checked @endif value="1" id="phy_enable">--}}
{{--                                                        <label class="form-check-label" for="phy_enable">--}}
{{--                                                            Yes--}}
{{--                                                        </label>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="w-100">--}}
{{--                                                        <input class="form-check-input" type="radio" name="is_physical_consultancy" value="0" @if ($result) @if ($result->is_physical_consultancy == 0) checked @endif @endif id="phy_disable">--}}
{{--                                                        <label class="form-check-label" for="phy_disable">--}}
{{--                                                            No--}}
{{--                                                        </label>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="col-md-12">--}}
{{--                                        <div class="col-md-6 px-0 mb-2">--}}
{{--                                            <label for="is_video_consultancy" class="d-block">Is Video Consultancy</label>--}}
{{--                                            @error('is_video_consultancy')<div class="validation-error"> {{ $message }}</div> @enderror--}}
{{--                                            <div class="custom__radio mb-3">--}}
{{--                                                <div class="d-flex box p-0 justify-content-between form-check">--}}
{{--                                                    <div class="w-100">--}}
{{--                                                        <input class="form-check-input" name="is_video_consultancy" type="radio" @if ($result) @if ($result->is_video_consultancy == 1) checked @endif @else checked @endif value="1" id="vid_enable">--}}
{{--                                                        <label class="form-check-label" for="vid_enable">--}}
{{--                                                            Yes--}}
{{--                                                        </label>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="w-100">--}}
{{--                                                        <input class="form-check-input" type="radio" name="is_video_consultancy" value="0" @if ($result) @if ($result->is_video_consultancy == 0) checked @endif @endif id="vid_disable">--}}
{{--                                                        <label class="form-check-label" for="vid_disable">--}}
{{--                                                            No--}}
{{--                                                        </label>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                        <div class="col-md-12">--}}
{{--                                            <div class="col-md-6 px-0 mb-2">--}}
{{--                                                <label for="status" class="d-block">Account Status</label>--}}
{{--                                                @error('status')<div class="validation-error"> {{ $message }}</div> @enderror--}}
{{--                                                <div class="custom__radio mb-3">--}}
{{--                                                    <div class="d-flex box p-0 justify-content-between form-check">--}}
{{--                                                        <div class="w-100">--}}
{{--                                                            <input class="form-check-input" name="status" type="radio" @if ($result) @if ($result->user->status == 1) checked @endif @else checked @endif value="1" id="enable">--}}
{{--                                                            <label class="form-check-label" for="enable">--}}
{{--                                                                Approved--}}
{{--                                                            </label>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="w-100">--}}
{{--                                                            <input class="form-check-input" type="radio" name="status" value="0" @if ($result) @if ($result->user->status == 0) checked @endif @endif id="disable">--}}
{{--                                                            <label class="form-check-label" for="disable">--}}
{{--                                                                Not Approved--}}
{{--                                                            </label>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                        
                                        <div class="col-md-6 d-md-flex align-items-center">
                                            <div class="col-md-4 mb-2">
                                                <label for="status" class="d-block">Profile Status</label>
                                            </div>
                                            <div class="col-md-8">
                                                @error('status')<div class="validation-error"> {{ $message }}</div> @enderror
                                                <div class="custom__radio mb-3">
                                                    <div class="d-flex box p-0 justify-content-between form-check w-100">
                                                    @can('doctor-management-add-profile-status-verified')    
                                                        <div class="w-100">
                                                            <input class="form-check-input" name="profile_status" type="radio" @if ($result) @if ($result->user->status == 1) checked @endif @else checked @endif value="1" id="profile_enable">
                                                            <label class="form-check-label" for="profile_enable">
                                                                Verified
                                                            </label>
                                                        </div>
                                                    @endcan
                                                    @can('doctor-management-add-profile-status-not-verified')    
                                                        <div class="w-100">
                                                            <input class="form-check-input" type="radio" name="profile_status" value="0" @if ($result) @if ($result->user->status == 0) checked @endif @endif id="profile_disable">
                                                            <label class="form-check-label" for="profile_disable">
                                                                Not Verified
                                                            </label>
                                                        </div>
                                                    @endcan    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @can('doctor-management-add-user-status-Yes/no')        
                                    <div class="col-md-6 d-md-flex align-items-center">
                                        <div class="col-md-4 mb-2">
                                            <label for="is_blocked" class="d-block">User Status</label>
                                        </div>
                                        <div class="col-md-8">
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
                                    @endcan
                                    @can('doctor-management-add-is-instant-consultation-Yes/No')        
                                    <div class="col-md-6 d-md-flex align-items-center">
                                        <div class="col-md-4 mb-2">
                                            <label for="is_instant_consultation" class="d-block">Is Instant Consultation</label>
                                        </div>
                                        <div class="col-md-8">
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
                                    @endcan
                                    @can('doctor-management-add-image')        
                                    <div class="col-md-6 d-md-flex align-items-center mb-2 px-4">
                                        <div class="col-md-4">
                                            <label for="image">Image<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="file" data-max-file-size="2M" name="image" id="image" value="{{ $result ? env('ASSETS_STORAGE').$result->user->image : null }}" data-default-file="{{ $result ? env('ASSETS_STORAGE').$result->user->image : '' }}" class="dropify">
                                            @error('image')
                                            <div class="validation-error"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    @endcan

{{--                                    @if(auth()->user()->role_id == (new App\Http\Common\Constant)->SUPER_ADMIN_ROLE_ID)--}}
{{--                                    <div class="col-md-12">--}}
{{--                                        <div class="col-md-6 px-0 mb-2">--}}
{{--                                            <label for="status" class="d-block">Status</label>--}}
{{--                                            @error('status')<div class="validation-error"> {{ $message }}</div> @enderror--}}
{{--                                            <div class="custom__radio mb-3">--}}
{{--                                                <div class="d-flex box p-0 justify-content-between form-check">--}}
{{--                                                    <div class="w-100">--}}
{{--                                                        <input class="form-check-input" name="status" type="radio" @if ($result) @if ($result->user->status == 1) checked @endif @else checked @endif value="1" id="enable">--}}
{{--                                                        <label class="form-check-label" for="enable">--}}
{{--                                                            Active--}}
{{--                                                        </label>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="w-100">--}}
{{--                                                        <input class="form-check-input" type="radio" name="status" value="0" @if ($result) @if ($result->user->status == 0) checked @endif @endif id="disable">--}}
{{--                                                        <label class="form-check-label" for="disable">--}}
{{--                                                            Inactive--}}
{{--                                                        </label>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    @endif--}}
                                </div>

                            </div>

                        </div>




                    </div>
                </div>

                @can('doctor-management-add-save')
                <div class="bg-transparent">
                    <button type="submit" class="cst_btn px-5 btn-sm">
                        {{-- <i class="icon-save"></i> --}}
                        {{ $result ? 'Update' : 'Save' }}
                    </button>
                </div>
                @endcan
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
