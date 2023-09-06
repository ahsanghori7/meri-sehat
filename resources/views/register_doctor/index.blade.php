@extends('layouts.register_doctor.app')
@section('form')
    <!-- MultiStep Form -->
    <div class="row">
        <div class="col-md-12">
            <form id="msform" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="text-center">
                    <img src="{{ asset('admin/img/logo.svg') }}" alt="" class="logo">
                </div>
                <fieldset class="thankyou-form">
                    <h2 class="fs-title">Acknowledgement</h2>
                    <h3 class="fs-subtitle">By clicking Yes, you agree to list yourself as a doctor on MeriSehat platform
                        to receive appointments. Do you wish to continue?</h3>
                    <div class="inline-button">
                        <input type="button" name="previous" onclick="window.location='{{ route('coming_soon') }}';"
                            class="previous action-button mt-2" value="No" />
                        <input type="button" name="next" onclick="window.location='{{ route('register_doctor_about') }}';"
                            class="next action-button-green" value="Yes" />
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    <!-- /.MultiStep Form -->
@endsection
