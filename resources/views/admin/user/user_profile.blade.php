@extends('layouts.admin.app')
@section('page_header')
 {{Str::plural('Update Profile')}}
@endsection
@section('content')




    <div class="container-fluid relative animatedParent animateOnce">
        <div class="tab-content pb-3" id="v-pills-tabContent">
            <!--Today Tab Start-->
            <div class="row p-t-b-10 ">

                <div class="col-md-6 mx-auto">

                    <div class="card">
                        <div class="card-header white">
                            <h6>Update Profile</h6>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="toast" data-title="Success" data-message="Successfully updated."
                                    data-type="success">
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="toast" data-title="Error" data-message="{{ session('error') }}"
                                    data-type="error">
                                </div>
                            @endif
                            @if (session('failed'))
                                <div class="toast" data-title="Old Password Error"
                                    data-message="{{ session('failed') }}" data-type="error">
                                </div>
                            @endif
                            <form class="needs-validation" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label for="validationCustom01">Name</label>
                                        <input type="text" name="name"
                                            value="@if ($user) {{ $user->name }} @endif"
                                            class="form-control " id="validationCustom01" placeholder="Name" required>

                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label for="validationCustom01">Phone Number</label>
                                        <input type="number" maxlength="2" name="phone"
                                            value="{{$user ? $user->phone : ''}}"
                                            class="form-control numberInput" id="validationCustom01" placeholder="Phone" required>
                                        @error('phone')
                                            <p class="text-danger">Invalid phone format</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label for="validationCustom01">Gender</label>
                                        <select name="gender" id="" class="form-control">
                                            <option {{ $user ? ($user->gender == 'male' ? 'selected' : '') : '' }}
                                                value="male">Male</option>
                                            <option {{ $user ? ($user->gender == 'female' ? 'selected' : '') : '' }}
                                                value="female">Female</option>
                                            <option {{ $user ? ($user->gender == 'others' ? 'selected' : '') : '' }}
                                                    value="others">Others</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label for="validationCustom01">City</label>
                                        <select name="city" class="form-control">
                                            @if ($cities)
                                                @foreach ($cities as $item)
                                                    <option {{ $item->id == $user->city_id ? 'selected' : '' }}
                                                        value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label for="validationCustom01">Date Of Birth</label>
                                        <div class="input-group">
                                            <input type="text" id="datetimepicker7" datepicker max="{{date('Y-m-d')}}  name="date_of_birth" class="date-time-picker form-control"
                                                data-options='{"timepicker":false, "format":"Y-m-d"}' value="2018-06-01" />
                                            <span class="input-group-append">
                                                <span class="input-group-text add-on white">
                                                    <i class="icon-calendar"></i>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label for="validationCustom01">Is Subscriber</label>
                                        <input type="number"
                                            value="@if ($user) {{ $user->is_subscribed }} @endif"
                                            readonly name="is_subscriber" class="form-control" id="validationCustom01"
                                            placeholder="Is Subscriber">

                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label for="validationCustom01">Is Verified</label>
                                        <input type="number"
                                            value="@if ($user) {{ $user->is_phone_verified }} @endif"
                                            readonly name="is_verified" class="form-control" id="validationCustom01"
                                            placeholder="Is Verified">

                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label for="validationCustom01">Image</label>
                                        <input type="file" data-max-file-size="2M" name="image" class="form-control" id="validationCustom01"
                                            placeholder="Is Verified">
                                        @if ($user)
                                            @if ($user->image)
                                                <img src="{{ env('ASSETS_STORAGE').$user->image }}" width="100" height="100" alt="">
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <button class="btn btn-primary" style="background-color: #1FA7A8" id="submit" type="submit"><i class="icon-save"></i> Update</button>
                            </form>
                        </div>

                    </div>
                </div>


            </div>
            <!--Yesterday Tab Start-->
        </div>
    </div>
@endsection
