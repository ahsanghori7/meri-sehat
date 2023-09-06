<div class="row">
    <div class="col-md-8">
        <h3 class="mb-3">Personal info</h3>
    </div>
    <div class="col-md-4">
        <h3 class="mb-3">Family Members</h3>
    </div>
</div>
<form method="post" class="needs-validation" id="disable_enter_submit" action="{{ route('patient-edit', ['id'=>$result->e_id]) }}" enctype="multipart/form-data"
      novalidate>
    @csrf
    <input type="hidden" name="user_id" value="{{ $result ? $result->id : null }}">
    <div class="row">
        @can('patients-management-update-photo')
            <div class="col-md-2">
                <label for="image">Profile picture<span class="text-danger">*</span></label>
                <input type="file" data-max-file-size="2M" name="image" id="image" value="{{ isset($result->image) ? env('ASSETS_STORAGE').$result->image : null }}" data-default-file="{{ isset($result->image) ? env('ASSETS_STORAGE'). $result->image : '' }}" class="dropify">
                @error('image')
                <div class="validation-error"> {{ $message }}</div>
                @enderror
            </div>
        @endcan
        <div class="col-md-6">
            @can('patients-management-update-name')
                <div class=" d-md-flex align-items-center mb-3">
                    <div class="col-md-4">
                        <label for="name">Patient Name</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" name="name" id="name" value="{{ $result ? $result->name : old('name') }}" class="form-control" placeholder="Patient Name" required>
                        @error('name')
                        <div class="validation-error"> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            @endcan
            @can('patients-management-update-gender')
                <div class="d-md-flex align-items-center mb-3">
                    <div class="col-md-4">
                        <label for="gender">Gender</label>
                    </div>
                    <div class="col-md-8">
                        <select class="form-control badge" name="gender" id="gender">
                            <option value="">Please select gender</option>
                            <option {{ ($result && $result->gender == "male") ? "selected" : "" }} value="male">Male</option>
                            <option {{ ($result && $result->gender == "female") ? "selected" : "" }} value="female">Female</option>
                            <option {{ ($result && $result->gender == "others") ? "selected" : "" }} value="others">Others</option>
                        </select>
                        @error('gender')
                        <div class="validation-error"> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            @endcan
            @can('patients-management-update-city')
                <div class="d-md-flex align-items-center mb-3">
                    <div class="col-md-4">
                        <label for="city">City</label>
                    </div>
                    <div class="col-md-8">
                        <select class="form-control badge" name="city" id="city">
                            @foreach ($cities as $city)
                                <option {{($result && $result->city_id == $city->id)? "selected":""}} value="{{$city->id}}">{{$city->name}}</option>
                            @endforeach
                        </select>
                        @error('city')
                        <div class="validation-error"> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            @endcan
            @can('patients-management-update-phone')
                <div class="d-md-flex align-items-center mb-3">
                    <div class="col-md-4">
                        <label for="phone">Phone Number</label>
                    </div>
                    <div class="col-md-8">
                        <input type="number" name="phone" id="phone" value="{{ $result ? $result->phone : old('phone') }}" class="form-control" placeholder="Enter Phone Number">
                        @error('phone')
                        <div class="validation-error"> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            @endcan
            @can('patients-management-update-email')
                <div class="d-md-flex align-items-center mb-3">
                    <div class="col-md-4">
                        <label for="email">Email Address</label>
                    </div>
                    <div class="col-md-8">
                        <input type="email" name="email" id="email" value="{{ $result ? $result->email : old('email') }}" class="form-control" placeholder="Enter Email Address">
                        @error('email')
                        <div class="validation-error"> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            @endcan
            @can('patients-management-update-dob')
                <div class="d-md-flex align-items-center mb-3">
                    <div class="col-md-4">
                        <label for="birth_date">Date of Birth</label>
                    </div>
                    <div class="col-md-8">
                        {{--                    <input type="text" name="birth_date" id="birth_date" value="{{ $result ? $result->birth_date : old('birth_date') }}" class="form-control datePicker" placeholder="Enter Birth Date">--}}
                        <input type="text" id="datetimepicker7" datepicker max="{{date('Y-m-d')}}"  name="birth_date" class="date-time-picker form-control"
                        data-options='{"timepicker":false, "format":"Y-m-d"}' value="2018-06-01" />
                        @error('birth_date')
                        <div class="validation-error"> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            @endcan
            @can('patients-management-update-blood_group')
                <div class="d-md-flex align-items-center mb-3">
                    <div class="col-md-4">
                        <label for="blood_group">Blood Group</label>
                    </div>
                    <div class="col-md-8">
                        <select class="form-control badge" name="blood_group" id="blood_group">
                            <option value="">Please select blood group</option>
                            <option {{($result && $result->blood_group == 'A+' )? "selected":""}} value="A+">A+</option>
                            <option {{($result && $result->blood_group == 'A-' )? "selected":""}} value="A-">A-</option>
                            <option {{($result && $result->blood_group == 'B+' )? "selected":""}} value="A+">B+</option>
                            <option {{($result && $result->blood_group == 'B-' )? "selected":""}} value="B-">B-</option>
                            <option {{($result && $result->blood_group == 'AB+' )? "selected":""}} value="A+">AB+</option>
                            <option {{($result && $result->blood_group == 'AB-' )? "selected":""}} value="AB-">AB-</option>
                            <option {{($result && $result->blood_group == 'O+' )? "selected":""}} value="O+">O+</option>
                            <option {{($result && $result->blood_group == 'O-' )? "selected":""}} value="O-">O-</option>
                        </select>
                        @error('blood_group')
                        <div class="validation-error"> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            @endcan
            @can('patients-management-update-height')
                <div class="d-md-flex align-items-center mb-3">
                    <div class="col-md-4">
                        <label for="height_feet">Height</label>
                    </div>
                    <div class="col-md-8 row">
                        {{--                    <input type="text" name="height" id="height" value="{{ $result ? $result->height : old('height') }}" class="form-control" placeholder="Enter Height e.g 5.2">--}}
                        @php
                            $height_arr = array();
                            $height_feet = '';
                            $height_inch = '';
                            if ($result->height) {
                                $height_arr = explode(' ', $result->height);
                            }
                            if (count($height_arr) > 0) {
                                if (isset($height_arr[0])) {
                                    $height_feet = str_replace("'", "", $height_arr[0]);
                                }
                                if (isset($height_arr[1])) {
                                    $height_inch = str_replace('"', '', $height_arr[1]);
                                }
                            }
                        @endphp
                        <div class="col-md-6">
                            <select class="form-control badge" name="height_feet" id="height_feet">
                                <option value="">Please height feet</option>
                                @for ($a=0; $a<10; $a++)
                                    <option {{ $height_feet ? 'selected' : '' }}>{{ $a+1 }}</option>
                                @endfor
                            </select>
                            @error('height_feet')
                            <div class="validation-error"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <select class="form-control badge" name="height_inch" id="height_inch">
                                <option value="">Please height inch</option>
                                @for ($a=0; $a<12; $a++)
                                    <option {{ $height_inch ? 'selected' : '' }}>{{ $a+1 }}</option>
                                @endfor
                            </select>
                            @error('height_feet')
                            <div class="validation-error"> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            @endcan
            @can('patients-management-update-weight')
                <div class="d-md-flex align-items-center mb-3">
                    <div class="col-md-4">
                        <label for="weight">Weight</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" name="weight" id="weight" value="{{ $result ? $result->weight : old('weight') }}" class="form-control" placeholder="Enter Weight e.g 65">
                        @error('weight')
                        <div class="validation-error"> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            @endcan
            @can('patients-management-update-past_medical_history')
                <div class="d-md-flex align-items-center mb-3">
                    <div class="col-md-4">
                        <label for="weight">Past medical history</label>
                    </div>
                    <div class="col-md-8">
                        @if (count($result->appointment_last) > 0)
                            @foreach($result->appointment_last[0]->getCondition as $condition)
                                <div class="speciality_tags">{{$condition->condition}}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endcan
        </div>
            <div class="col-md-4 members-details">
                @foreach ($result->familyMembers as $member)
                    <div class="d-flex justify-content-between mb-4">
                        <div>
                            <p class="mb-2">{{ $member->name ? ucwords($member->name) : '-' }}</p>
                            <p>{{ $member->gender ? ucwords($member->gender) : '-' }} - {{ $member->age() ? : '0' }} years - {{ $member->relationship ? ucwords($member->relationship) : '-' }}</p>
                        </div>
                        <a href="javascript:void(0)" data-id="{{ $member->id }}" class="family_member_view"><i class="fa fa-pencil"></i></a>
                    </div>
                @endforeach
                <div class="mb-4">
                    <p>User IP <strong>192.168.123.131</strong>
                        @can('patients-management-update-add_to_black_list')
                            <a href="javascript:void(0)">Add to Blacklist</a>
                        @endcan
                    </p>
                </div>
                @can('patients-management-update-status')
{{--                @if(auth()->user()->role_id == (new App\Http\Common\Constant)->SUPER_ADMIN_ROLE_ID)--}}
                    <div class="row align-items-center mb-3">
                        <div class="col-md-4">
                            <label for="status" class="d-block">Status</label>
                        </div>
                        <div class="col-md-8">
                            @error('status')<div class="validation-error"> {{ $message }}</div> @enderror
                            <div class="custom__radio">
                                <div class="d-flex box p-0 justify-content-between form-check">
                                    <div class="w-100">
                                        <input class="form-check-input" name="status" type="radio" @if ($result) @if ($result->status == 1) checked @endif @else checked @endif value="1" id="enable">
                                        <label class="form-check-label" for="enable">
                                            Active
                                        </label>
                                    </div>
                                    <div class="w-100">
                                        <input class="form-check-input" type="radio" name="status" value="0" @if ($result) @if ($result->status == 0) checked @endif @endif id="disable">
                                        <label class="form-check-label" for="disable">
                                            Inactive
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan

{{--                @endif--}}
            </div>

    </div>

    <div class="bg-transparent">
        @can('patients-management-update-save')
            <button type="submit" class="cst_btn px-5 btn-sm">
                {{-- <i class="icon-save"></i> --}}
                {{ $result ? 'Update' : 'Save' }}
            </button>
        @endcan
    </div>
</form>


<div class="modal fade" id="edit_family_member_modal" tabindex="-1" role="dialog"
     aria-labelledby="edit_family_member_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" class="needs-validation" id="disable_enter_submit_family" enctype="multipart/form-data"
                  novalidate>
                @csrf
                <input type="hidden" name="user_id" id="user_id" value="">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Family Member</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('.patient_name').html('{{ ucfirst($result->name) ?? '-' }}');
</script>
