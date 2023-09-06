@extends('layouts.admin.app')
@section('page_header')
    {{ $page_header }}
@endsection
@section('content')
    <div class="container-fluid animatedParent animateOnce my-3">
        <div class="animated fadeInUpShort">
            <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf
                <div class="row">
                    <div class="col-md-8 ">
                        <div class="box_border">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="box_heading">Administrator Information</h5>
                                </div>
                                @can('admin-roles-add-name')
                                <div class="col-md-6 mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name"
                                        value="{{ $result ? $result->name : old('name') }}" class="form-control"
                                        placeholder="Administrator Name" required>
                                    @error('name')
                                        <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan

                                @can('admin-roles-add-phone')
                                <div class="col-md-6 mb-3">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" name="phone" id="phone" maxlength="15"
                                        value="{{ $result ? $result->phone : old('phone') }}" class="form-control"
                                        placeholder="Administrator Phone">
                                    @error('phone')
                                        <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan

                                @can('admin-roles-add-email')
                                <div class="col-md-6 mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email"
                                        value="{{ $result ? $result->email : old('email') }}" class="form-control"
                                        placeholder="Administrator Email Address" required>
                                    @error('email')
                                        <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan

                                @can('admin-roles-add-role')
                                <div class="col-md-6 mb-3">
                                    <label for="role">Role</label>
                                    <select id="role" name="role" required class="form-control role">
                                        <option value="">Select Role</option>
                                        @foreach ($roles as $key => $role)
                                            <option value="{{ $role->id }}"
                                                @if ($result) @if ($result->role_id == $role->id) selected @endif
                                                @endif>{{ $role->name }}
                                            </option>
                                            @foreach($role->children as $roles_child)
                                            <option value="{{ $roles_child->id }}"
                                                @if ($result) @if ($result->role_id == $roles_child->id) selected @endif
                                                @endif>{{ $roles_child->name }}
                                            </option>
                                                @foreach($roles_child->children as $nth_child)
                                                    <option value="{{ $nth_child->id }}"
                                                            @if ($result) @if ($result->role_id == $nth_child->id) selected @endif
                                                        @endif>{{ $nth_child->name }}
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan

                                @can('admin-roles-add-password')
                                <div class="col-md-6 mb-3">
                                    <label for="password">Set Password</label>
                                    <small class="text-info">(Enter Password if you want to update current password)</small>
                                    <br>
                                    <small class="text-primary">Password Format : Atleast 8 characters with atleast one
                                        uppercase letter and
                                        one special character</small>
                                    <div class="password_box">
                                        <input type="password" name="password" id="password" class="form-control"
                                            placeholder="Administrator Password" {{ $result ?: 'required' }}>
                                        <span class="show_password p-2" style="cursor:pointer"><i
                                                class="icon-eye"></i></span>
                                        <span class="hide_password p-2" style="cursor:pointer; display: none;"><i
                                                class="icon icon-eye-hidden2"></i></span>
                                        @error('password')
                                            <div class="validation-error"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                @endcan
                                {{-- <div class="col-md-6 mb-3 outlet  @if ($result)  @if ($result->role_id != App\User::OUTLET_MANAGER_ROLE) d-none @endif @else d-none @endif ">
                                <label for="outlet">Branch</label>
                                <select id="outlet" name="outlet" class="form-control outlet_dropdown">
                                    <option value="">Select Branch</option>
                                    @foreach ($outlets as $key => $outlet)
                                        <option value="{{ $outlet->id }}" @if ($result)  @if ($result->outlet_id == $outlet->id) selected @endif @endif>{{ $outlet->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('outlet')<div class="validation-error"> {{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3 city  @if ($result)  @if ($result->role_id != App\User::PICKUP_DISPATCHER_ROLE) d-none @endif @else d-none @endif ">
                                <label for="city">City</label>
                                <select id="city" name="city" class="form-control city_dropdown">
                                    <option value="">Select City</option>
                                    @foreach ($cities as $key => $city)
                                        <option value="{{ $city->id }}" @if ($result)  @if ($result->city_id == $city->id) selected @endif @endif>{{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('city')<div class="validation-error"> {{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3 region  @if ($result)  @if ($result->role_id != App\User::DELIVERY_TRANSPORTER_ROLE) d-none @endif @else d-none
                                               @endif
                                ">
                                <label for="region">Region</label>
                                <select id="region" name="region" class="form-control region_dropdown">
                                    <option value="">Select Region</option>
                                    @foreach ($regions as $key => $region)
                                        <option value="{{ $region->id }}" @if ($result)  @if ($result->region_id == $region->id) selected @endif @endif>{{ $region->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('outlet')<div class="validation-error"> {{ $message }}</div> @enderror
                            </div> --}}
                                @can('admin-roles-add-status')
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="d-block">Administrator Status</label>
                                    @error('status')
                                        <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                    <div class="custom__radio mb-3">
                                        <div class="d-flex box p-0 justify-content-between form-check">
                                            <div class="w-100">
                                                <input class="form-check-input" name="status" type="radio"
                                                    @if ($result) @if ($result->status == 1) checked @endif
                                                @else checked @endif
                                                value="1" id="enable">
                                                <label class="form-check-label" for="enable">
                                                    Enable
                                                </label>
                                            </div>
                                            <div class="w-100">
                                                <input class="form-check-input" type="radio" name="status" value="0"
                                                    @if ($result) @if ($result->status == 0) checked @endif
                                                    @endif id="disable">
                                                <label class="form-check-label" for="disable">
                                                    Disable
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endcan
                            </div>


                        </div>

                    </div>

                    <div class="col-md-4 ">
                        <div class="box_border pb-1 px-0">
                            <div class="row">
                                @can('admin-roles-add-photo')
                                <div class="col-md-12">
                                    <h5 class="box_heading px-2">Administrator Photo</h5>
                                    <hr>
                                </div>
                                <div class="col-md-12 px-4 mb-3">
                                    <input type="file" data-max-file-size="2M" name="image" id="image"
                                        value="{{ isset($result->image) ? env('ASSETS_STORAGE').$result->image : null }}"
                                        data-allowed-file-extensions="jpg png jpeg" data-max-file-size="5M"
                                        data-default-file="{{ isset($result->image) ? env('ASSETS_STORAGE').$result->image : '' }}" class="dropify">
                                    @error('image')
                                        <div class="validation-error"> {{ $message }}</div>
                                    @enderror
                                </div>
                                @endcan
                            </div>



                        </div>
                    </div>

                </div>
                <div class="bg-transparent">
                    @can('admin-roles-add-save')
                    <button type="submit" class="cst_btn btn-sm px-5"><i class="icon-save"></i>
                        {{ $result ? 'Update' : 'Save' }}
                    </button>
                    @endcan
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('.show_password').click(function() {
            $(this).hide();
            $('#password').attr('type', 'text');
            $('.hide_password').show();
        })
        $('.hide_password').click(function() {
            $(this).hide();
            $('#password').attr('type', 'password');
            $('.show_password').show();
        })
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
