<h3 class="mb-3">Personal info</h3>
<form method="post" class="needs-validation" id="disable_enter_submit" enctype="multipart/form-data"
      novalidate>
    @csrf
    <input type="hidden" name="user_id" value="{{ $result ? $result->doctor_id : null }}">
    <input type="hidden" name="is_login_credentials_sent" value="{{ $result ? $result->is_login_credentials_sent : null }}">
    <div class="row">
        <div class="col-md-2">
            <label for="image">Profile picture<span class="text-danger">*</span></label>
            <input type="file" data-max-file-size="2M" name="image" id="image" value="{{ $result ? env('ASSETS_STORAGE').$result->user->image : null }}" data-default-file="{{ isset($result->user->image) ? env('ASSETS_STORAGE').$result->user->image : '' }}" class="dropify">
            @error('image')
            <div class="validation-error"> {{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-10 row">
            @can('doctor-management-update-name')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="name">Doctor Name</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="name" id="name" value="{{ $result ? $result->user->name : old('name') }}" class="form-control" placeholder="Doctor Name" required>
                    @error('name')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endcan
            @can('doctor-management-update-is-pmc')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="category">PMC Number</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="pmc_no" id="pmc_no" value="{{ $result ? $result->pmc_no : old('pmc_no') }}" class="form-control" placeholder="Enter PMC Number">
                    @error('pmc_no')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endcan
            @can('doctor-management-update-prefix')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="name">Prefix</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control parent_type" name="prefix" id="prefix">
                        <option value="" {{ isset($result->prefix) ? ($result->prefix == '' || $result->prefix == null ? 'selected' : '') : '' }}> Please Select Prefix </option>
                        <option value="Dr" {{ isset($result->prefix) ? ($result->prefix == 'Dr' ? 'selected' : '') : '' }}> Dr </option>
                        <option value="Prof" {{ isset($result->prefix) ? ($result->prefix == 'Prof' ? 'selected' : '') : '' }}> Prof </option>
                        <option value="Mr" {{ isset($result->prefix) ? ($result->prefix == 'Mr' ? 'selected' : '') : '' }}> Mr </option>
                        <option value="Mrs" {{ isset($result->prefix) ? ($result->prefix == 'Mrs' ? 'selected' : '') : '' }}> Mrs </option>
                        <option value="Ms" {{ isset($result->prefix) ? ($result->prefix == 'Ms' ? 'selected' : '') : '' }}> Ms </option>
                    </select>
                    @error('prefix')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endcan
            @can('doctor-management-update-experience-year')
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
            @can('doctor-management-update-phone-number')
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
            @can('doctor-management-update-email')
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
            @can('doctor-management-update-qualification')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="type">Qualification</label>
                </div>
                <div class="col-md-8">
                    asdasdas
                </div>
            </div>
            @endcan
            @can('doctor-management-update-cnic')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="email">CNIC #</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="cnic" id="cnic" value="{{ $result ? $result->cnic : old('cnic') }}" class="form-control" placeholder="Enter CNIC">
                    @error('email')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endcan
            @can('doctor-management-update-badge')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="parents">Badge</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control badge" name="badge" id="badge">
                        <option value="">No Badge selected</option>
                        <option {{($result && $result->badge == "Friendly and Polite")? "selected":""}} value="Friendly and Polite">Friendly and Polite</option>
                    </select>
                    @error('badge')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endcan
            @can('doctor-management-update-city')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="city">City</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control badge" name="city" id="city">
                        @foreach ($cities as $city)
                            <option {{($result && $result->user->city_id == $city->id)? "selected":""}} value="{{$city->id}}">{{$city->name}}</option>
                        @endforeach
                    </select>
                    @error('badge')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endcan
            @can('doctor-management-update-waiting-time')
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
            @can('doctor-management-update-is-available')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="is_available" class="d-block">Is Available</label>
                </div>
                <div class="col-md-8">
                    @error('is_available')<div class="validation-error"> {{ $message }}</div> @enderror
                    <div class="custom__radio">
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
            @endcan
            @can('doctor-management-update-is-physical-consultancy')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="is_physical_consultancy" class="d-block">Is Physical Consultancy</label>
                </div>
                <div class="col-md-8">
                    @error('is_physical_consultancy')<div class="validation-error"> {{ $message }}</div> @enderror
                    <div class="custom__radio">
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
            @endcan
            @can('doctor-management-update-is-video-consultancy')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="is_video_consultancy" class="d-block">Is Video Consultancy</label>
                </div>
                <div class="col-md-8">
                    @error('is_video_consultancy')<div class="validation-error"> {{ $message }}</div> @enderror
                    <div class="custom__radio">
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
            @endcan
            @can('doctor-management-update-is-blocked')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="is_blocked" class="d-block">Is Blocked</label>
                </div>
                <div class="col-md-8">
                    @error('is_blocked')<div class="validation-error"> {{ $message }}</div> @enderror
                    <div class="custom__radio">
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
            @can('doctor-management-update-is-admin-verified')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="is_admin_verified" class="d-block">Is Admin Verified</label>
                </div>
                <div class="col-md-8">
                    @error('is_admin_verified')<div class="validation-error"> {{ $message }}</div> @enderror
                    <div class="custom__radio">
                        <div class="d-flex box p-0 justify-content-between form-check">
                            <div class="w-100">
                                <input class="form-check-input" name="is_admin_verified" type="radio" @if ($result) @if ($result->is_admin_verified == 1) checked @endif @else checked @endif value="1" id="is_admin_verified_enable">
                                <label class="form-check-label" for="is_admin_verified_enable">
                                    Yes
                                </label>
                            </div>
                            <div class="w-100">
                                <input class="form-check-input" type="radio" name="is_admin_verified" value="0" @if ($result) @if ($result->is_admin_verified == 0) checked @endif @endif id="is_admin_verified_disable">
                                <label class="form-check-label" for="is_admin_verified_disable">
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
            @can('doctor-management-update-is-instant-consultation')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="is_instant_consultation" class="d-block">Is Instant Consultation</label>
                </div>
                <div class="col-md-8">
                    @error('is_instant_consultation')<div class="validation-error"> {{ $message }}</div> @enderror
                    <div class="custom__radio">
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
            @can('doctor-management-update-is-featured')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="is_featured" class="d-block">Is Featured</label>
                </div>
                <div class="col-md-8">
                    @error('is_featured')<div class="validation-error"> {{ $message }}</div> @enderror
                    <div class="custom__radio">
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
            @can('doctor-management-update-is-verified')
            <div class="col-md-6 d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="is_verified" class="d-block">Is Verified</label>
                </div>
                <div class="col-md-8">
                    @error('is_verified')<div class="validation-error"> {{ $message }}</div> @enderror
                    <div class="custom__radio">
                        <div class="d-flex box p-0 justify-content-between form-check">
                            <div class="w-100">
                                <input class="form-check-input" name="is_verified" type="radio" @if ($result) @if ($result->is_verified == 1) checked @endif @else checked @endif value="1" id="is_verified_enable">
                                <label class="form-check-label" for="is_verified_enable">
                                    Yes
                                </label>
                            </div>
                            <div class="w-100">
                                <input class="form-check-input" type="radio" name="is_verified" value="0" @if ($result) @if ($result->is_verified == 0) checked @endif @endif id="is_verified_disable">
                                <label class="form-check-label" for="is_verified_disable">
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
            @if(auth()->user()->role_id == (new App\Http\Common\Constant)->SUPER_ADMIN_ROLE_ID)
                <div class="col-md-6 d-md-flex align-items-center mb-3">
                    <div class="col-md-4">
                        <label for="status" class="d-block">Status</label>
                    </div>
                    <div class="col-md-8">
                        @error('status')<div class="validation-error"> {{ $message }}</div> @enderror
                        <div class="custom__radio">
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
            @can('doctor-management-update-about')
            <div class="col-md-12 d-md-flex mb-3">
                <div class="col-md-2">
                    <label for="keywords">About</label>
                </div>
                <div class="col-md-10">
                    <textarea name="about" id="about" class="form-control summernote" placeholder="Enter about" required>{{ $result ? $result->about : old('about') }}
                    </textarea>
                    @error('about')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror

                </div>
            </div>
            @endcan
        </div>
    </div>

    <div class="bg-transparent">
        <button type="submit" class="cst_btn px-5 btn-sm">
            {{-- <i class="icon-save"></i> --}}
            {{ $result ? 'Update' : 'Save' }}
        </button>
    </div>
</form>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/lang/summernote-ko-KR.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.summernote').summernote();
    });
</script>
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
    $('.doctor_name').html('{{$doctor_name}}');

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            } else {
            }
            form.classList.add('was-validated');
        }, false);
    });
</script>
