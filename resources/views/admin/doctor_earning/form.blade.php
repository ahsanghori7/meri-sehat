@extends('layouts.admin.app')
@section('page_header')
    {{Str::singular($module_name)}}
@endsection
@section('content')
<form method="post" class="needs-validation" id="disable_enter_submit" enctype="multipart/form-data" novalidate>
    @csrf
    <div class="container-fluid animatedParent animateOnce my-3">
        <div class="animated fadeInUpShort">
            <div class="row">
                <div class="col-md-8 ">
                    <div class="box_border">
                        <div class="row">
                            <div class="col-md-12">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="consult_type">Consult Type</label>
                                    <select class="" name="consult_type">
                                        @foreach($consulttype as $value)
                                            <option value="{{$value}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                @error('code')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6 mb-3">
                                <label for="market-price">Market Price</label>
                                    <select name="market-price" id="market-price">
                                    <!-- Loop through options from a data source, e.g. array or database query -->
                                        <option value="">Select market price</option>
                                        <option value="set price">Set price</option>
                                    </select>
                                    <input type="text" name="set_price" id="set_price"
                                           value="{{ $result ? $result->set_price : old('set_price') }}" class="form-control d-none"
                                           placeholder="Enter price." disabled>
                                @error('set_price')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6 mb-3">
                                <label for="discount_percantage">Discount in %age</label>
                                    <input type="text" name="discount_percantage" id="discount_percantage"
                                           value="{{ $result ? $result->discount_percantage : old('discount_percantage') }}" class="form-control"
                                           placeholder="Enter discount fee in %age.">
                                @error('set_price')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6 mb-3">
                                <label for="discount_price">Discount Price</label>
                                    <input type="text" name="discount_price" id="discount_price"
                                           value="" class="form-control"
                                        >
                                @error('discount_price')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6 mb-3">
                                <label for="ms_commission">MS Commission</label>
                                    <input type="text" name="ms_commission" id="ms_commission"
                                           value="{{ $result ? $result->ms_commission : old('ms_commission') }}" class="form-control"
                                           >
                                @error('ms-commission')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6 mb-3">
                                <label for="penalty_charges">Penalty Charges</label>
                                    <input type="text" name="penalty_charges" id="penalty_charges"
                                           value="{{ $result ? $result->penalty_charges : old('penalty_charges') }}" class="form-control"
                                           >
                                @error('penalty-charges')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>
                            <input type="hidden" name="id" id="id"
                            value="{{ isset($settingsId) ? $settingsId : old('settingsId') }}" class="form-control"
                            >

                            <input id="commission_msg" type="checkbox" />
                            <label for="commission_msg">Show commission after discount</label>

                            <div class="col-md-6 mb-3" id="price-after-commission-div">
                                <label for="price-after-commission" id="label-commission" class="d-none">Price after commission</label>
                                    <input type="text" name="price-after-commission" id="price-after-commission"
                                           value="{{ $result ? $result->price_after_commission : old('price_after_commission') }}" class="form-control d-none"
                                           disabled >
                                @error('price-after-commission')
                                <div class="validation-error"> {{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="bg-transparent">
                        <button type="submit" class="cst_btn px-5 btn-sm">
                            {{-- <i class="icon-save"></i> --}}
                            {{ $result ? 'Update' : 'Save' }}
                        </button>
                    </div>

                </div>

            </div>
        </div>
</form>
@endsection
@push('scripts')

<script>
    $('#commission_msg').change(function(){
        if ($(this).prop('checked')) {
            $('#price-after-commission').removeClass('d-none')
            $('#label-commission').removeClass('d-none')
        } else {
            $('#price-after-commission').addClass('d-none')
            $('#label-commission').addClass('d-none')
        }
    })
    $('#market-price').change(function() {
    if($(this).val()=='set price'){

        $("#set_price").removeAttr('disabled').removeClass('d-none');

    }else if($(this).val()==''){

        $("#set_price").attr('disabled', 'disabled').addClass('d-none');
    }
    });
    let pricing = ("{{ route('doctor-earning-add') }}");
        $(document).ready(function() {
            $('#discount_percantage').on('input', function() {
                console.log($('#set_price').val())
                var inputValue = $(this).val();
                var setprice=$('#set_price').val();
                var final = (setprice * inputValue)/100;
                var temp=setprice-final;

                $('#discount_price').val(temp);
            });
            // $.ajax({
            //     url: pricing,
            //     method: 'GET',
            //     success: function(response) {
            //         // Handle the successful response from the server
            //         console.log(response);
            //     },
            //     error: function(xhr, status, error) {
            //         // Handle the error
            //         console.error(error);
            //     }
            // });

        });
</script>
@endpush

