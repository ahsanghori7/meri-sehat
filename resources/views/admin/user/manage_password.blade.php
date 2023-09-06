@extends('layouts.admin.app')
@section('page_header')
    Manage Password
@endsection
@section('content')


            
                
                <div class="container-fluid relative animatedParent animateOnce">
                    <div class="tab-content pb-3" id="v-pills-tabContent">
                        <!--Today Tab Start-->
                        <div class="row p-t-b-10 ">
                            
                                    <div class="col-md-6 mx-auto">

                                        <div class="card">
                                            <div class="card-header white">
                                                <h6>Manage Password</h6>
                                            </div>
                                            <div class="card-body">
                                                @if (session('status')=='success')
                                                    <div class="toast"
                                                        data-title="Success"
                                                        data-message="Successfully password updated."
                                                        data-type="success">
                                                    </div>
                                                @endif
                                                @if (session('status')=='error')
                                                    <div class="toast"
                                                        data-title="Error"
                                                        data-message="Please Enter Valid Values"
                                                        data-type="error">
                                                    </div>
                                                @endif
                                                @if (session('status')=='failed')
                                                    <div class="toast"
                                                        data-title="Old Password Error"
                                                        data-message="Your old password does not match!"
                                                        data-type="error">
                                                    </div>
                                                @endif
                                                <form class="needs-validation" method="POST">
                                                    @csrf
                                                    <div class="form-row">
                                                        <div class="col-md-12 mb-3">
                                                            <label for="validationCustom01">Old Password</label>
                                                            <input type="password" name="old_password" class="form-control" id="validationCustom01" placeholder="Old Password" required>
                                                            <div class="valid-feedback">
                                                                Invalid old password
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="col-md-12 mb-3">
                                                            <label for="validationCustom01">New Password</label>
                                                            <input type="password" name="new_password" class="form-control" id="txtNewPassword" placeholder="New Password" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="col-md-12 mb-3">
                                                            <label for="validationCustom01">Confirm Password</label>
                                                            <input type="password" name="confrim_password" class="form-control" id="txtConfirmPassword" placeholder="Confrim Password" required>
                                                            <div class="registrationFormAlert" style="color:green;" id="CheckPasswordMatch"></div>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary" style="background-color: #1FA7A8; "><i class="icon-save"></i> Save</button>
                                                </form>
                                            </div>
                                            
                                        </div>
                                    </div>
                                  
                            
                        </div>
                        <!--Yesterday Tab Start-->
                    </div>
                </div>
           
@endsection
@push('scripts')
<script>
    function checkPasswordMatch() {
        var password = $("#txtNewPassword").val();
        var confirmPassword = $("#txtConfirmPassword").val();
        if (password != confirmPassword){
            $("#CheckPasswordMatch").html("Passwords does not match!");
            document.getElementById("CheckPasswordMatch").style.color = "red";
            // $("#submit").prop('disabled', true);
        }
        else{
            $("#CheckPasswordMatch").html("Passwords match.");
            document.getElementById("CheckPasswordMatch").style.color = "green";
            // $("#submit").prop('disabled', false);
        }
    }
    $(document).ready(function () {
        // $("#submit").prop('disabled', true);
       $("#txtConfirmPassword").keyup(checkPasswordMatch);
    });
    </script>
@endpush