

    <div class="row">
        <div class="col-md-12">
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
            <div class="d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="gender">Gender</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control badge" name="gender" id="gender" required>
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
            <div class="d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="city">Relationship</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control badge" name="relationship" id="relationship required>
                        <option value="">Please select relationship</option>
                        <option {{($result && $result->relationship == 'parent') ? "selected":""}} value="parent">Parent</option>
                        <option {{($result && $result->relationship == 'spouse') ? "selected":""}} value="spouse">Spouse</option>
                        <option {{($result && $result->relationship == 'children') ? "selected":""}} value="children">Children</option>
                        <option {{($result && $result->relationship == 'sibling') ? "selected":""}} value="sibling">Sibling</option>
                    </select>
                    @error('relationship')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
                </div>
            </div>
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
            <div class="d-md-flex align-items-center mb-3">
                <div class="col-md-4">
                    <label for="height_feet">Height</label>
                </div>
                <div class="col-md-8 row">
                    {{--                    <input type="text" name="height" id="height" value="{{ $result ? $result->height : old('height') }}" class="form-control" placeholder="Enter Height e.g 5.2">--}}
                    @php
                        $height_arr = '';
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
        </div>
    </div>
