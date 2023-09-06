<h3 class="mb-3">Personal info</h3>
<form method="post" class="needs-validation" id="disable_enter_submit" enctype="multipart/form-data"
      novalidate>
    @csrf
    <input type="hidden" name="user_id" value="{{ $result->fitness_experts_id ? $result->fitness_experts_id : null }}">
    <input type="hidden" name="is_login_credentials_sent" value="{{ $result ? $result->user->is_login_credentials_sent : null }}">
    <div class="row">
        <div class="col-md-2">
            <label for="image">Profile picture<span class="text-danger">*</span></label>
            <input type="file" data-max-file-size="2M" name="image" id="image" value="{{ $result ? env('ASSETS_STORAGE').$result->user->image : null }}" data-default-file="{{ isset($result->user->image) ? env('ASSETS_STORAGE').$result->user->image : '' }}" class="dropify">
            @error('image')
            <div class="validation-error"> {{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-10 row">
            @can('fitness-management-update-wellness-expert-name')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="name">Wellness Expert Name</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="name" id="name" value="{{ $result ? $result->user->name : old('name') }}" class="form-control" placeholder="Wellness Expert Name" required>
                    @error('name')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endcan
            @can('fitness-management-update-prefix')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="name">Prefix</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control parent_type" name="prefix" id="prefix">
                        <option value="">Please Prefix</option>
                        <option value="Dr" {{($result && $result->prefix == "Dr")? "selected":""}}>Dr</option>
                        <option value="Prof" {{($result && $result->prefix == "Prof")? "selected":""}}>Prof</option>
                        <option value="Mr" {{($result && $result->prefix == "Mr")? "selected":""}}>Mr</option>
                        <option value="Ms" {{($result && $result->prefix == "Ms")? "selected":""}}>Ms</option>
                    </select>
                    @error('prefix')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endcan
            @can('fitness-management-update-phone-number')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="phone">Phone Number</label>
                </div>
                <div class="col-md-8">
                    <input type="number" name="phone" id="phone" value="{{ $result ? $result->user->phone : old('phone') }}" class="form-control" placeholder="Enter Phone Number" readonly>
                    @error('phone')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endcan
            @can('fitness-management-update-title')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="title">Title</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="title" id="title" value="{{ $result ? $result->title : old('title') }}" class="form-control" placeholder="Enter Title" >
                    @error('title')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endcan
            @can('fitness-management-update-description')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="phone">Description</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="description" id="description" value="{{ $result ? $result->description : old('description') }}" class="form-control" placeholder="Enter Description" >
                    @error('description')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endcan
            @can('fitness-management-update-email-address')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="email">Email Address</label>
                </div>
                <div class="col-md-8">
                    <input type="email" name="email" id="email" value="{{ $result ? $result->user->email : old('email') }}" class="form-control" placeholder="Enter Email Address" readonly>
                    @error('email')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endcan
            @can('fitness-management-update-experience-year')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="type">Experience Year</label>
                </div>
                <div class="col-md-8">
                    <input type="number" name="experience_year" id="experience_year" value="{{ $result ? $result->experience_year : old('experience_year') }}" class="form-control" placeholder="Enter Experience Year" required>
                    @error('experience_year')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endcan
            @can('fitness-management-update-location')
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
            @can('fitness-management-update-gender')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="gender">Gender</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control badge" name="gender" id="gender">
                        <option value="" selected>Please select gender</option>
                        <option value="male" {{($result && $result->user->gender == "male")? "selected":""}}>Male</option>
                        <option value="female" {{($result && $result->user->gender == "female")? "selected":""}}>Female</option>
                        <option value="others" {{($result && $result->user->gender == "others")? "selected":""}}>Others</option>
                    </select>
                    @error('gender')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endcan
            @can('fitness-management-update-about')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="about">About</label>
                </div>
                <div class="col-md-8">
                    <textarea name="about" id="about" class="form-control" placeholder="Enter about" required>{{ $result ? $result->about : old('about') }}</textarea>
                    @error('about')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endcan
            @can('fitness-management-update-social-facebook')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="social_facebook">Social Facebook</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="social_facebook" id="social_facebook" value="{{ $result->social_facebook ?? old('social_facebook') }}" class="form-control" placeholder="Enter Facebook URL">
                    @error('social_facebook')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endcan
            @can('fitness-management-update-social-twitter')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="social_twitter">Social Twitter</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="social_twitter" id="social_twitter" value="{{ $result->twitter ?? old('social_twitter') }}" class="form-control" placeholder="Enter Twitter URL">
                    @error('social_twitter')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endcan
            @can('fitness-management-update-social-youtube')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="social_youtube">Social Youtube</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="social_youtube" id="social_youtube" value="{{ $result->social_youtube ?? old('social_youtube') }}" class="form-control" placeholder="Enter Youtube URL">
                    @error('social_youtube')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endcan 
            @can('fitness-management-update-social-instagram')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="social_instagram">Social Instagram</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="social_instagram" id="social_instagram" value="{{ $result->social_instagram ?? old('social_instagram') }}" class="form-control" placeholder="Enter Instagram URL">
                    @error('social_instagram')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endcan
            @can('fitness-management-update-social-linkedin')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="social_linkedin">Social LinkedIn</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="social_linkedin" id="social_linkedin" value="{{ $result->social_linkedin ?? old('social_linkedin') }}" class="form-control" placeholder="Enter LinkedIn URL">
                    @error('social_linkedin')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endcan
            @can('fitness-management-update-profile-status')
            <div class="col-md-6 d-md-flex align-items-center">
                <div class="col-md-4 mb-2">
                    <label for="status" class="d-block">Profile Status</label>
                </div>
                <div class="col-md-8">
                    @error('profile_status')<div class="validation-error"> {{ $message }}</div> @enderror
                    <div class="custom__radio mb-3">
                        <div class="d-flex box p-0 justify-content-between form-check w-100">
                            <div class="w-100">
                                <input class="form-check-input" name="profile_status" type="radio" @if ($result) @if ($result->status == 1) checked @endif @else checked @endif value="1" id="profile_enable">
                                <label class="form-check-label" for="profile_enable">
                                    Active
                                </label>
                            </div>
                            <div class="w-100">
                                <input class="form-check-input" type="radio" name="profile_status" value="0" @if ($result) @if ($result->status == 0) checked @endif @endif id="profile_disable">
                                <label class="form-check-label" for="profile_disable">
                                    Inactive
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan

            @if(auth()->user()->role_id == (new App\Http\Common\Constant)->SUPER_ADMIN_ROLE_ID)
            <div class="col-md-6 d-md-flex align-items-center">
                <div class="col-md-4 mb-2">
                    <label for="is_blocked" class="d-block">User Status</label>
                </div>
                <div class="col-md-8">
                    @error('status')<div class="validation-error"> {{ $message }}</div> @enderror
                    <div class="custom__radio mb-3">
                        <div class="d-flex box p-0 justify-content-between form-check">
                            <div class="w-100">
                                <input class="form-check-input" name="status" type="radio" @if ($result) @if ($result->user->status == 1) checked @endif @else checked @endif value="1" id="is_blocked_enable">
                                <label class="form-check-label" for="is_blocked_enable">
                                    Active
                                </label>
                            </div>
                            <div class="w-100">
                                <input class="form-check-input" type="radio" name="status" value="0" @if ($result) @if ($result->user->status == 0) checked @endif @endif id="is_blocked_disable">
                                <label class="form-check-label" for="is_blocked_disable">
                                    Inactive
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @can('fitness-management-update-featured')
            <div class="col-md-6 d-md-flex align-items-center">
                <div class="col-md-4 mb-2">
                    <label for="is_featured" class="d-block">Featured ?</label>
                </div>
                <div class="col-md-8">
                    @error('is_featured')<div class="validation-error"> {{ $message }}</div> @enderror
                    <div class="custom__radio mb-3">
                        <div class="d-flex box p-0 justify-content-between form-check">
                            <div class="w-100">
                                <input class="form-check-input" name="is_featured" type="radio" @if ($result) @if ($result->is_featured == 1) checked @endif @else checked @endif value="1" id="is_featured_enable">
                                <label class="form-check-label" for="is_featured_enable">
                                    Yes
                                </label>
                            </div>
                            <div class="w-100">
                                <input class="form-check-input" type="radio" name="is_featured" value="0" @if ($result) @if ($result->is_featured == 0) checked @endif @endif id="is_featured_disable">
                                <label class="form-check-label" for="is_featured_disable">
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan

{{--            @if(auth()->user()->role_id == (new App\Http\Common\Constant)->SUPER_ADMIN_ROLE_ID)--}}

{{--                <div class="col-md-6 d-md-flex align-items-center mb-3">--}}
{{--                    <div class="col-md-4">--}}
{{--                        <label for="status" class="d-block">Status</label>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-8">--}}
{{--                        @error('status')<div class="validation-error"> {{ $message }}</div> @enderror--}}
{{--                        <div class="custom__radio">--}}
{{--                            <div class="d-flex box p-0 justify-content-between form-check">--}}
{{--                                <div class="w-100">--}}
{{--                                    <input class="form-check-input" name="status" type="radio" @if ($result) @if ($result->user->status == 1) checked @endif @else checked @endif value="1" id="enable">--}}
{{--                                    <label class="form-check-label" for="enable">--}}
{{--                                        Active--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                                <div class="w-100">--}}
{{--                                    <input class="form-check-input" type="radio" name="status" value="0" @if ($result) @if ($result->user->status == 0) checked @endif @endif id="disable">--}}
{{--                                    <label class="form-check-label" for="disable">--}}
{{--                                        Inactive--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--            @endif--}}
        </div>
    </div>
    @can('fitness-management-update-save')
    <div class="bg-transparent">
        <button type="submit" class="cst_btn px-5 btn-sm">
            {{-- <i class="icon-save"></i> --}}
            {{ $result ? 'Update' : 'Save' }}
        </button>
    </div>
    @endcan
</form>
<script>
    var fitness_link = '{{$fitness_link}}';
    if (fitness_link != '') {
        $(document).find('.warning').addClass('d-none');
        $(document).find('.warning').removeClass('d-flex');
        $(document).find('.warning').removeClass('justify-content-center');
        $('.fitness_anchor').removeClass('d-none').attr('href', fitness_link);
    } else {
        $(document).find('.warning').removeClass('d-none');
        $(document).find('.warning').addClass('d-flex');
        $(document).find('.warning').addClass('justify-content-center');
        $('.fitness_anchor').addClass('d-none').attr('href', 'javascript:void(0)');
    }
    $('.fitness_name').html('{{$fitness_name}}');
</script>
