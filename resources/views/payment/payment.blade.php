{{-- @extends('layouts.register_doctor.app')
@section('form')
<div class="row">
    <div class="col-md-12">
        <form id="msform" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="text-center">
                <img src="{{ asset('admin/img/logo.svg') }}" alt="" class="logo">
</div>
<h2 class="fs-title">Payment</h2>
<h3 class="fs-subtitle">By clicking Yes, You agree to pay this amount : {{ $amount }}. Do you wish to
    continue?</h3>
<div class="inline-button d-flex justify-content-between">
    <a href="{{ route('payment-confirmation', ['failed', $transction_id]) }}" class="btn btn-danger text-white">
        No </a>
    <a href="{{ route('payment-confirmation', ['succeed', $transction_id]) }}" class="btn btn-success text-white">
        Yes</a>
</div>
</form>
</div>
</div>
@endsection --}}
<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&display=swap');

    .loaderContainer {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        height: 100vh;
        width: 100vw;
        font-family: 'Nunito Sans', sans-serif;
    }

    .loaderContainer img {
        width: 100%;
        max-width: 200px;
    }
    .loaderText { 
        font-weight: 700;
    }

</style>


<div class="loaderContainer">
    <img src="{{asset('admin/img/loader_gif.gif')}}" alt="Loader">
    <h4 class="loaderText">Reaching Payment Merchant...</h4>
</div>







<input id="Key1" name="Key1" type="hidden" value="tHzGpPpWN68Dm4s3">                                                                                                                              
<input id="Key2" name="Key2" type="hidden" value="9136876947610710">

<h3 style="display:none">Handshake</h3>
<form style="display:none" action="https://payments.bankalfalah.com/HS/api/HSAPI/HSAPI" id="HandshakeForm" method="post">
    <input id="HS_RequestHash" name="HS_RequestHash" type="hidden" value="">
    <input id="HS_IsRedirectionRequest" name="HS_IsRedirectionRequest" type="hidden" value="0">
    <input id="HS_ChannelId" name="HS_ChannelId" type="hidden" value="1001">
    <input id="HS_ReturnURL" name="HS_ReturnURL" type="hidden" value="{{route('payment-confirmation')}}">
    <input id="HS_MerchantId" name="HS_MerchantId" type="hidden" value="18866">
    <input id="HS_StoreId" name="HS_StoreId" type="hidden" value="024283">
    <input id="HS_MerchantHash" name="HS_MerchantHash" type="hidden" value="OUU362MB1uqF4t7WwPtgv5lSYcskSALBYAqI0Ksq7hpU6Pr9RaEUNN+b8+F2oXQ4YGvt52KqyQF8JTQkyJdWFOUOV42y4p+I">
    <input id="HS_MerchantUsername" name="HS_MerchantUsername" type="hidden" value="jelixo">
    <input id="HS_MerchantPassword" name="HS_MerchantPassword" type="hidden" value="xVSzOJ7dXblvFzk4yqF7CA==">
    <input id="HS_TransactionReferenceNumber" name="HS_TransactionReferenceNumber" autocomplete="off" placeholder="Order ID" value="{{$orderId}}">
    <button type="submit" class="btn btn-custon-four btn-danger" id="handshake">Handshake</button>
</form>


<h3 style="display:none">Page Redirection Request</h3>
<form style="display:none" action="https://Payments.bankalfalah.com/SSO/SSO/SSO" id="PageRedirectionForm" method="post" novalidate="novalidate">
    <input id="AuthToken" name="AuthToken" type="hidden" value="">
    <input id="RequestHash" name="RequestHash" type="hidden" value="">
    <input id="ChannelId" name="ChannelId" type="hidden" value="1001">
    <input id="Currency" name="Currency" type="hidden" value="PKR">
    <input id="IsBIN" name="IsBIN" type="hidden" value="0">
    <input id="ReturnURL" name="ReturnURL" type="hidden" value="{{route('payment-confirmation')}}">
    <input id="MerchantId" name="MerchantId" type="hidden" value="18866">
    <input id="StoreId" name="StoreId" type="hidden" value="024283">
    <input id="MerchantHash" name="MerchantHash" type="hidden" value="OUU362MB1uqF4t7WwPtgv5lSYcskSALBYAqI0Ksq7hpU6Pr9RaEUNN+b8+F2oXQ4YGvt52KqyQF8JTQkyJdWFOUOV42y4p+I">
    <input id="MerchantUsername" name="MerchantUsername" type="hidden" value="jelixo">
    <input id="MerchantPassword" name="MerchantPassword" type="hidden" value="xVSzOJ7dXblvFzk4yqF7CA==">
    <select autocomplete="off" id="TransactionTypeId" name="TransactionTypeId">
        <option value="1" @if($transaction->payment_method == "alfa_wallet") selected @endif>Alfa Wallet</option>
        <option value="2" @if($transaction->payment_method == "alfa_bank_account") selected @endif>Alfalah Bank Account</option>
        <option value="3" @if($transaction->payment_method == "credit_debit_card") selected @endif>Credit/Debit Card</option>
    </select>
    <input autocomplete="off" id="TransactionReferenceNumber" name="TransactionReferenceNumber" placeholder="Order ID" type="text" value="{{$orderId}}">
    <input autocomplete="off" id="TransactionAmount" name="TransactionAmount" placeholder="Transaction Amount" type="text" value="{{$amount}}">
    <button type="submit" class="btn btn-custon-four btn-danger" id="run">RUN</button>
</form>

<script type="text/javascript">
    $(document).ready($(function() {

        setTimeout(function() {
            $('#handshake').click();
        }, 1000)

        $("#handshake").click(function(e) {
            e.preventDefault();
            $("#handshake").attr('disabled', 'disabled');
            submitRequest("HandshakeForm");
            if ($("#HS_IsRedirectionRequest").val() == "1") {
                document.getElementById("HandshakeForm").submit();
            } else {
                var myData = {
                    HS_MerchantId: $("#HS_MerchantId").val()
                    , HS_StoreId: $("#HS_StoreId").val()
                    , HS_MerchantHash: $("#HS_MerchantHash").val()
                    , HS_MerchantUsername: $("#HS_MerchantUsername").val()
                    , HS_MerchantPassword: $("#HS_MerchantPassword").val()
                    , HS_IsRedirectionRequest: $("#HS_IsRedirectionRequest").val()
                    , HS_ReturnURL: $("#HS_ReturnURL").val()
                    , HS_RequestHash: $("#HS_RequestHash").val()
                    , HS_ChannelId: $("#HS_ChannelId").val()
                    , HS_TransactionReferenceNumber: $("#HS_TransactionReferenceNumber").val()
                , }


                $.ajax({
                    type: 'POST'
                    , url: 'https://payments.bankalfalah.com/HS/HS/HS'
                    , contentType: "application/x-www-form-urlencoded"
                    , data: myData
                    , dataType: "json"
                    , beforeSend: function() {}
                    , success: function(r) {
                        if (r != '') {
                            console.log(r);
                            if (r.success == "true") {
                                $("#AuthToken").val(r.AuthToken);
                                $("#ReturnURL").val(r.ReturnURL);
                                $('.loaderText').text('Redirecting you to payment screen...');
                                $('#run').click();
                            } else {
                                $('.loaderText').text('Something Went Wrong, please try again.');
                            }
                        } else {
                            alert('Error: Handshake Unsuccessful');
                        }
                    }
                    , error: function(error) {
                        alert('Error: An error occurred');
                    }
                    , complete: function(data) {
                        $("#handshake").removeAttr('disabled', 'disabled');
                    }
                });
            }

        });









        $("#run").click(function(e) {
            e.preventDefault();
            submitRequest("PageRedirectionForm");
            document.getElementById("PageRedirectionForm").submit();
        });





    }));



    function submitRequest(formName) {

        var mapString = ''
            , hashName = 'RequestHash';
        if (formName == "HandshakeForm") {
            hashName = 'HS_' + hashName;
        }

        $("#" + formName + " :input").each(function() {
            if ($(this).attr('id') != '') {
                mapString += $(this).attr('id') + '=' + $(this).val() + '&';
            }
        });

        $("#" + hashName).val(CryptoJS.AES.encrypt(CryptoJS.enc.Utf8.parse(mapString.substr(0, mapString.length - 1)), CryptoJS.enc.Utf8.parse($("#Key1").val()), {
            keySize: 128 / 8
            , iv: CryptoJS.enc.Utf8.parse($("#Key2").val())
            , mode: CryptoJS.mode.CBC
            , padding: CryptoJS.pad.Pkcs7
        }));
    }

</script>
