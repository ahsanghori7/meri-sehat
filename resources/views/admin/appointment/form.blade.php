@extends('layouts.admin.app')
@section('page_header'){{ $page_header }}@endsection
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
                                <h5 class="box_heading">General Information</h5>
                            </div>
                          {{--  <div class="col-md-6 mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" value="{{ $result ? $result->name : old('name') }}" class="form-control" placeholder="Administrator Name" required>
                                @error('name')<div class="validation-error"> {{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone">Phone Number</label>
                                <input type="text" name="phone" id="phone" maxlength="15" value="{{ $result ? $result->phone : old('phone') }}" class="form-control" placeholder="Administrator Phone">
                                @error('phone')<div class="validation-error"> {{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">

                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" value="{{ $result ? $result->email : old('email') }}" class="form-control" placeholder="Administrator Email Address" required>
                                @error('email')<div class="validation-error"> {{ $message }}</div> @enderror

                            </div>


                            @include('general_crud.status_radio') --}}


                        </div>


                    </div>

                </div>

                <div class="col-md-4">
                    <div class="box_border pb-1 px-0">
                        <div class="row">


                            <div class="col-md-12 px-4 mb-3 text-center ">
                                <h6>Questionaire Form</h6>
                                <a href="#" data-id="{{$result->id}}" class="cst_btn btn-sm px-5 fill_questionaire"><i class="icon-format_align_justify"></i> Fill</a>
                            </div>


                        </div>
                    </div>
                </div>

            </div>
            {{-- <div class="bg-transparent">
                <button type="submit" class="cst_btn btn-sm px-5"><i class="icon-save"></i>
                    {{ $result ? 'Update' : 'Save' }}
                </button>
            </div> --}}
        </form>
    </div>
</div>

@endsection
@push('scripts')
<script>
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
