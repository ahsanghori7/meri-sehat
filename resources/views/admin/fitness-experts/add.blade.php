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
                                            <h5 class="box_heading">Add Wellness Expert</h5>
                                        </div>
                                        <input type="hidden" name="user_id" value="{{ $result ? $result->fitness_experts_id : null }}">
                                        <input type="hidden" name="is_login_credentials_sent" value="{{ $result ? $result->is_login_credentials_sent : null }}">
                                        @can('fitness-management-add-name')
                                        <div class="col-md-6 d-md-flex align-items-center mb-3">
                                            <div class="col-md-4">
                                                <label for="name">Name</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" name="name" id="name" value="{{ $result ? $result->user->name : old('name') }}" class="form-control" placeholder="Wellness Expert Name" required>
                                                @error('name')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @endcan
                                        @can('fitness-management-add-phone-number')
                                        <div class="col-md-6 d-md-flex align-items-center mb-3">
                                            <div class="col-md-4">
                                                <label for="name">Phone Number</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" name="phone" id="phone" value="{{ $result ? $result->user->phone : old('phone') }}" class="form-control" placeholder="Wellness Expert Phone Number" required>
                                                @error('phone')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @endcan
                                        @can('fitness-management-add-email')
                                        <div class="col-md-6 d-md-flex align-items-center mb-3">
                                            <div class="col-md-4">
                                                <label for="email">Email</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="email" name="email" id="email" value="{{ $result ? $result->user->email : old('email') }}" class="form-control" placeholder="Wellness Expert Email" required>
                                                @error('email')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @endcan
                                        @can('fitness-management-add-experience-year')
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
                                        @can('fitness-management-add-prefix')
                                        <div class="col-md-6 d-md-flex align-items-center mb-3">
                                            <div class="col-md-4">
                                                <label for="name">Prefix</label>
                                            </div>
                                            <div class="col-md-8">
                                                <select class="form-control parent_type" name="prefix" id="prefix" required>
                                                    <option value="Dr">Dr</option>
                                                    <option value="Prof">Prof</option>
                                                    <option value="Mr">Mr</option>
                                                    <option value="Ms">Ms</option>
                                                </select>
                                                @error('prefix')
                                                <div class="validation-error"> {{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @endcan
                                        @can('fitness-management-add-location')
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
                                        @can('fitness-management-add-gender')

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
                                        @can('fitness-management-add-about')
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
                                        @can('fitness-management-social-facebook')
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
                                        @can('fitness-management-social-twitter')

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
                                        @can('fitness-management-social-youtube')

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
                                        @can('fitness-management-social-instagram')

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
                                        @can('fitness-management-social-linkedin')

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

                                    @can('fitness-management-profile-status')            
                                    <div class="col-md-6 d-md-flex align-items-center">
                                        <div class="col-md-4 mb-2">
                                            <label for="status" class="d-block">Profile Status</label>
                                        </div>
                                        <div class="col-md-8">
                                            @error('status')<div class="validation-error"> {{ $message }}</div> @enderror
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
                                    @can('fitness-management-user-status')
                                    <div class="col-md-6 d-md-flex align-items-center">
                                        <div class="col-md-4 mb-2">
                                            <label for="is_blocked" class="d-block">User Status</label>
                                        </div>
                                        <div class="col-md-8">
                                            @error('is_blocked')<div class="validation-error"> {{ $message }}</div> @enderror
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
                                    @endcan
                                    @can('fitness-management-featured')
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
                                    @can('fitness-management-image')

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

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @can('fitness-management-save')
                <div class="bg-transparent">
                    <button type="submit" class="cst_btn px-5 btn-sm">
                        {{-- <i class="icon-save"></i> --}}
                        {{ $result ? 'Update' : 'Save' }}
                    </button>
                </div>
                @endcan
        </form>
    </div>

    <div class="modal fade" id="edit_fitness_details_modal" tabindex="-1" role="dialog" aria-labelledby="edit_fitness_details_modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
</div>


@endsection
@push('scripts')
<script>


</script>
@endpush
