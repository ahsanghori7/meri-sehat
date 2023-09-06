<div class="modal fade" id="addDoctorLead" tabindex="-1" role="dialog" aria-labelledby="addDoctorLeadLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title ml-auto mr-auto" id="addDoctorLeadLabel">Add Doctor Lead</h4>
        </div>
        <div class="modal-body">
          <form method="post" action="{{ route('doctor-add') }}" class="needs-validation" id="disable_enter_submit" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="row">
            <div class="col-md-12">
                <label for="basic-url">Full Name*</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    @can('doctor-management-add-prefix')
                        <select class="form-control parent_type rounded" name="prefix" id="prefix" required>
                            <option value="Dr">Dr</option>
                            <option value="Mr">Prof</option>
                        </select>
                        @error('prefix')
                        <div class="validation-error"> {{ $message }}</div>
                        @enderror
                    @endcan  
                </div>
                    <input type="text" name="name" id="name" value="{{ $add_result ? $add_result->user->name : old('name') }}" class="form-control" placeholder="Doctor Name" required>
                    @error('name')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
            </div>
            </div>
            <div class="col-md-12">
                <label for="basic-url">Mobile Number*</label>
                <div class="input-group mb-3">
                    <input type="text" name="phone" id="phone" value="{{ $add_result ? $add_result->user->phone : old('phone') }}" class="form-control" placeholder="Doctor Phone Number" required>
                    @error('phone')
                    <div class="validation-error"> {{ $message }}</div>
                    @enderror
            </div>
            </div>
            <div class="col-md-12">
                    <label for="parents">Medical Speciality*</label>
                <select class="form-control badge" name="speciality" id="speciality">
                    @foreach ($add_specialities as $speciality)
                        <option value="{{$speciality->id}}">{{$speciality->name}}</option>
                    @endforeach
                </select>
                @error('speciality')
                <div class="validation-error"> {{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-5 mt-2">
                <label for="parents">City*</label>
                <select class="form-control badge" name="city" id="city">
                    @foreach ($add_cities as $city)
                    <option value="{{$city->id}}">{{$city->name}}</option>
                    @endforeach
                </select>
                @error('city')
                <div class="validation-error"> {{ $message }}</div>
                @enderror
        </div>
        <div class="col-md-7 mt-2">
            <label for="parents">PMC Number</label>
            <input type="text" name="pmc_no" id="pmc_no" value="{{ isset($result->pmc_no) ? $result->pmc_no : old('pmc_no') }}" class="form-control" placeholder="Enter PMC Number" required>
                @error('pmc_no')
                <div class="validation-error"> {{ $message }}</div>
                @enderror
        </div>
        <div class="col-md-12 mt-2">
            <label for="parents">Email*</label>
            <input type="email" name="email" id="email" value="{{ isset($result->user) ? $result->user->email : old('email') }}" class="form-control" placeholder="Doctor Email" required>
            @error('email')
            <div class="validation-error"> {{ $message }}</div>
            @enderror
        @error('speciality')
        <div class="validation-error"> {{ $message }}</div>
        @enderror
    </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn ml-auto btn-danger" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn mr-auto btn-primary">Add</button>
    </div>
</form>
      </div>
    </div>
  </div>
  